<?php
/**
 * SAM-4688: Date validator
 * https://bidpath.atlassian.net/browse/SAM-4688
 *
 * @author        Vahagn Hovsepyan
 * @since         Dec 25, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Core\Date\Validate;

use DateTime;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DateValidator
 */
class DateFormatValidator extends CustomizableClass
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Returns whether or not $date is a valid Date, e.g.,
     * format date dd-mm-yyyy ex. 30-06-2010
     * @param string $date the date to test
     * @return bool the result
     * */
    public function isEnFormatDate(string $date): bool
    {
        if (strlen($date) !== 10) {
            return false;
        }
        if (preg_match('/^(0[1-9]|[1-2]\d|3[0-1])[-](0[1-9]|1[0-2])[-](19|20)\d{2}$/', $date)) {
            $dd = (int)substr($date, 0, 2);
            $mm = (int)substr($date, 3, 2);
            $yy = (int)substr($date, 6, 4);
            return checkdate($mm, $dd, $yy);
        }
        return false;
    }

    /**
     * Returns whether or not $date is a valid mysql Datetime, e.g.,
     * format date Y-m-d H:i:s ex. 1970-01-01 12:00:00
     * @param string $dateTimeIso the date to test
     * @return bool the result
     */
    public function isIsoFormatDateTime(string $dateTimeIso): bool
    {
        if (strlen($dateTimeIso) !== 19) {
            return false;
        }
        if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$/", $dateTimeIso, $matches)) {
            return checkdate((int)$matches[2], (int)$matches[3], (int)$matches[1]);
        }
        return false;
    }

    /**
     * Validate date
     * @param string $date
     * @param string[] $formats
     * @return bool
     */
    public function isValidFormatDateTime(string $date, array $formats = [Constants\Date::ISO]): bool
    {
        foreach ($formats as $currentFormat) {
            $dateTime = DateTime::createFromFormat($currentFormat, $date);
            if ($dateTime && $dateTime->format($currentFormat) === $date) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns whether or not $date is a valid Date, date format: mm/dd/yyyy ex. 06/30/2010. No time.
     * @param string $dateIso the date to test
     * @return bool the result
     * */
    public function isUsFormatDate(string $dateIso): bool
    {
        if (strlen($dateIso) !== 10) {
            return false;
        }
        if (preg_match('/^(0[1-9]|1[0-2])[\/](0[1-9]|[1-2]\d|3[0-1])[\/](19|20)\d{2}$/', $dateIso)) {
            $mm = (int)substr($dateIso, 0, 2);
            $dd = (int)substr($dateIso, 3, 2);
            $yy = (int)substr($dateIso, 6, 4);
            return checkdate($mm, $dd, $yy);
        }
        return false;
    }
}
