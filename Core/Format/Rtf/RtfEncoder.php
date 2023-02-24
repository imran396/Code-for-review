<?php
/**
 * Helper class for Rtf document generation
 *
 * Mantis ticket:
 * SAM-1502: Feed extension / "Publish to RTF" (PBA)
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id:$
 * @since           07 Apr, 2013
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Format\Rtf;

use PHPRtfLite_Utf8;
use Sam\Core\Service\CustomizableClass;

class RtfEncoder extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Treat text to Rtf entities
     *
     * @param string $input
     * @param string $charset
     * @return string
     */
    public function encode(string $input, string $charset): string
    {
        $output = '';
        if ($charset === 'UTF-8') {
            $length = mb_strlen($input, $charset);
            $chunkSize = 5000;
            for ($i = 0; $i < $length; $i += $chunkSize) {
                $chunkText = mb_substr($input, $i, $chunkSize, $charset);
                $output .= PHPRtfLite_Utf8::getUnicodeEntities($chunkText, $charset);
            }
        } else {
            for ($i = 0, $len = strlen($input); $i < $len; $i++) {
                $value = ord($input[$i]);
                if ($value <= 127) {
                    $rtfEntity = chr($value);
                } else {
                    $rtfEntity = '\\\'' . dechex($value);
                }
                $output .= $rtfEntity;
            }
        }
        return $output;
    }

}
