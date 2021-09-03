<?php

namespace AstraLib\PDFParser;

use App\Event\FileUpdatedEvent;

class EventListener
{
    public function onAppEventFileUpdatedEvent(FileUpdatedEvent $event)
    {
        dump($event);
    }
}