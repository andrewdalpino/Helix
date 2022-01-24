<?php

namespace Helix\Extractors;

use Helix\Exceptions\InvalidArgumentException;
use Helix\Exceptions\RuntimeException;
use Generator;

use function is_dir;
use function is_file;
use function is_readable;
use function substr;
use function trim;
use function fopen;
use function feof;
use function fgets;
use function fclose;

/**
 * FASTA
 *
 * A memory-efficient FASTA dataset extractor.
 *
 * @category    Bioinformatics
 * @package     Scienide/Helix
 * @author      Andrew DalPino
 */
class FASTA implements Extractor, Writable
{
    /**
     * The character that represents the start of the header of a new read.
     *
     * @var string
     */
    private const HEADER_DELIMITER = '>';

    /**
     * The character that represents the start of a comment.
     *
     * @var string
     */
    private const COMMENT_DELIMITER = ';';

    /**
     * The path to the file on disk.
     *
     * @var string
     */
    protected string $path;

    /**
     * @param string $path
     * @throws \Helix\Exceptions\InvalidArgumentException
     */
    public function __construct(string $path)
    {
        if (empty($path)) {
            throw new InvalidArgumentException('Path cannot be empty.');
        }

        if (is_dir($path)) {
            throw new InvalidArgumentException('Path must be to a file, folder given.');
        }

        if (!is_file($path)) {
            throw new InvalidArgumentException("Path $path is not a file.");
        }

        if (!is_readable($path)) {
            throw new InvalidArgumentException("Path $path is not readable.");
        }

        $this->path = $path;
    }

    /**
     * Export an iterable data table.
     *
     * @param iterable<string> $iterator
     * @throws \Helix\Exceptions\RuntimeException
     */
    public function export(iterable $iterator) : void
    {
        if (is_file($this->path) and !is_writable($this->path)) {
            throw new RuntimeException("Path {$this->path} is not writable.");
        }

        if (!is_file($this->path) and !is_writable(dirname($this->path))) {
            throw new RuntimeException("Path {$this->path} is not writable.");
        }

        $handle = fopen($this->path, 'w');

        if (!$handle) {
            throw new RuntimeException('Could not open file pointer.');
        }

        $line = 1;

        foreach ($iterator as $header => $sequence) {
            $length = fputs($handle, self::HEADER_DELIMITER . $header . PHP_EOL);

            if ($length === false) {
                throw new RuntimeException("Could not write header on line $line.");
            }

            $length = fputs($handle, $sequence . PHP_EOL);

            if ($length === false) {
                throw new RuntimeException("Could not write sequence on line $line.");
            }

            ++$line;
        }

        fclose($handle);
    }

    /**
     * Return an iterator for the sequences in a dataset.
     *
     * @throws \Helix\Exceptions\RuntimeException
     * @return \Generator<string>
     */
    public function getIterator() : Generator
    {
        $handle = fopen($this->path, 'r');

        if (!$handle) {
            throw new RuntimeException('Could not open file pointer.');
        }

        rewind($handle);

        $header = $sequence = '';

        while (!feof($handle)) {
            $data = trim(fgets($handle) ?: '');

            if (empty($data)) {
                continue;
            }

            switch ($data[0]) {
                case self::HEADER_DELIMITER:
                    if (!empty($sequence)) {
                        yield $header => $sequence;
                    }

                    $header = substr($data, 1);

                    $sequence = '';

                    break;

                case self::COMMENT_DELIMITER:
                    break;

                default:
                    $sequence .= $data;
            }
        }

        if (!empty($sequence)) {
            yield $header => $sequence;
        }

        fclose($handle);
    }
}
