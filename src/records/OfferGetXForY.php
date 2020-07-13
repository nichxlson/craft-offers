<?php

namespace nichxlson\offers\records;

use craft\commerce\events\CustomizeVariantSnapshotDataEvent;

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

    public function handleAfterCaptureVariantSnapshot(CustomizeVariantSnapshotDataEvent $e) {
        $discount = [
            'id' => $this->id,
            'type' => $this->offerType,
            'discount_quantity' => $this->offerQuantity,
            'discount_amount' => $this->offerAmount,
            'discount_max_quantity' => $this->offerMaxQuantity
        ];

        $e->fieldData['offers'][] = $discount;
    }
}