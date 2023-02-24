<?php
/**
 * SAM-4420: Refactor Util_Date to DateHelper
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/4/2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Date;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Locale\FormatConverter\DateTimeFormatConverterCreateTrait;
use Sam\Locale\Formatter\DateTimeFormatter;
use Sam\Locale\Formatter\DateTimeFormatterAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Timezone\ApplicationTimezoneProviderAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Timezone;

/**
 * Class DateHelper
 * Method arguments are nullable for ease of usage, because caller rather often may provide null input and this causes null result.
 */
class DateHelper extends CustomizableClass
{
    use ApplicationTimezoneProviderAwareTrait;
    use CurrentDateTrait;
    use DateTimeFormatConverterCreateTrait;
    use DateTimeFormatterAwareTrait;
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;
    use TimezoneLoaderAwareTrait;
    use TranslatorAwareTrait;

    protected array $cachedConvertedFormats = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Convert date to UTC
     * @template T of DateTimeInterface|null
     * @param T $date null leads to null result
     * @param Timezone|null $tz null leads to null result
     * @return T|DateTime
     */
    public function convertToUtc(?DateTimeInterface $date, ?Timezone $tz): ?DateTimeInterface
    {
        if ($date && $tz) {
            return $this->convertTimezone($date, $tz->Location, 'UTC');
        }
        return null;
    }

    /**
     * Convert date to UTC
     * @template T of DateTimeInterface|null
     * @param T $date null leads to null result
     * @param int|null $tzId null leads to null result
     * @return T|DateTime
     */
    public function convertToUtcByTzId(?DateTimeInterface $date, ?int $tzId): ?DateTimeInterface
    {
        if ($date && $tzId) {
            $tz = $this->getTimezoneLoader()->load($tzId);
            return $this->convertToUtc($date, $tz);
        }
        return null;
    }

    /**
     * @template T of DateTimeInterface|null
     * @param T $date null leads to null result
     * @param int|null $tzId null leads to null result
     * @return T|DateTime
     */
    public function convertUtcToTzById(?DateTimeInterface $date, ?int $tzId): ?DateTimeInterface
    {
        $tz = $this->getTimezoneLoader()->load($tzId);
        if ($date && $tz) {
            $date = $this->convertTimezone($date, 'UTC', $tz->Location);
        }
        return $date;
    }

    /**
     * @template T of DateTimeInterface|null
     * @param T $date
     * @param string $location
     * @return T|DateTime
     */
    public function convertUtcToTzByLocation(?DateTimeInterface $date, string $location): ?DateTimeInterface
    {
        if (
            $date
            && $location
        ) {
            $date = $this->convertTimezone($date, 'UTC', $location);
        }
        return $date;
    }

    /**
     * @template T of DateTimeInterface|null
     * @param T $dateUtc null leads to null result
     * @param Timezone|null $tz null leads to null result
     * @return T|DateTime
     */
    public function convertUtcToTz(?DateTimeInterface $dateUtc, ?Timezone $tz): ?DateTimeInterface
    {
        $date = $dateUtc;
        if ($dateUtc && $tz) {
            $date = $this->convertTimezone($dateUtc, 'UTC', $tz->Location);
        }
        return $date;
    }

    /**
     * Convert date in UTC to TZ of account
     * @template T of DateTimeInterface|null
     * @param T $dateUtc null leads to null result
     * @param int|null $accountId null means to find and use system account
     * @return T|DateTime
     */
    public function convertUtcToSys(?DateTimeInterface $dateUtc, ?int $accountId = null): ?DateTimeInterface
    {
        if ($dateUtc) {
            $tz = $this->getTimezoneLoader()->loadSystemTimezone($accountId);
            return $this->convertUtcToTz($dateUtc, $tz);
        }
        return null;
    }

    /**
     * @param string|null $dateIso null leads to null result
     * @param int|null $accountId null means to find and use system account
     * @return DateTime|null
     * @throws Exception
     */
    public function convertUtcToSysByDateIso(?string $dateIso, ?int $accountId = null): ?DateTime
    {
        if ($dateIso) {
            $dateUtc = new DateTime($dateIso);
            return $this->convertUtcToSys($dateUtc, $accountId);
        }
        return null;
    }

