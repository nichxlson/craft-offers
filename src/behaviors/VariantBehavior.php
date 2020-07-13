<?php

namespace nichxlson\offers\behaviors;

use nichxlson\offers\Offers;
use yii\base\Behavior;

class VariantBehavior extends Behavior
{
    private $_offers;

    public function getOffers() {
        if(!$this->_offers) {
            $variant = $this->owner;

            if(!$variant->id) {
                return null;
            }

            $this->_offers = Offers::getInstance()->offers->getOffersByPurchasableId($this->owner->id);

            return $this->_offers;
        }

        return $this->_offers;
    }
}