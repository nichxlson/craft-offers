<?php

namespace nichxlson\offers\services;

use craft\base\Component;
use craft\helpers\ArrayHelper;
use nichxlson\offers\records\Offer;
use nichxlson\offers\records\OfferBuyXGetYFree;
use nichxlson\offers\records\OfferGetXForY;

class Offers extends Component
{
    private $offers = null;

    public function getOffers() {
        if(!is_array($this->offers) && !$this->offers) {
            $this->offers = Offer::find()->with(['offerPurchasables'])->all();
        }

        return $this->offers;
    }

    public function getOfferTypes() {
        return [
            OfferBuyXGetYFree::TYPE => [
                'class' => OfferBuyXGetYFree::class,
                'table' => OfferBuyXGetYFree::TABLE,
                'fields' => [
                    'offerQuantity',
                    'offerAmount',
                    'offerMaxQuantity',
                ]
            ],
            OfferGetXForY::TYPE => [
                'class' => OfferGetXForY::class,
                'table' => OfferGetXForY::TABLE,
                'fields' => [
                    'offerQuantity',
                    'offerAmount',
                    'offerMaxQuantity',
                ]
            ]
        ];
    }

    public function getOffersByPurchasableId(int $purchasableId) {
        $offers = $this->getOffers();

        $offersForPurchasableId = [];

        foreach($offers as $offer) {
            $offerPurchasables = $offer['offerPurchasables'];

            if(ArrayHelper::where($offerPurchasables, 'purchasableId', $purchasableId)) {
                $offersForPurchasableId[] = $offer;
            }
        }

        return $offersForPurchasableId;
    }

    public function getOffersByProductId(int $productId) {
        $offers = $this->getOffers();

        $offersForProductId = [];

        foreach($offers as $offer) {
            $offerPurchasables = $offer['offerPurchasables'];

            if(ArrayHelper::where($offerPurchasables, 'productId', $productId)) {
                $offersForProductId[] = $offer;
            }
        }

        return $offersForProductId;
    }

    public function getOffersByType(string $type) {
        return ArrayHelper::where($this->getOffers(), 'offerType', $type);
    }
}