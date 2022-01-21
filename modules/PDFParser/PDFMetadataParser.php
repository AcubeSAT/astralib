<?php

namespace AstraLib\PDFParser;

use Psr\Log\LoggerInterface;
use Smalot\PdfParser\Parser;

class PDFMetadataParser
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Parser
     */
    private $parser;

    /*
     * @var XMPParser
     */
    private $xmpParser;

    /**
     */
    public function __construct(LoggerInterface $logger, Parser $parser, XMPParser $xmpParser)
    {
        $this->logger = $logger;
        $this->parser = $parser;
        $this->xmpParser = $xmpParser;
    }

    /**
     * @throws \Exception
     */
    public function parse(\SplFileInfo $file) : array {
        $return = [];

//        try {
            $pdf = $this->parser->parseFile($file->getPathname());
            $pdfDetails = $pdf->getDetails();

            $return['pages'] = $pdfDetails['Pages'] ?? count($pdf->getPages());
            $return = $pdf->getDetails();
            if (!isset($return['pages'])) {
                $return['pages'] = count($pdf->getPages());
            }

            // XMP metadata
            $metadataObjectName = $pdf->getDictionary()['Metadata'] ?? null;
            if ($metadataObjectName) {
                $xmpString = $pdf->getObjectById(end($metadataObjectName))->getContent();
                $xmpMetadata = $this->xmpParser->parseString($xmpString);
                $return = array_merge($return, $xmpMetadata);
            }
//        } catch (\Exception $e) {
//            $this->logger->error("Error parsing PDF document", [
//                'message' => $e->getMessage(),
//                'type' => get_class($e)
//            ]);
//        }

        return $return;
    }
}