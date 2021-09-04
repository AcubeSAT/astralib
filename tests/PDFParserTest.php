<?php declare(strict_types=1);

namespace App\Tests;

use AstraLib\PDFParser\EventListener;
use AstraLib\PDFParser\PDFMetadataParser;
use AstraLib\PDFParser\XMPParser;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\File;

final class PDFParserTest extends KernelTestCase
{
    public function testADC_EW_007(): void
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

        $this->assertEquals('AcubeSAT-ADC-EW-007', $results['docid']);
        $this->assertEquals('1.1', $results['version']);
        $this->assertEquals(false, $results['public']);
        $this->assertEquals('vt1.5-dev', $results['templateVersion']);
        $this->assertEquals('Attitude Determination & Control', $results['subsystem']);
    }

    public function testDDJF_TTC(): void
    {
        $parser = $this->getContainer()->get(PDFMetadataParser::class);

        $file = new File(__DIR__ . '/files/ASAT_DDJF_TTC_2020-11-11_v1.1.pdf');

        $results = $parser->parse($file);

        $this->assertEquals(27, $results['pages']);
        $this->assertEquals("TT&C Design Definition & Justification File [DDJF_TTC]", $results['Title']);

        $this->assertEquals([
            "0.1" => [
                "version" => "0.1",
                "date" => "2020-09-27",
                "status" => "RELEASED"
            ],
            "1.0" => [
                "version" => "1.0",
                "date" => "2020-10-04",
                "status" => "RELEASED"
            ],
            "1.1" => [
                "version" => "1.1",
                "date" => "2020-11-11",
                "status" => "PUBLISHED"
            ]
        ], $results['versions']);

        $this->assertEquals([
            "Konstantinos Kapoglis",
            "Eleftheria Chatziargyriou",
            "Konstantinos Kanavouras",
            "Dimitrios Stoupis"
        ], $results['authors']);

        $this->assertEquals([
            'id' => 'GS-FUN-130',
            'method' => 'A',
            'page' => '13',
        ], $results['requirements']['GS-FUN-130']);

        $this->assertEquals([
            'id' => 'COMMS-OPS-040',
            'method' => 'R',
            'page' => '26',
        ], $results['requirements']['COMMS-OPS-040']);

        $this->assertCount(18, $results['requirements']);

        $this->assertEquals('AcubeSAT-COM-TU-010', $results['docid']);
        $this->assertEquals('1.1', $results['version']);
        $this->assertEquals(true, $results['public']);
        $this->assertEquals('vt1.8', $results['templateVersion']);
        $this->assertEquals('vd1.2-dev', $results['dataPackageTemplateVersion']);
        $this->assertEquals('Communications', $results['subsystem']);

    }
}
