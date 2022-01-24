<?php

namespace Helix\Tests\Extractors;

use Helix\Extractors\FASTQ;
use PHPUnit\Framework\TestCase;

/**
 * @group Extractors
 * @covers \Helix\Extractors\FASTQ
 */
class FASTQTest extends TestCase
{
    /**
     * @var \Helix\Extractors\FASTQ
     */
    protected $extractor;

    /**
     * @before
     */
    protected function setUp() : void
    {
        $this->extractor = new FASTQ('tests/test.fastq');
    }

    /**
     * @test
     */
    public function extract() : void
    {
        $expected = [
            'SRR001666.1 071112_SLXA-EAS1_s_7:5:1:817:345 length=72' => 'GGGTGATGGCCGCTGCCGATGGCGTCAAATCCCACCAAGTTACCCTTAACAACTTAAGGGTTTTCAAATAGA',
            'SRR001666.2 071112_SLXA-EAS1_s_7:5:1:801:338 length=72' => 'GTTCAGGGATACGACGTTTGTATTTTAAGAATCTGAAGCAGAAGTCGATGATAATACGCGTCGTTTTATCAT',
            '071112_SLXA-EAS1_s_7:5:1:817:345' => 'GGGTGATGGCCGCTGCCGATGGCGTCAAATCCCACCGTTCAGGGATACGACGTTTGTATTTTAAGAATCTGA',
        ];

        $reads = iterator_to_array($this->extractor);

        $this->assertEquals($expected, $reads);
    }
}
