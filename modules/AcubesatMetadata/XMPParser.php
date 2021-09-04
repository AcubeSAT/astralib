<?php

namespace AstraLib\AcubesatMetadata;

use AstraLib\PDFParser\XMPMetadataEvent;
use EasyRdf\Graph;
use EasyRdf\RdfNamespace;

class XMPParser
{
    private const Literals = [
        'docid' => 'dc11:identifier',
        'nickname' => 'dc11:nickname',
        'public' => 'AcubeSAT:Public',
        'confidential' => 'AcubeSAT:Confidential',
        'templateVersion' => 'AcubeSAT:TemplateVersion',
        'dataPackageTemplateVersion' => 'AcubeSAT:DataPackageTemplateVersion',
        'subsystem' => 'AcubeSAT:Subsystem'
    ];

    private const URI = 'http://www.w3.org/1999/02/22-rdf-syntax-ns';

    public function __construct()
    {
        RdfNamespace::set("AcubeSAT", "http://helit.org/acubesat/ns/1.0#");
        RdfNamespace::set("reqClose", "http://helit.org/reqclose/ns/1.0#");
        RdfNamespace::set("xmpidq", "http://ns.adobe.com/xmp/Identifier/qual/1.0#");
    }

    public function onPDFParserXMP(XMPMetadataEvent $event) {
        $metadata = [];

        // Parse some simple literal values
        $this->xmpParseLiterals($event->getRdf(), $metadata);
        $this->xmpParseRequirements($event->getRdf(), $metadata);

        $event->addMetadata($metadata);
    }



    private function xmpParseLiterals(Graph $rdf, array &$metadata) {
        foreach (self::Literals as $metadataKey => $xmpKey) {
            if ($literal = $rdf->getLiteral(self::URI, $xmpKey)) {
                $metadata[$metadataKey] = $literal->getValue();

                // Cast boolean values
                if ($metadata[$metadataKey] === 'True') {
                    $metadata[$metadataKey] = true;
                } elseif ($metadata[$metadataKey] === 'False') {
                    $metadata[$metadataKey] = false;
                }
            }
        }
    }

    private function xmpParseRequirements(Graph $rdf, array &$metadata) {
        foreach ($rdf->get(self::URI, 'AcubeSAT:RequirementsCloseout') ?? [] as $requirement) {
            /** @var Resource $requirement */

            $requirementData = [
                'id' => $requirement->getLiteral('reqClose:id'),
                'method' => $requirement->getLiteral('reqClose:method'),
                'page' => $requirement->getLiteral('reqClose:page'),
            ];

            foreach ($requirementData as $key => &$value) {
                if (!$value) {
                    // TODO: Error handling
                    echo "There is no $key for version\n";
                } else {
                    $value = $value->getValue();
                }
            }
            unset($value); // Make sure the reference doesn't get used below
            $metadata['requirements'][$requirementData['id']] = $requirementData;
        }
    }
}