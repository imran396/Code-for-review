<?php
/**
 * SAM-10338: Redact sensitive information in Soap error.log
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 31, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Log\Soap;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Log\Soap\Internal\Build\OutputBuilder;
use Sam\Log\Support\SupportLoggerAwareTrait;

/**
 * Class SoapRequestLogger
 * @package Sam\Log\Soap
 */
class SoapRequestLogger extends CustomizableClass
{
    use SupportLoggerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function log(string $soapRawInput, int $logLevel = Constants\Debug::DEBUG): void
    {
        $logOutputCb = static function () use ($soapRawInput) {
            return OutputBuilder::new()->build($soapRawInput);
        };
        $this->getSupportLogger()->log($logLevel, $logOutputCb);
    }
}
