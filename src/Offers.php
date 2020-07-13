<?php

namespace nichxlson\offers;

use Craft;
use craft\base\Plugin;
use craft\commerce\elements\Variant;
use craft\commerce\events\CustomizeVariantSnapshotDataEvent;
use craft\commerce\services\OrderAdjustments;
use craft\events\DefineBehaviorsEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\web\twig\variables\CraftVariable;
use nichxlson\offers\adjusters\OfferGetXForYAdjuster;
use nichxlson\offers\behaviors\VariantBehavior;
use nichxlson\offers\records\Offer;
use nichxlson\offers\variables\OffersVariable;
use nichxlson\offers\services\Offers as OffersService;
use yii\base\Event;

class Offers extends Plugin
{
    public static $plugin;

    public $schemaVersion = '1.0.0';

    public $hasCpSettings = false;

    public $hasCpSection = false;

    public function init() {
        parent::init();

        $this->setComponents([
            'offers' => OffersService::class,
        ]);

        $this->registerEventHandlers();

        Craft::info(
            Craft::t('offers', '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    protected function registerEventHandlers() {
        Event::on(Variant::class, Variant::EVENT_DEFINE_BEHAVIORS, function(DefineBehaviorsEvent $e) {
            $e->behaviors['offers.variant'] = VariantBehavior::class;
        });

        Event::on(Variant::class, Variant::EVENT_AFTER_CAPTURE_VARIANT_SNAPSHOT, function(CustomizeVariantSnapshotDataEvent $e) {
            $offers = $e->sender->offers;

            if($offers) {
                foreach($offers as $offer) {
                    $offer->handleAfterCaptureVariantSnapshot($e);
                }
            }
        });

        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function (Event $event) {
            $variable = $event->sender;
            $variable->set('offers', OffersVariable::class);
        });

        Event::on(OrderAdjustments::class, OrderAdjustments::EVENT_REGISTER_ORDER_ADJUSTERS, function(RegisterComponentTypesEvent $e) {
            $e->types[] = OfferGetXForYAdjuster::class;
        });
    }
}
