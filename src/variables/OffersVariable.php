<?php

namespace nichxlson\offers\variables;

use Craft;
use craft\commerce\elements\Variant;
use craft\commerce\records\Purchasable;
use nichxlson\offers\Offers;

class OffersVariable
{
    public function getOffersByPurchasable(Purchasable $purchasable) {
        return Offers::getInstance()->offers->getOffersByPurchasableId($purchasable->id);
    }

    public function getOffersByPurchasableId(int $purchasableId) {
        return Offers::getInstance()->offers->getOffersByPurchasableId($purchasableId);
    }

    public function getOffersByVariant(Variant $variant) {
        return Offers::getInstance()->offers->getOffersByPurchasableId($variant->id);
    }

    public function getOffersByVariantId(int $variantId) {
        return Offers::getInstance()->offers->getOffersByPurchasableId($variantId);
    }
}