<?php

namespace AstraLib\PDFParser;

use App\Entity\Source;
use EasyRdf\Graph;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Event called when parsing XMP data. The event contains the parsed data and the RDF graph, and the listeners can
 * add additional information based on the RDF.
 */
class XMPMetadataEvent extends Event
{
    public const NAME = 'pdfparser.xmp';

    /**
     * @var array
     * @todo Consider using an ArrayCollection for this
     */
    protected $metadata;

    /**
     * @var Graph
     */
    protected $rdf;

    public function __construct(Graph $rdf, array $metadata)
    {
        $this->rdf = $rdf;
        $this->metadata = $metadata;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getRdf(): Graph
    {
        return $this->rdf;
    }

    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * Adds an array of metadata to the already existing metadata. Any values existing in both arrays (old and new)
     * are overwritten by the new values provided in this function.
     */
    public function addMetadata(array $metadata): self
    {
        $this->metadata = array_merge($this->metadata, $metadata);
        return $this;
    }

    /**
     * Adds a new metadata item to the array. If the key already existed, it is overriden.
     */
    public function addMetadatum(string $key, $value): self
    {
        $this->metadata[$key] = $value;
        return $this;
    }
}