<?php

namespace AstraLib\AcubesatMetadata;

use AstraLib\PDFParser\XMPMetadataEvent;
use EasyRdf\RdfNamespace;

class XMPParser
{
    private const Literals = [
        'public' => 'AcubeSAT:Public',
        'confidential' => 'AcubeSAT:Confidential',
        'templateVersion' => 'AcubeSAT:TemplateVersion'
    ];

    public function __construct()
    {
        RdfNamespace::set("AcubeSAT", "http://helit.org/acubesat/ns/1.0#");
    }

    public function onPDFParserXMP(XMPMetadataEvent $event) {
        $uri = 'http://www.w3.org/1999/02/22-rdf-syntax-ns'; // The base URI of all properties
        $metadata = [];

        // Parse some simple literal values
        foreach (self::Literals as $metadataKey => $xmpKey) {
            if ($literal = $event->getRdf()->getLiteral($uri, $xmpKey)) {
                $metadata[$metadataKey] = $literal->getValue();

                // Cast boolean values
                if ($metadata[$metadataKey] === 'True') {
                    $metadata[$metadataKey] = true;
                } elseif ($metadata[$metadataKey] === 'False') {
                    $metadata[$metadataKey] = false;
                }
            }
        }

        $event->addMetadata($metadata);
    }
}