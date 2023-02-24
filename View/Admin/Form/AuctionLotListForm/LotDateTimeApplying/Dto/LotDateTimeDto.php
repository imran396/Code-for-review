<?php
/**
 * SAM-10180: Extract logic of date and time assignment to auction lots collection from the "Auction Lot List" page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 10, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotDateTimeDto
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Dto
 */
class LotDateTimeDto extends CustomizableClass
{

    public readonly string $startDate;
    public readonly int $startHour;
    public readonly int $startMinute;
    public readonly string $startMeridiem;
    public readonly string $startClosingDate;
    public readonly int $startClosingHour;
    public readonly int $startClosingMinute;
    public readonly string $startClosingMeridiem;
    public readonly string $timezoneLocation;
    public readonly int $adminDateFormat;
    public readonly int $staggerClosingLotsPerInterval;
    public readonly int $staggerClosingInterval;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        string $startDate,
        int $startHour,
        int $startMinute,
        string $startMeridiem,
        string $startClosingDate,
        int $startClosingHour,
        int $startClosingMinute,
        string $startClosingMeridiem,
        string $timezoneLocation,
        int $adminDateFormat,
        int $staggerClosingLotsPerInterval,
        int $staggerClosingInterval
    ): static {
        $this->startDate = $startDate;
        $this->startHour = $startHour;
        $this->startMinute = $startMinute;
        $this->startMeridiem = $startMeridiem;
        $this->startClosingDate = $startClosingDate;
        $this->startClosingHour = $startClosingHour;
        $this->startClosingMinute = $startClosingMinute;
        $this->startClosingMeridiem = $startClosingMeridiem;
        $this->timezoneLocation = $timezoneLocation;
        $this->adminDateFormat = $adminDateFormat;
        $this->staggerClosingLotsPerInterval = $staggerClosingLotsPerInterval;
        $this->staggerClosingInterval = $staggerClosingInterval;
        return $this;
    }
}