    /**
     * @param int|null $ts timestamp, null leads to null result
     * @param int|null $accountId null means to find and use system account
     * @return DateTime|null
     */
    public function convertUtcToSysByTimestamp(?int $ts, ?int $accountId = null): ?DateTime
    {
        if ($ts) {
            $dateUtc = (new DateTime())->setTimestamp($ts);
            return $this->convertUtcToSys($dateUtc, $accountId);
        }
        return null;
    }

    /**
     * @template T of DateTimeInterface|null
     * @param T $date null leads to null result
     * @param int|null $accountId null means to find and use system account
     * @return T|DateTime
     */
    public function convertSysToUtc(?DateTimeInterface $date, ?int $accountId = null): ?DateTimeInterface
    {
        if ($date) {
            $sysTz = $this->getTimezoneLoader()->loadSystemTimezone($accountId);
            return $this->convertToUtc($date, $sysTz);
        }
        return null;
    }

    /**
     * @param string $dateIso
     * @param int|null $accountId null means to find and use system account
     * @return DateTime
     * @throws Exception
     */
    public function convertSysToUtcByDateIso(string $dateIso, ?int $accountId = null): DateTime
    {
        return $this->convertSysToUtc(new DateTime($dateIso), $accountId);
    }

    /**
     * @template T of DateTimeInterface|null
     * @param T $dateTime null leads to null result
     * @param string $timezoneFrom
     * @param string $timezoneTo
     * @return T|DateTime
     */
    public function convertTimezone(?DateTimeInterface $dateTime, string $timezoneFrom, string $timezoneTo): ?DateTimeInterface
    {
        if ($dateTime && $timezoneFrom !== $timezoneTo) {
            $dateTime = new DateTime($dateTime->format('Y-m-d\TH:i:s'), new DateTimeZone($timezoneFrom));
            $dateTime = $dateTime->setTimezone(new DateTimeZone($timezoneTo));
        }
        return $dateTime;
    }

    /**
     * @param string $location
     * @param DateTimeInterface|null $forDate null leads to current date
     * @return string
     */
    public function getTimezoneCodeByLocation(string $location, ?DateTimeInterface $forDate = null): string
    {
        $forDate = $forDate ?: $this->getCurrentDateUtc();
        try {
            $code = (new DateTime($forDate->format('Y-m-d\TH:i:s'), new DateTimeZone($location)))->format('T');
        } catch (Exception $e) {
            log_error('Cannot find timezone code by location' . composeSuffix(['error' => $e->getMessage(), 'code' => $e->getCode()]));
            $code = '';
        }
        return $code;
    }

    /**
     * @param string $location
     * @param DateTimeInterface|null $forDate null leads to current date
     * @return string
     */
    public function getTimezoneOffsetByLocation(string $location, ?DateTimeInterface $forDate = null): string
    {
        $forDate = $forDate ?: $this->getCurrentDateUtc();
        try {
            $offset = (new DateTime($forDate->format('Y-m-d\TH:i:s'), new DateTimeZone($location)))->format('Z');
        } catch (Exception $e) {
            log_error('Cannot find timezone code by location' . composeSuffix(['error' => $e->getMessage(), 'code' => $e->getCode()]));
            $offset = '';
        }
        return $offset;
    }

    /**
     * Make \DateTime object from date string
     * @param string $dateFormatted
     * @return DateTime|null
     */
    public function convertCsvFormattedToDate(string $dateFormatted): ?DateTime
    {
        $date = DateTime::createFromFormat('d.m.Y H:i', $dateFormatted);
        if ($date) {
            return $date;
        }

        return strtotime($dateFormatted) ? new DateTime($dateFormatted) : null;
    }

    /**
     * Return current date converted to timezone defined for account
     * @param int|null $accountId null means system account
     * @return DateTime
     */
    public function detectCurrentDateSys(?int $accountId = null): DateTime
    {
        $sysTz = $this->getTimezoneLoader()->loadSystemTimezone($accountId);
        return $this->convertUtcToTz($this->detectCurrentDateUtc(), $sysTz);
    }

