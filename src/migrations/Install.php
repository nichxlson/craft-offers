<?php

namespace nichxlson\offers\migrations;

use craft\commerce\records\Purchasable;
use craft\db\Migration;
use craft\helpers\MigrationHelper;
use nichxlson\offers\records\Offer;
use nichxlson\offers\records\OfferPurchasable;
use nichxlson\offers\records\OfferBuyXGetYFree;
use nichxlson\offers\records\OfferGetXForY;

class Install extends Migration
{
    public function safeUp() {
        // Create offers table
        $this->createTable(Offer::TABLE, [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'offerTypeId' => $this->integer()->notNull(),
            'offerType' => $this->string()->notNull(),
            'dateCreated' => $this->dateTime(),
            'dateUpdated' => $this->dateTime(),
            'uid' => $this->uid(),
        ]);

        // Create offer purchasables table
        $this->createTable(OfferPurchasable::TABLE, [
            'id' => $this->primaryKey(),
            'offerId' => $this->integer()->notNull(),
            'purchasableId' => $this->integer()->notNull(),
            'dateCreated' => $this->dateTime(),
            'dateUpdated' => $this->dateTime(),
            'uid' => $this->uid(),
        ]);

        $this->addForeignKey(
            null,
            OfferPurchasable::TABLE,
            'offerId',
            Offer::TABLE,
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            null,
            OfferPurchasable::TABLE,
            'purchasableId',
            Purchasable::tableName(),
            'id',
            'CASCADE'
        );

        $this->createIndex(null, OfferPurchasable::TABLE, ['offerId', 'purchasableId'], true);
        $this->createIndex(null, OfferPurchasable::TABLE, 'purchasableId', false);

        // Create buy x get y free offers table
        $this->createTable(OfferBuyXGetYFree::TABLE, [
            'id' => $this->primaryKey(),
            'offerQuantity' => $this->integer()->notNull(),
            'offerAmount' => $this->integer()->notNull(),
            'offerMaxQuantity' => $this->integer() ,
            'dateCreated' => $this->dateTime(),
            'dateUpdated' => $this->dateTime(),
            'uid' => $this->uid(),
        ]);

        // Create get x for y offers table
        $this->createTable(OfferGetXForY::TABLE, [
            'id' => $this->primaryKey(),
            'offerQuantity' => $this->integer()->notNull(),
            'offerAmount' => $this->integer()->notNull(),
            'offerMaxQuantity' => $this->integer() ,
            'dateCreated' => $this->dateTime(),
            'dateUpdated' => $this->dateTime(),
            'uid' => $this->uid(),
        ]);
    }

    public function safeDown() {
        MigrationHelper::dropAllForeignKeysToTable(OfferPurchasable::TABLE, $this);
        MigrationHelper::dropAllForeignKeysOnTable(OfferPurchasable::TABLE, $this);

        $this->dropTable(Offer::TABLE);
        $this->dropTable(OfferPurchasable::TABLE);
        $this->dropTable(OfferBuyXGetYFree::TABLE);
        $this->dropTable(OfferGetXForY::TABLE);
    }
}