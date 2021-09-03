<?php

namespace App\Event;

use App\Entity\Source;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Event called whenever a user creates or modifies the content of a document file, linked as a Source to a Version of
 * the Document
 */
class FileUpdatedEvent extends Event
{
//    public const NAME = 'file.updated';

    /**
     * @var Source
     */
    protected $source;

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    /**
     * @return Source
     */
    public function getSource(): Source
    {
        return $this->source;
    }
}