<?php

namespace nichxlson\offers\records;

use craft\commerce\records\Purchasable;
use craft\db\ActiveRecord;

class OfferPurchasable extends ActiveRecord
{
    const TABLE = '{{%offers_purchasables}}';

    public static function tableName() {
        return self::TABLE;
    }

    public function getOffer() {
        return $this->hasOne(Offer::class, ['id' => 'offerId']);
    }

    public function getPurchasable() {
        return $this->hasOne(Purchasable::class, ['id' => 'purchasableId']);
    }
}