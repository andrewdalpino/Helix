# Helix
A PHP library for counting short DNA sequences for use in Bioinformatics. Helix consists of tools for data extraction as well as an ultra-low memory hash table called *DNA Hash* specialized for counting DNA sequences. DNA Hash stores sequence counts by their up2bit encoding - a two-way hash that exploits the fact that each DNA base need only 2 bits to be fully encoded. Accordingly, DNA Hash uses less memory than a lookup table that stores raw gene sequences. In addition, DNA Hash's novel layered Bloom filter eliminates the need to explicitly store counts for sequences that have only been seen once.

- **Ultra-low** memory footprint
- **Compatible** with FASTA and FASTQ formats
- **Supports** canonical sequence counting
- **Open-source** and free to use commercially

> **Note:** The maximum sequence length is platform dependent. On a 64-bit machine, the max length is 31. On a 32-bit machine, the max length is 15.

> **Note:** Due to the probabilistic nature of the Bloom filter, DNA Hash may over count sequences at a bounded rate.

## Installation
Install into your project using [Composer](https://getcomposer.org/):

```sh
$ composer require andrewdalpino/helix
```

### Requirements
- [PHP](https://php.net/manual/en/install.php) 7.4 or above

## Example

```php
use Helix\DNAHash;
use Helix\Extractors\FASTA;
use Helix\Tokenizers\Canonical;
use Helix\Tokenizers\Kmer;

$extractor = new FASTA('example.fa');

$tokenizer = new Canonical(new Kmer(25));

$hashTable = new DNAHash(0.001);

foreach ($extractor as $sequence) {
    $tokens = $tokenizer->tokenize($sequence);

    foreach ($tokens as $token) {
        $hashTable->increment($token);
    }
}

$top10 = $hashTable->top(10);

print_r($top10);
```

```
Array
(
    [GCTATAAAAAGAAAATTTTGGAATA] => 19
    [ATTCCAAAATTTTCTTTTTATAGCC] => 19
    [TAAAAAGAAAATTTTGGAATAAAAA] => 18
    [ATAAAAAGAAAATTTTGGAATAAAA] => 18
    [TATAAAAAGAAAATTTTGGAATAAA] => 18
    [CTATAAAAAGAAAATTTTGGAATAA] => 18
    [AAATAATTTCAATTTTCTATCTCAA] => 17
    [AAAATAATTTCAATTTTCTATCTCA] => 17
    [CAAAATAATTTCAATTTTCTATCTC] => 17
    [AGATAGAAAATTGAAATTATTTTGA] => 17
)
```

## Testing
To run the unit tests:

```sh
$ composer test
```
## Static Analysis
To run static code analysis:

```sh
$ composer analyze
```

## Benchmarks
To run the benchmarks:

```sh
$ composer benchmark
```

## References
- [1] https://github.com/JohnLonginotto/ACGTrie/blob/master/docs/UP2BIT.md.
- [2] P. Melsted et al. (2011). Efficient counting of k-mers in DNA sequences using a bloom filter.
- [3] S. Deorowicz et al. (2015). KMC 2: fast and resource-frugal k-mer counting.