    /**
     * @return DateTime
     */
    public function detectCurrentDateUtc(): DateTime
    {
        return $this->getCurrentDateUtc();
    }

    /**
     * Return formatted date according to php regular date formatting
     * @param string $dateIso
     * @param string $format
     * @return string
     */
    public function formatDate(string $dateIso, string $format): string
    {
        if (!$dateIso) {
            return '';
        }

        return (new DateTime($dateIso))->format($format);
    }

    /**
     * Parse passed date according to "admin date format" setting and return date ready for storing in db
     * @param string $dateIso
     * @param int|null $adminDateFormat null means default value of Constants\Date::ADF_US
     * @return string
     */
    public function formatDateByAdminDateFormat(string $dateIso, ?int $adminDateFormat = null): string
    {
        if (!$adminDateFormat) {
            $adminDateFormat = Constants\Date::ADF_US;
        }

        if ($adminDateFormat === Constants\Date::ADF_US) {
            return substr($dateIso, -4) . '-' . substr($dateIso, 0, 2) . '-' . substr($dateIso, 3, 2);
        }

        // else $adminDateFormat === Constants\Date::ADF_AU
        return substr($dateIso, -4) . '-' . substr($dateIso, 3, 2) . '-' . substr($dateIso, 0, 2);
    }

    /**
     * @param DateTime|DateTimeImmutable|null $date - null leads to empty result.
     * @param int|null $accountId - null means use system account.
     * @param string|null $tzLocation - timezone.Location. When null, use location from account's timezone.
     * @param string|null $displayType - defines format. null means to use default value of formattedDate().
     * @param string|null $format - date format to support translatable formats. null means use format from $displayType.
     * @return string
     * @throws Exception
     */
    public function formatUtcDate(
        DateTime|DateTimeImmutable|null $date,
        ?int $accountId = null,
        ?string $tzLocation = null,
        ?string $displayType = null,
        ?string $format = null
    ): string {
        if ($date === null) {
            return '';
        }

        if ($tzLocation) {
            $date->setTimezone(new DateTimeZone($tzLocation));
        }

        return $this->formattedDate($date, $accountId, $tzLocation, $displayType, $format);
    }

    /**
     * @param string|null $date - null leads to empty result.
     * @param int|null $accountId - null means use system account.
     * @param string|null $tzLocation - timezone.Location. When null, use location from account's timezone.
     * @param string|null $displayType - defines format. null means to use default value of formattedDate().
     * @param string|null $format - date format to support translatable formats. null means use format from $displayType.
     * @return string
     * @throws Exception
     */
    public function formatUtcDateIso(
        ?string $date = null,
        ?int $accountId = null,
        ?string $tzLocation = null,
        ?string $displayType = null,
        ?string $format = null
    ): string {
        if ($date) {
            $dateTime = new DateTime($date);
            return $this->formatUtcDate($dateTime, $accountId, $tzLocation, $displayType, $format);
        }
        return '';
    }

    /**
     * Return a formatted date and time with time zone according date formatting.
     * @param DateTimeInterface|null $date - null leads to empty result.
     * @param int|null $accountId - null means use system account.
     * @param string|null $tzLocation - timezone.Location. When null, use location from account's timezone.
     * @param string|null $displayType - defines format. null means to use default value 'AUCTIONS_DATE_LONG'.
     * @param string|null $format - date format to support translatable formats. null means use format from $displayType.
     * @return string
     */
    public function formattedDate(
        ?DateTimeInterface $date,
        ?int $accountId = null,
        ?string $tzLocation = null,
        ?string $displayType = null,
        ?string $format = null
    ): string {
        if ($date === null) {
            log_trace(
                "Null date"
                . composeSuffix(['acc' => $accountId, 'tzLocation' => $tzLocation, 'displayType' => $displayType, 'format' => $format])
            );
            return '';
        }

        if (!$accountId) {
            $accountId = $this->getSystemAccountId();
        }

        if (!$displayType) {
            $displayType = 'AUCTIONS_DATE_LONG';
        }

        if (!$format) {
            $format = $this->getTranslator()->translate($displayType, 'auctions', $accountId);
        }

        $suffix = '';
        if ($this->hasTimeInFormat($format)) {
            if (!$tzLocation) {
                $accountTz = $this->getApplicationTimezoneProvider()->getAccountTimezone($accountId);
                $tzLocation = $accountTz->Location;
            }

            $suffix = ' ' . $tzLocation;
            if ($this->getApplicationTimezoneProvider()->isTimezoneAvailable($tzLocation)) {
                $suffix = ' ' . $this->getTimezoneCodeByLocation($tzLocation, $date);
            }
        }

        return $date->format($format) . $suffix;
    }

