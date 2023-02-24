<?php
/**
 * Decode string using php base64_decode()
 *
 * SAM-6783: Improve email verification
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 8, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Format\Base64;

use Sam\Core\Service\CustomizableClass;

/**
 * Class Base64Decoder
 * @package Sam\Core\Format\Base64
 */
class Base64Decoder extends CustomizableClass
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
     * @return $this
     */
    public function construct(): static
    {
        return $this;
    }

    /**
     * https://www.php.net/manual/en/function.base64-encode.php#103849
     * @param string $data
     * @return string
     */
    public function decode(string $data): string
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}
