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

namespace Sam\Core\Transform\File;

use Sam\Core\Service\CustomizableClass;

/**
 * Class FileSizeRenderer
 * @package Sam\Core\Transform\File
 */
class FileSizeRenderer extends CustomizableClass
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
     * format byte size in a human readable format
     *
     * See http://www.php.net/manual/en/function.filesize.php#62656
     *
     * @param int|float $size size in bytes. It will be a float value for all Exa(10^18) values!! All below - are int values.
     * @param int $precision number of digits; optional; defaults to 3
     * @param string $mode display mode, SI or IEC; optional; defaults to SI; SI has a factor 1000, IEC factor 1024
     * @param string $bB input value unit. (b)bits or (B)Bytes; defaults to B
     * @return string formatted size
     */
    public function renderHumanReadableSize(int|float $size, int $precision = 3, string $mode = "SI", string $bB = "B"): string
    {
        $modeFactorAndSymbols = $this->fetchModeFactorAndSymbols($mode);
        $symbols = $modeFactorAndSymbols['symbols'];
        $factor = $modeFactorAndSymbols['factor'];

        if ($bB === "b") {
            // convert input value in bits to Bytes for proper calculation below.
            $size /= 8;
            $bB = "B";
        } else {
            $bB = "B";
        }

        // Calculations for size in bytes.
        for ($i = 0; $i < count($symbols) - 1 && $size >= $factor; $i++) {
            $size /= $factor;
        }

        $dotPosition = strpos((string)$size, ".");

        if ($dotPosition !== false && $dotPosition > $precision) {
            $result = round($size);
        } elseif ($dotPosition !== false) {
            $result = round($size, $precision - $dotPosition);
        } else {
            $result = $size;
        }

        return round($result, $precision) . " " . $symbols[$i] . $bB;
    }

    /**
     *
     * See about metric prefixes: https://en.wikipedia.org/wiki/Metric_prefix (List of SI prefixes)
     * @param string $mode
     * @return array
     */
    private function fetchModeFactorAndSymbols(string $mode): array
    {
        $mode = strtoupper($mode);

        if ($mode === "IEC") {
            return [
                'factor' => 1024,
                'symbols' => ["", "Ki", "Mi", "Gi", "Ti", "Pi", "Ei", "Zi", "Yi"]
            ];
        }

        return [
            'factor' => 1000,
            'symbols' => ["", "k", "M", "G", "T", "P", "E", "Z", "Y"]
        ];
    }
}
