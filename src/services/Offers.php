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
        if(!$this->offers) {
            $this->offers = Offer::find()->with(['purchasables'])->all();
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
            $purchasables = $offer['purchasables'];

            if(ArrayHelper::where($purchasables, 'id', $purchasableId)) {
                $offersForPurchasableId[] = $offer;
            }
        }

        return $offersForPurchasableId;
    }
}