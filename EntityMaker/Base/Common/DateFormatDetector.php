<?php
/**
 * SAM-8837: Lot item entity maker module structural adjustments for v3-5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Common;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DateFormats
 * @package Sam\EntityMaker\Base\Common
 */
class DateFormatDetector extends CustomizableClass
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
     * @return $this
     */
    public function construct(): static
    {
        return $this;
    }

    /**
     * @param Mode $mode
     * @return string[]
     */
    public function dateFormatsForMode(Mode $mode): array
    {
        $formats = match ($mode) {
            Mode::CSV => [
                Constants\Date::ISO,
                'm/d/Y h:i a T',
                'd-m-Y H:i',
                'd-m-Y H:i:s'
            ],
            Mode::SOAP => [
                'Y-m-d H:i',
                Constants\Date::ISO
            ],
            Mode::WEB_ADMIN => [
                'm/d/Y h:i a',
                'm/d/Y h:i A',
                'm/d/Y g:i a',
                'm/d/Y g:i A',
                'd-m-Y h:i a',
                'd-m-Y h:i A',
                'd-m-Y g:i a',
                'd-m-Y g:i A',
                Constants\Date::ISO
            ],
            Mode::WEB_RESPONSIVE, Mode::SSO_RESPONSIVE => [
                Constants\Date::US_DATE,
                'm/d/Y h:i a',
                'm/d/Y h:i A',
                'm/d/Y g:i a',
                'm/d/Y g:i A',
                'd-m-Y h:i a',
                'd-m-Y h:i A',
                'd-m-Y g:i a',
                'd-m-Y g:i A',
                Constants\Date::ISO
            ]
        };
        return $formats;
    }
}
