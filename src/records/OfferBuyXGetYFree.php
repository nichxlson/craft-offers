<?php

namespace nichxlson\offers\records;

class OfferBuyXGetYFree extends Offer
{
    const TABLE = '{{%offers_buyxgetyfree}}';
    const TYPE = 'buy_x_get_y_free';

    public $offerQuantity;
    public $offerAmount;
    public $offerMaxQuantity;

    public function init() {
        $this->offerType = self::TYPE;
        parent::init();
    }

    public function beforeSave($insert) {
        $this->offerType = self::TYPE;
        return parent::beforeSave($insert);
    }
}