<?php

namespace AstraLib\PDFParser;

use EasyRdf\Exception;
use EasyRdf\Graph;
use EasyRdf\Literal;
use EasyRdf\RdfNamespace;
use EasyRdf\Resource;

/**
 * A class that reads XMP data from a PDF file and emits events to services that might want to do something with those
 * data
 */
class XMPParser
{
    public function __construct()
    {
        // Add RDF namespaces because EasyRdf requires this to fetch entries using their XML tag names
        RdfNamespace::set("xmp", "http://ns.adobe.com/xap/1.0/");
        RdfNamespace::set("xmpMM", "http://ns.adobe.com/xap/1.0/mm/");
        RdfNamespace::set("stVer", "http://ns.adobe.com/xap/1.0/sType/Version#");
    }

    public function parse(\SplFileInfo $file) {

    }

    /**
     * Given a PDF document, get its XMP data
     * @throws Exception
     */
    private function getXMPFromPDF(string $content): ?Graph {
        // Search the location of the <rdf:RDF> tag in the PDF
        $lastOffset = -1;

        // For each occurrence of <rdf:RDF
        while (true) {
            $lastOffset = $xmp_data_start = strpos($content, '<rdf:RDF', $lastOffset + 1);
            $xmp_data_end = strpos($content, '</rdf:RDF>', $xmp_data_start);

            if ($xmp_data_start === false || $xmp_data_end === false) {
                // XMP data was not found at all
                return null;
            }

            // Some XMP data was found
            $xmp_length = $xmp_data_end - $xmp_data_start;
            $xmp_data = substr($content, $xmp_data_start, $xmp_length + 10);

            // Search for AcubeSAT in the XMP, to make sure we have the correct XMP that corresponds to a modern
            // document's metadata, and not some XMP of multimedia within the document
            if (strpos($xmp_data, 'xmlns:AcubeSAT') !== false) {
                // The data was found!
                break;
            } // else, keep searching
        }

        $rdf = new Graph('http://www.w3.org/1999/02/22-rdf-syntax-ns#'); // A random URI
        $rdf->parse($xmp_data);

        return $rdf;
    }

    public function parseString(string $xmpString) {
        $rdf = new Graph('http://www.w3.org/1999/02/22-rdf-syntax-ns#'); // A random URI
        $rdf->parse($xmpString);

        return $this->parseRDF($rdf);
    }

    public function parseRDF(Graph $rdf) {
        $uri = 'http://www.w3.org/1999/02/22-rdf-syntax-ns'; // The base URI of all properties

        $metadata = [];

        // Document title
        foreach ($rdf->get($uri, 'dc11:title') ?? [] as $title) {
            /** @var Literal $title */
            $metadata['title'] = $title->getValue();
        }

        // Document authors
        foreach ($rdf->get($uri, 'dc11:creator') ?? [] as $creator) {
            /** @var Literal $creator */
            $metadata['authors'][] = $creator->getValue();
        }

        // Document changelog
        foreach ($rdf->get($uri, 'xmpmm:Versions') ?? [] as $version) {
            /** @var Resource $version */

            $versionData = [
                'version' => $version->getLiteral('stVer:version'),
                'date' => $version->getLiteral('stVer:modifyDate'),
                'status' => $version->getLiteral('stVer:status'),
            ];

            foreach ($versionData as $key => &$value) {
                if (!$value) {
                    $this->logger->warning("There is no $key for version", $versionData);
                } else {
                    $value = $value->getValue();
                }
            }
            unset($value); // Make sure the reference doesn't get used below
            if (isset($metadata['versions'][$versionData['version']])) {
//                throw new UserParseException("You have specified a version more than once.");
            }
            $metadata['versions'][$versionData['version']] = $versionData;

//            if (!in_array($versionData['status'], $this->docID->getValidVersionTypes())) {
//                throw new UserParseException("Your versions contain an unknown version type. The only acceptable types are the following: " .
//                    implode(', ', $this->docID->getValidVersionTypes()) .
//                    ". Make sure they are in CAPITAL LETTERS.");
//            }
        }

        return $metadata;
    }
}