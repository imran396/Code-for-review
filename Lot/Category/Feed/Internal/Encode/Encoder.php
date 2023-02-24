<?php
/**
 * SAM-9677: Refactor \Feed\CategoryGet
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Feed\Internal\Encode;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class Encoder
 * @package Sam\Lot\Category\Feed\Internal
 * @internal
 */
class Encoder extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function encode(string $value, string $encoding): string
    {
        if ($encoding === '') {
            log_debug('No selected encoding returning original value');
            return $value;
        }

        if ($this->cfg()->get('core->feed->checkEncoding')) {
            log_debug('Check encoding');
            if (mb_check_encoding($value, $encoding) === false) {
                $message = 'Not compatible with the target encoding "' . $encoding . '"';
                log_debug($message);
                return $message;
            }
        }

        if ($value) {
            $value = mb_convert_encoding($value, $encoding, "UTF-8");
            log_debug('Convert to selected encoding');
        }

        return $value;
    }
}
