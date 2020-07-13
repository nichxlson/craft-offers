<?php

namespace nichxlson\offers\interfaces;

use craft\commerce\events\CustomizeVariantSnapshotDataEvent;

interface OfferInterface
{
    public function handleAfterCaptureVariantSnapshot(CustomizeVariantSnapshotDataEvent $event);
}