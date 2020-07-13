<?php

namespace nichxlson\offers\adjusters;

use craft\base\Component;
use craft\commerce\base\AdjusterInterface;
use craft\commerce\elements\Order;
use craft\commerce\models\OrderAdjustment;
use nichxlson\offers\Offers;
use nichxlson\offers\records\OfferGetXForY;

class OfferGetXForYAdjuster extends Component implements AdjusterInterface
{
    public function adjust(Order $order): array {
        $adjustments = [];
        $offers = Offers::getInstance()->offers->getOffersByType(OfferGetXForY::TYPE);

        foreach($offers as $offer) {
            $purchasableIds = array_map(function($purchasable) {
                return $purchasable->purchasableId;
            }, $offer['offerPurchasables']);

            $offerQuantity = $offer->offerQuantity;
            $offerAmount = $offer->offerAmount;
            $offerMaxQuantity = $offer->offerMaxQuantity;

            foreach($order->getLineItems() as $lineItem) {
                if(!in_array($lineItem->purchasableId, $purchasableIds)) continue;

                $price = (float) $lineItem->salePrice;

                $amount = 0;

                if($lineItem->qty >= $offerQuantity) {
                    $applicableQuantity = floor($lineItem->qty / $offerQuantity);

                    if($offerQuantity == 1) {
                        $applicableQuantity = floor($lineItem->qty / 2);
                    }

                    if($offerMaxQuantity && $applicableQuantity > $offerMaxQuantity) {
                        $applicableQuantity = $offerMaxQuantity;
                    }

                    $amount = -($applicableQuantity * $price);
                }

                $adjustment = new OrderAdjustment();
                $adjustment->type = 'offer';
                $adjustment->name = $offer->title;
                $adjustment->description = 'Offer';
                $adjustment->sourceSnapshot = [
                    'lineItemId' => (int) $lineItem->id
                ];
                $adjustment->amount = $amount;
                $adjustment->setOrder($order);
                $adjustment->setLineItem($lineItem);

                if($amount != 0) {
                    $adjustments[] = $adjustment;
                }
            }
        }

        return $adjustments;
    }
}