    /**
     * @param string $format
     * @return bool
     */
    private function hasTimeInFormat(string $format): bool
    {
        return str_contains($format, 'g')
            || str_contains($format, 'G')
            || str_contains($format, 'h')
            || str_contains($format, 'H');
    }

    /**
     * @param string|null $dateIso - null leads to empty result.
     * @param int|null $accountId - null means use system account.
     * @param string|null $tzLocation - timezone.Location. When null, use location from account's timezone.
     * @param string|null $displayType - defines format. null means to use default value of formattedDate().
     * @param string|null $format - date format to support translatable formats. null means use format from $displayType.
     * @return string
     * @throws Exception
     */
    public function formattedDateByDateIso(
        ?string $dateIso,
        ?int $accountId = null,
        ?string $tzLocation = null,
        ?string $displayType = null,
        ?string $format = null
    ): string {
        if (!$dateIso) {
            log_debug('Empty date ISO string passed to date formatting function');
            return '';
        }
        $date = new DateTime($dateIso);
        return $this->formattedDate($date, $accountId, $tzLocation, $displayType, $format);
    }

    /**
     * @param int|null $timestamp - null leads to empty result.
     * @param int|null $accountId - null means use system account.
     * @param string|null $tzLocation - timezone.Location. When null, use location from account's timezone.
     * @param string|null $displayType - defines format. null means to use default value of formattedDate().
     * @param string|null $format - date format to support translatable formats. null means use format from $displayType.
     * @return string
     */
    public function formattedDateByTimestamp(
        ?int $timestamp,
        ?int $accountId = null,
        ?string $tzLocation = null,
        ?string $displayType = null,
        ?string $format = null
    ): string {
        if (!$timestamp) {
            log_debug('Empty timestamp passed to date formatting function');
            return '';
        }
        $date = (new DateTime())->setTimestamp($timestamp);
        return $this->formattedDate($date, $accountId, $tzLocation, $displayType, $format);
    }

    /**
     * Return a formatted date without time in qcodo format way
     * @param DateTimeInterface|null $dateTime - null leads to empty result.
     * @param int|null $accountId - null means system account.
     * @return string
     */
    public function formattedDateWithoutTime(?DateTimeInterface $dateTime, ?int $accountId = null): string
    {
        if (!$dateTime) {
            log_debug('Null date passed to formatted rendering function');
            return '';
        }
        if ($accountId === null) {
            $accountId = $this->getSystemAccountId();
        }
        $format = $this->getTranslator()->translate('AUCTIONS_DATE_SHORT', 'auctions', $accountId);

        return $dateTime->format($format);
    }

    /**
     * Return display date format by AdminDateFormat(auction parameters)
     * @param int|null $adminDateFormat null means default value of Constants\Date::ADF_US
     * @return string
     */
    public function getDisplayFormatByAdminDateFormat(?int $adminDateFormat = null): string
    {
        if (!$adminDateFormat) {
            $adminDateFormat = Constants\Date::ADF_US;
        }

        if ($adminDateFormat === Constants\Date::ADF_US) {
            return '%m/%d/%Y';
        }
        // else for Constants\Date::ADF_AU
        return '%d-%m-%Y';
    }

