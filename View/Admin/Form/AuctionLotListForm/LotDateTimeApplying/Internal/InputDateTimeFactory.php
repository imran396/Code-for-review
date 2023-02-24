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

namespace Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Internal;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;

/**
 * Class InputDateTimeFactory
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Internal
 */
class InputDateTimeFactory extends CustomizableClass
{
    use DateHelperAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function create(string $date, int $hour, int $minute, string $meridiem, string $timezoneLocation, int $adminDateFormat): DateTime
    {
        $dateFormat = $this->getDateHelper()->getDateTimeFormatByAdminDateFormat($adminDateFormat);
        $dateTime = DateTime::createFromFormat(
            $dateFormat . ' g:i a',
            sprintf('%s %s:%02d %s', $date, $hour, $minute, strtolower($meridiem)),
            new DateTimeZone($timezoneLocation)
        );
        if ($dateTime === false) {
            throw new InvalidArgumentException(
                'Can\'t create DateTime object ' . composeLogData(
                    [
                        'date' => $date,
                        'hour' => $hour,
                        'minute' => $minute,
                        'meridiem' => $meridiem,
                        'dateFormat' => $dateFormat,
                        DateTime::getLastErrors()
                    ]
                )
            );
        }
        return $dateTime;
    }
}
