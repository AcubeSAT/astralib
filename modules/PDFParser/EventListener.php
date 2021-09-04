<?php

namespace AstraLib\PDFParser;

use App\Event\FileUpdatedEvent;
use Psr\Log\LoggerInterface;
use Smalot\PdfParser\Parser;
use Symfony\Component\HttpFoundation\File\File;

class EventListener
{
     public function onAppEventFileUpdatedEvent(FileUpdatedEvent $event)
    {
        dump($event);
    }
}