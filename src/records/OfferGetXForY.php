<?php

namespace nichxlson\offers\records;

class OfferGetXForY extends Offer
{
    const TABLE = '{{%offers_getxfory}}';
    const TYPE = 'get_x_for_y';

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