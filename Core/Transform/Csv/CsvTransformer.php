<?php
/**
 * SAM-4445: Apply TextFormatter
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           05-23, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Transform\Csv;

use Sam\Core\Service\CustomizableClass;

/**
 * Class CsvTransformer
 * @package Sam\Core\Transform\Csv
 */
class CsvTransformer extends CustomizableClass
{

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Encode string from UTF-8 into the selected encoding
     * in the import/export system settings
     *
     * @param string|int|float|null $input string being encoded
     * @param string|null $toEncoding Target character encoding set, for example Windows-1252 or UTF-8
     * @param string $fromEncoding source character encoding set. Default UTF-8
     * @param bool $isCsvEncodeDoubleQuotes make "" out ouf ". Default true
     * @return string encoded string
     */
    public function convertEncoding(string|int|float|null $input, ?string $toEncoding, string $fromEncoding = 'UTF-8', bool $isCsvEncodeDoubleQuotes = true): string
    {
        $input = (string)$input;

        if (trim($input) === '') {
            return $input;
        }

        if (!$toEncoding) {
            $toEncoding = 'UTF-8';
        }

        $output = (string)mb_convert_encoding($input, $toEncoding, $fromEncoding);

        if (!mb_check_encoding($output, $toEncoding)) {
            log_error('Failed to transcode from ' . $fromEncoding . ' to ' . composeLogData([$toEncoding => $input]));
        }

        // TODO: Remove $isCsvEncodeQuotes functionality, apply escapeQuotesForCsv() separately in caller code,
        // when all csv reports will be refactored
        // Currently convertEncoding() is called from csv reporters only
        if ($isCsvEncodeDoubleQuotes) {
            $output = $this->escapeDoubleQuotesForCsv($output);
        }

        return $output;
    }

    /**
     * Encode string to UTF-8 and optionally truncate
     *
     * @param string|null $text
     * @param string|null $fromEncoding default null
     * @param int $length default 0
     * @param int $start default 0
     * @return  string|null
     */
    public function encodeToUtf8(?string $text, ?string $fromEncoding = null, int $length = 0, int $start = 0): ?string
    {
        if ($fromEncoding === null) {
            $encodingLists = mb_list_encodings();
            $fromEncoding = mb_detect_encoding($text . 'a', $encodingLists);
        }

        if ($fromEncoding !== false) { // should have a valid enconding

            if ($fromEncoding !== "UTF-8") { // no need to convert to utf8 if it is already in utf8
                $text = mb_convert_encoding($text, "UTF-8", $fromEncoding);
            }
            if (!mb_check_encoding($text, 'UTF-8')) {
                log_error(composeLogData(["Converting to UTF-8 failed for" => $text]));
                return null;
            }
            if ($length > 0) {
                $text = mb_substr($text, $start, $length, "UTF-8");
                if (!mb_check_encoding($text, 'UTF-8')) {
                    log_error(composeLogData(["Truncating converted UTF-8 string failed for" => $text]));
                    return null;
                }
            }
        } else {
            // did not find an encoding
            return null;
        }
        return $text;
    }

    /**
     * Duplicates single double-quote (temporary solution)
     * @param string $value
     * @return string
     */
    public function escapeDoubleQuotesForCsv(string $value): string
    {
        $output = str_replace('"', '""', $value);
        return $output;
    }
}
