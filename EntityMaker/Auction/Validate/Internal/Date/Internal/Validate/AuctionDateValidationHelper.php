<?php
/**
 * SAM-10450: Decouple auction date validation logic into internal services
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate;

use DateTime;
use Sam\Core\Date\Validate\DateFormatValidator;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\DateFormatDetector;
use Sam\EntityMaker\Base\Common\Mode;

/**
 * Class AuctionDateValidationHelper
 * @package Sam\EntityMaker\Auction\Validate\Internal\Date
 */
class AuctionDateValidationHelper extends CustomizableClass
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
     * @template T of ErrorContainerInterface
     * @param string|null $date
     * @param int $errorCode
     * @param T $errorContainer
     * @return T
     */
    public function checkRequired(?string $date, int $errorCode, ErrorContainerInterface $errorContainer): ErrorContainerInterface
    {
        if ($date === null) {
            $errorContainer->addError($errorCode);
        }
        return $errorContainer;
    }

    /**
     * @template T of ErrorContainerInterface
     * @param string|null $date
     * @param int $errorCode
     * @param T $errorContainer
     * @return T
     */
    public function checkNotEmpty(?string $date, int $errorCode, ErrorContainerInterface $errorContainer): ErrorContainerInterface
    {
        if ($date === '') {
            $errorContainer->addError($errorCode);
        }
        return $errorContainer;
    }

    /**
     * @template T of ErrorContainerInterface
     * @param string|null $date
     * @param int $errorCode
     * @param Mode $mode
     * @param T $errorContainer
     * @return T
     */
    public function checkDate(?string $date, int $errorCode, Mode $mode, ErrorContainerInterface $errorContainer): ErrorContainerInterface
    {
        if (
            $date
            && !$this->isValidDateFormat($date, $mode)
        ) {
            $errorContainer->addError($errorCode);
        }
        return $errorContainer;
    }

    /**
     * @template T of ErrorContainerInterface
     * @param string|null $startDate
     * @param string|null $endDate
     * @param int $errorCode
     * @param Mode $mode
     * @param T $errorContainer
     * @return T
     */
    public function checkDateLaterThan(
        ?string $startDate,
        ?string $endDate,
        int $errorCode,
        Mode $mode,
        ErrorContainerInterface $errorContainer
    ): ErrorContainerInterface {
        if (
            $startDate
            && $endDate
            && $this->isValidDateFormat($startDate, $mode)
            && $this->isValidDateFormat($endDate, $mode)
            && new DateTime($startDate) > new DateTime($endDate)
        ) {
            $errorContainer->addError($errorCode);
        }
        return $errorContainer;
    }

    public function isValidDateFormat(string $date, Mode $mode): bool
    {
        $dateFormats = DateFormatDetector::new()->dateFormatsForMode($mode);
        return DateFormatValidator::new()->isValidFormatDateTime($date, $dateFormats);
    }
}