    /**
     * return date time format by AdminDateFormat(auction parameters)
     * @param int|null $adminDateFormat null means default value Constants\Date::ADF_US
     * @return string
     */
    public function getDateTimeFormatByAdminDateFormat(?int $adminDateFormat = null): string
    {
        if (!$adminDateFormat) {
            $adminDateFormat = Constants\Date::ADF_US;
        }

        if ($adminDateFormat === Constants\Date::ADF_US) {
            return Constants\Date::US_DATE;
        }

        // Constants\Date::ADF_AU
        return 'd-m-Y';
    }

    public function getDateDisplayFormat(int $accountId, bool $isFrontend = false): string
    {
        // Frontend pages use US date format only
        if ($isFrontend) {
            return Constants\Date::US_DATE;
        }

        // Admin pages use US or AU formats
        $adminDateFormat = (int)$this->getSettingsManager()->get(Constants\Setting::ADMIN_DATE_FORMAT, $accountId);
        return $this->getDateTimeFormatByAdminDateFormat($adminDateFormat);
    }

    public function getDateTimeDisplayFormat(
        int $dateType = DateTimeFormatter::DATE_TYPE_SHORT,
        int $timeType = DateTimeFormatter::TIME_TYPE_SHORT,
        ?string $locale = null
    ): string {
        $pattern = $this->getDateTimeFormatter()->getPattern($dateType, $timeType, $locale);
        return $this->convertIcuPatternToDateTime($pattern);
    }

    /**
     * @param DateTime|null $date null leads to empty string result
     * @param int $accountId
     * @return string
     */
    public function convertDateToAdminFormat(?DateTime $date, int $accountId): string
    {
        return $date ? $date->format($this->getDateDisplayFormat($accountId)) : '';
    }

    /**
     * @param DateTime|null $date null leads to empty string result
     * @param int|null $timezoneId
     * @return string
     */
    public function convertDateTimeToAdminFormat(?DateTime $date, ?int $timezoneId): string
    {
        $datetime = $this->convertUtcToTzById($date, $timezoneId);
        return $datetime ? $datetime->format($this->getDateTimeDisplayFormat()) : '';
    }

    /**
     * @param string $pattern
     * @return string
     */
    public function convertIcuPatternToDateTime(string $pattern): string
    {
        if (!isset($this->cachedConvertedFormats['QDateTimePicker'][$pattern])) {
            $this->cachedConvertedFormats['QDateTimePicker'][$pattern] = $this->createDateTimeFormatConverter()->convertDateIcuToDateTime($pattern);
        }
        return $this->cachedConvertedFormats['QDateTimePicker'][$pattern];
    }

    /**
     * @param string $pattern
     * @return string
     */
    public function convertIcuPatternToMomentJs(string $pattern): string
    {
        if (!isset($this->cachedConvertedFormats['moment'][$pattern])) {
            $this->cachedConvertedFormats['moment'][$pattern] = $this->createDateTimeFormatConverter()->convertDateIcuToMoment($pattern);
        }
        return $this->cachedConvertedFormats['moment'][$pattern];
    }

    /**
     * @param DateTimeInterface $dateTo
     * @return array
     */
    public function splitDifferenceWithCurrentDate(DateTimeInterface $dateTo): array
    {
        $dateFrom = $this->detectCurrentDateUtc();
        return $this->splitDifference($dateFrom, $dateTo);
    }

    /**
     * @param DateTimeInterface $dateFrom
     * @param DateTimeInterface $dateTo
     * @return array
     */
    public function splitDifference(DateTimeInterface $dateFrom, DateTimeInterface $dateTo): array
    {
        $interval = $dateTo->diff($dateFrom);
        return [$interval->days, $interval->h, $interval->i, $interval->s];
    }

    /**
     * @param DateTime|DateTimeImmutable|null $dateTime null leads to null result
     * @return DateTime|DateTimeImmutable|null
     */
    public function dropSeconds(DateTime|DateTimeImmutable|null $dateTime): DateTime|DateTimeImmutable|null
    {
        if ($dateTime === null) {
            return null;
        }
        $seconds = $dateTime->format('s');
        if (!$seconds) {
            return $dateTime;
        }
        return (clone $dateTime)->sub(new DateInterval("P{$seconds}S"));
    }
}
