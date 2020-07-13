<?php

namespace nichxlson\offers\records;

use craft\commerce\events\CustomizeVariantSnapshotDataEvent;
use craft\commerce\records\Purchasable;
use craft\db\ActiveRecord;
use nichxlson\offers\interfaces\OfferInterface;
use nichxlson\offers\Offers;
use yii\db\Query;

class Offer extends ActiveRecord implements OfferInterface
{
    const TABLE = '{{%offers}}';

    public static function tableName() {
        return self::TABLE;
    }

    public static function find() {
        $query = parent::find();

        $query->select('offers.*');

        $offerTypes = Offers::getInstance()->offers->getOfferTypes();

        $i = 0;

        $offerTables = [];
        $offerFields = [];

        foreach($offerTypes as $type => $offerType) {
            $tempTableName = 'offerType' . $i;
            $offerTables[] = $tempTableName;
            $query->leftJoin($offerType['table'] . ' as `' . $tempTableName . '`', '[[offers.offerTypeId]] = [[' . $tempTableName . '.id]] AND [[offers.offerType]] = \'' . $type . '\'');

            foreach($offerType['fields'] as $field) {
                $offerFields[] = $field;
            }

            $i++;
        }

        foreach(array_unique($offerFields) as $field) {
            $newFields = [];

            foreach(array_unique($offerTables) as $table) {
                $newFields[] = '`' . $table . '`.`' . $field . '`';
            }

            $query->addSelect(['coalesce(' . implode(',', $newFields) . ', NULL) as ' . $field]);
        }

        return $query;
    }

    public static function instantiate($row) {
        $offerTypes = Offers::getInstance()->offers->getOfferTypes();

        if(isset($offerTypes[$row['offerType']])) {
            return new $offerTypes[$row['offerType']]['class'];
        }

        return new self;
    }

    public function getOfferPurchasables() {
        return $this->hasMany(OfferPurchasable::class, ['offerId' => 'id']);
    }

    public function getPurchasables() {
        return $this->hasMany(Purchasable::class, ['id' => 'purchasableId'])->via('offerPurchasables');
    }

    public function getType() {
        return static::TYPE ?? '';
    }

    public function handleAfterCaptureVariantSnapshot(CustomizeVariantSnapshotDataEvent $e) {
        $e->fieldData['offers'][] = $this->toArray();
    }
}