<?php declare(strict_types=1);

namespace App\Tests;

use AstraLib\PDFParser\EventListener;
use AstraLib\PDFParser\PDFMetadataParser;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\File;

final class PDFParserTest extends KernelTestCase
{
    public function testPushAndPop(): void
    {
        $parser = $this->getContainer()->get(PDFMetadataParser::class);

        $file = new File(__DIR__ . '/files/AcubeSAT-ADC-EW-007.pdf');

        $results = $parser->parse($file);

        $this->assertEquals(10, $results['pages']);
        $this->assertEquals("Savvidis Georgios", $results['Author']);
        $this->assertEquals("Magnetic Field and Sun Position Reference Models", $results['Title']);

        $this->assertEquals([
            "1.0" => [
                "version" => "1.0",
                "date" => "26/11/2019",
                "status" => "INTERNALLY RELEASED"
            ],
            "1.1" => [
                "version" => "1.1",
                "date" => "27/11/2019",
                "status" => "INTERNALLY RELEASED"
            ]
        ], $results['versions']);

        $this->assertEquals([
            "Savvidis Georgios"
        ], $results['authors']);

        $this->assertEquals('1.1', $results['version']);
        $this->assertEquals(false, $results['public']);
        $this->assertEquals('vt1.5-dev', $results['templateVersion']);
    }
}
