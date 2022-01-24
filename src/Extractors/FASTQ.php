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
 * FASTQ
 *
 * A memory-efficient FASTQ dataset extractor.
 *
 * @category    Bioinformatics
 * @package     Scienide/Helix
 * @author      Andrew DalPino
 */
class FASTQ implements Extractor
{
    /**
     * The character that represents the start of a new read.
     *
     * @var string
     */
    private const HEADER_DELIMITER = '@';

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

        $readNextLine = false;
        $header = '';

        while (!feof($handle)) {
            $data = trim(fgets($handle) ?: '');

            if (empty($data)) {
                continue;
            }

            if ($readNextLine) {
                yield $header => $data;

                $readNextLine = false;

                continue;
            }

            if ($data[0] === self::HEADER_DELIMITER) {
                $header = substr($data, 1);

                $readNextLine = true;
            }
        }

        fclose($handle);
    }
}
