<?php
/**SAM-4676: Encoding helper
 * https://bidpath.atlassian.net/browse/SAM-4676
 *
 * @author        Vahagn Hovsepyan
 * @since         Dec 24, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>*/

namespace Sam\Settings\Encoding;

use Sam\Core\Service\CustomizableClass;

/**
 * Class EncodingHelper
 */
class EncodingHelper extends CustomizableClass
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $isAutoEncoding - if true, then we have "auto" encoding option
     * @return array
     */
    public function loadAvailable(bool $isAutoEncoding = true): array
    {
        $mainEncodings = [
            'ISO-8859-1',
            'Windows-1252',
            'UTF-8',
        ];

        if ($isAutoEncoding) {
            $mainEncodings[] = 'auto';
        }

        $phpEncodings = mb_list_encodings();

        if (!$isAutoEncoding) {
            foreach ($phpEncodings as $key => $value) {
                if ($value === 'auto') {
                    unset($phpEncodings[$key]);
                }
            }
        }
        $encodings = array_merge($mainEncodings, $phpEncodings);
        $encodings = array_unique($encodings);
        sort($encodings);
        return $encodings;
    }

    /**
     * @param string $encoding
     * @param bool $isAutoEncoding
     * @return bool
     */
    public function isAvailable(string $encoding, bool $isAutoEncoding = true): bool
    {
        $encodings = $this->loadAvailable($isAutoEncoding);
        return in_array($encoding, $encodings, true);
    }
}
