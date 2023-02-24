<?php
/**
 * SAM-5884: Admin side internationalization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Locale\FormatConverter;


use Sam\Core\Service\CustomizableClass;

/**
 * Class DateTimeFormatConverter
 * @package Sam\Locale\FormatConverter
 * @source https://github.com/nkovacs/yii2-datetimepicker/blob/master/FormatConverter.php
 */
class DateTimeFormatConverter extends CustomizableClass
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
     * Converts a date format pattern from ICU format to DateTime format.
     * Pattern constructs that are not supported by the DateTime format will be removed.
     *
     * @param string $pattern
     * @return string
     */
    public function convertDateIcuToDateTime(string $pattern): string
    {
        // http://userguide.icu-project.org/formatparse/datetime#TOC-Date-Time-Format-Syntax
        // https://www.php.net/manual/ru/datetime.format.php
        // escaped text
        $escaped = [];
        if (preg_match_all('/(?<!\')\'(.*?[^\'])\'(?!\')/', $pattern, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $match[1] = str_replace('\'\'', '\'', $match[1]);
                $escaped[$match[0]] = '[' . $match[1] . ']';
            }
        }

        return strtr(
            $pattern,
            array_merge(
                $escaped,
                [
                    'G' => '',      // era designator like (Anno Domini)
                    'Y' => '',  // 4digit year of "Week of Year"
                    'y' => 'Y',    // 4digit year e.g. 2014
                    'yyyy' => 'Y', // 4digit year e.g. 2014
                    'yy' => 'y',    // 2digit year number eg. 14
                    'u' => '',      // extended year e.g. 4601
                    'U' => '',      // cyclic year name, as in Chinese lunar calendar
                    'r' => '',      // related Gregorian year e.g. 1996
                    'Q' => '',      // number of quarter
                    'QQ' => '',     // number of quarter '02'
                    'QQQ' => '',    // quarter 'Q2'
                    'QQQQ' => '',   // quarter '2nd quarter'
                    'QQQQQ' => '',  // number of quarter '2'
                    'q' => '',      // number of Stand Alone quarter
                    'qq' => '',     // number of Stand Alone quarter '02'
                    'qqq' => '',    // Stand Alone quarter 'Q2'
                    'qqqq' => '',   // Stand Alone quarter '2nd quarter'
                    'qqqqq' => '',  // number of Stand Alone quarter '2'
                    'M' => 'm',    // Numeric representation of a month, without leading zeros
                    'MM' => 'm',   // Numeric representation of a month, with leading zeros
                    'MMM' => 'b',   // A short textual representation of a month, three letters
                    'MMMM' => 'B', // A full textual representation of a month, such as January or March
                    'MMMMM' => '',  // First letter of month
                    'L' => 'm',     // Stand alone month in year
                    'LL' => 'm',   // Stand alone month in year
                    'LLL' => 'm',   // Stand alone month in year
                    'LLLL' => 'm', // Stand alone month in year
                    'LLLLL' => '',  // Stand alone month in year
                    'w' => '',      // ISO-8601 week number of year
                    'ww' => '',     // ISO-8601 week number of year
                    'W' => '',      // week of the current month
                    'd' => 'j',     // day without leading zeros
                    'dd' => 'd',   // day with leading zeros
                    'D' => '',     // day of the year 0 to 365
                    'F' => '',      // Day of Week in Month. eg. 2nd Wednesday in July
                    'g' => '',      // Modified Julian day. This is different from the conventional Julian day number in two regards.
                    'E' => '',     // day of week written in short form eg. Sun
                    'EE' => '',
                    'EEE' => '',
                    'EEEE' => '', // day of week fully written eg. Sunday
                    'EEEEE' => '',    // day of week, one letter
                    'EEEEEE' => '', //day of week, two letters, e.g. Su
                    'e' => '',      // ISO-8601 numeric representation of the day of the week 1=Mon to 7=Sun
                    'ee' => '',     // with leading zero, not supported by moment
                    'eee' => '', // day of week written in short form eg. Sun
                    'eeee' => '', // day of week fully written eg. Sunday
                    'eeeee' => '',   // day of week, one letter
                    'eeeeee' => '', //day of week, two letters, e.g. Su
                    'c' => '',      // ISO-8601 numeric representation of the day of the week 1=Mon to 7=Sun
                    'cc' => '',     // with leading zero, not supported by moment
                    'ccc' => '',
                    'cccc' => '',
                    'ccccc' => '',
                    'cccccc' => '',
                    'a' => 'A',      // am/pm marker
                    'h' => 'h',      // 12-hour format of an hour without leading zeros 1 to 12h
                    'hh' => 'h',     // 12-hour format of an hour with leading zeros, 01 to 12 h
                    'H' => 'H',      // 24-hour format of an hour without leading zeros 0 to 23h
                    'HH' => 'H',     // 24-hour format of an hour with leading zeros, 00 to 23 h
                    'k' => '',      // hour in day (1~24)
                    'kk' => '',     // hour in day (1~24)
                    'K' => '',      // hour in am/pm (0~11)
                    'KK' => '',     // hour in am/pm (0~11)
                    'm' => 'i',      // Minutes without leading zeros
                    'mm' => 'i',     // Minutes with leading zeros
                    's' => '',      // Seconds, without leading zeros
                    'ss' => '',     // Seconds, with leading zeros
                    'S' => '',      // fractional second
                    'SS' => '',     // fractional second
                    'SSS' => '',    // fractional second
                    'SSSS' => '',   // fractional second (only three digits in moment)
                    'A' => '',      // milliseconds in day
                    'z' => '',      // Timezone abbreviation
                    'zz' => '',     // Timezone abbreviation
                    'zzz' => '',    // Timezone abbreviation
                    'zzzz' => '',   // Timzone full name, not supported by php but we fallback
                    'Z' => '',      // Difference to Greenwich time (GMT) in hours
                    'ZZ' => '',     // Difference to Greenwich time (GMT) in hours
                    'ZZZ' => '',    // Difference to Greenwich time (GMT) in hours
                    'ZZZZ' => '',   // Time Zone: long localized GMT (=OOOO) e.g. GMT-08:00
                    'ZZZZZ' => '',  //  TIme Zone: ISO8601 extended hms? (=XXXXX)
                    'O' => '',      // Time Zone: short localized GMT e.g. GMT-8
                    'OOOO' => '',   //  Time Zone: long localized GMT (=ZZZZ) e.g. GMT-08:00
                    'v' => '',      // Time Zone: generic non-location (falls back first to VVVV and then to OOOO) using the ICU defined fallback here
                    'vvvv' => '',   // Time Zone: generic non-location (falls back first to VVVV and then to OOOO) using the ICU defined fallback here
                    'V' => '',      // Time Zone: short time zone ID
                    'VV' => '',     // Time Zone: long time zone ID
                    'VVV' => '',    // Time Zone: time zone exemplar city
                    'VVVV' => '',   // Time Zone: generic location (falls back to OOOO) using the ICU defined fallback here
                    'X' => '',      // Time Zone: ISO8601 basic hm?, with Z for 0, e.g. -08, +0530, Z
                    'XX' => '',     // Time Zone: ISO8601 basic hm, with Z, e.g. -0800, Z
                    'XXX' => '',    // Time Zone: ISO8601 extended hm, with Z, e.g. -08:00, Z
                    'XXXX' => '',   // Time Zone: ISO8601 basic hms?, with Z, e.g. -0800, -075258, Z
                    'XXXXX' => '',  // Time Zone: ISO8601 extended hms?, with Z, e.g. -08:00, -07:52:58, Z
                    'x' => '',      // Time Zone: ISO8601 basic hm?, without Z for 0, e.g. -08, +0530
                    'xx' => '',     // Time Zone: ISO8601 basic hm, without Z, e.g. -0800
                    'xxx' => '',    // Time Zone: ISO8601 extended hm, without Z, e.g. -08:00
                    'xxxx' => '',   // Time Zone: ISO8601 basic hms?, without Z, e.g. -0800, -075258
                    'xxxxx' => '',  // Time Zone: ISO8601 extended hms?, without Z, e.g. -08:00, -07:52:58
                ]
            )
        );
    }

    /**
     * Converts a date format pattern from ICU format to moment.js format.
     * Pattern constructs that are not supported by the moment.js format will be removed.
     *
     * @param string $pattern date format pattern in ICU format.
     * @return string
     */
    public function convertDateIcuToMoment(string $pattern): string
    {
        // http://userguide.icu-project.org/formatparse/datetime#TOC-Date-Time-Format-Syntax
        // escaped text
        $escaped = [];
        if (preg_match_all('/(?<!\')\'(.*?[^\'])\'(?!\')/', $pattern, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $match[1] = str_replace('\'\'', '\'', $match[1]);
                $escaped[$match[0]] = '[' . $match[1] . ']';
            }
        }

        return strtr(
            $pattern,
            array_merge(
                $escaped,
                [
                    'G' => '',      // era designator like (Anno Domini)
                    'Y' => 'GGGG',  // 4digit year of "Week of Year"
                    'y' => 'YYYY',    // 4digit year e.g. 2014
                    'yyyy' => 'YYYY', // 4digit year e.g. 2014
                    'yy' => 'YY',    // 2digit year number eg. 14
                    'u' => '',      // extended year e.g. 4601
                    'U' => '',      // cyclic year name, as in Chinese lunar calendar
                    'r' => '',      // related Gregorian year e.g. 1996
                    'Q' => 'Q',      // number of quarter
                    'QQ' => '',     // number of quarter '02'
                    'QQQ' => '',    // quarter 'Q2'
                    'QQQQ' => '',   // quarter '2nd quarter'
                    'QQQQQ' => '',  // number of quarter '2'
                    'q' => 'Q',      // number of Stand Alone quarter
                    'qq' => '',     // number of Stand Alone quarter '02'
                    'qqq' => '',    // Stand Alone quarter 'Q2'
                    'qqqq' => '',   // Stand Alone quarter '2nd quarter'
                    'qqqqq' => '',  // number of Stand Alone quarter '2'
                    'M' => 'M',    // Numeric representation of a month, without leading zeros
                    'MM' => 'MM',   // Numeric representation of a month, with leading zeros
                    'MMM' => 'MMM',   // A short textual representation of a month, three letters
                    'MMMM' => 'MMMM', // A full textual representation of a month, such as January or March
                    'MMMMM' => '',  // First letter of month
                    'L' => 'M',     // Stand alone month in year
                    'LL' => 'MM',   // Stand alone month in year
                    'LLL' => 'MMM',   // Stand alone month in year
                    'LLLL' => 'MMMM', // Stand alone month in year
                    'LLLLL' => '',  // Stand alone month in year
                    'w' => 'W',      // ISO-8601 week number of year
                    'ww' => 'WW',     // ISO-8601 week number of year
                    'W' => '',      // week of the current month
                    'd' => 'D',     // day without leading zeros
                    'dd' => 'DD',   // day with leading zeros
                    'D' => 'DDD',     // day of the year 0 to 365
                    'F' => '',      // Day of Week in Month. eg. 2nd Wednesday in July
                    'g' => '',      // Modified Julian day. This is different from the conventional Julian day number in two regards.
                    'E' => 'ddd',     // day of week written in short form eg. Sun
                    'EE' => 'ddd',
                    'EEE' => 'ddd',
                    'EEEE' => 'dddd', // day of week fully written eg. Sunday
                    'EEEEE' => '',    // day of week, one letter
                    'EEEEEE' => 'dd', //day of week, two letters, e.g. Su
                    'e' => 'E',      // ISO-8601 numeric representation of the day of the week 1=Mon to 7=Sun
                    'ee' => 'E',     // with leading zero, not supported by moment
                    'eee' => 'ddd', // day of week written in short form eg. Sun
                    'eeee' => 'dddd', // day of week fully written eg. Sunday
                    'eeeee' => '',   // day of week, one letter
                    'eeeeee' => 'dd', //day of week, two letters, e.g. Su
                    'c' => 'E',      // ISO-8601 numeric representation of the day of the week 1=Mon to 7=Sun
                    'cc' => 'E',     // with leading zero, not supported by moment
                    'ccc' => 'ddd',
                    'cccc' => 'dddd',
                    'ccccc' => '',
                    'cccccc' => 'dd',
                    'a' => 'A',      // am/pm marker
                    'h' => 'h',      // 12-hour format of an hour without leading zeros 1 to 12h
                    'hh' => 'hh',     // 12-hour format of an hour with leading zeros, 01 to 12 h
                    'H' => 'H',      // 24-hour format of an hour without leading zeros 0 to 23h
                    'HH' => 'HH',     // 24-hour format of an hour with leading zeros, 00 to 23 h
                    'k' => '',      // hour in day (1~24)
                    'kk' => '',     // hour in day (1~24)
                    'K' => '',      // hour in am/pm (0~11)
                    'KK' => '',     // hour in am/pm (0~11)
                    'm' => 'm',      // Minutes without leading zeros
                    'mm' => 'mm',     // Minutes with leading zeros
                    's' => 's',      // Seconds, without leading zeros
                    'ss' => 'ss',     // Seconds, with leading zeros
                    'S' => 'S',      // fractional second
                    'SS' => 'SS',     // fractional second
                    'SSS' => 'SSS',    // fractional second
                    'SSSS' => 'SSSS',   // fractional second (only three digits in moment)
                    'A' => '',      // milliseconds in day
                    'z' => '[GMT]Z',      // Timezone abbreviation
                    'zz' => '[GMT]Z',     // Timezone abbreviation
                    'zzz' => '[GMT]Z',    // Timezone abbreviation
                    'zzzz' => '',   // Timzone full name, not supported by php but we fallback
                    'Z' => 'ZZ',      // Difference to Greenwich time (GMT) in hours
                    'ZZ' => 'ZZ',     // Difference to Greenwich time (GMT) in hours
                    'ZZZ' => 'ZZ',    // Difference to Greenwich time (GMT) in hours
                    'ZZZZ' => '[GMT]Z',   // Time Zone: long localized GMT (=OOOO) e.g. GMT-08:00
                    'ZZZZZ' => '[GMT]Z',  //  TIme Zone: ISO8601 extended hms? (=XXXXX)
                    'O' => '',      // Time Zone: short localized GMT e.g. GMT-8
                    'OOOO' => '[GMT]Z',   //  Time Zone: long localized GMT (=ZZZZ) e.g. GMT-08:00
                    'v' => '',      // Time Zone: generic non-location (falls back first to VVVV and then to OOOO) using the ICU defined fallback here
                    'vvvv' => '',   // Time Zone: generic non-location (falls back first to VVVV and then to OOOO) using the ICU defined fallback here
                    'V' => '',      // Time Zone: short time zone ID
                    'VV' => '',     // Time Zone: long time zone ID
                    'VVV' => '',    // Time Zone: time zone exemplar city
                    'VVVV' => '',   // Time Zone: generic location (falls back to OOOO) using the ICU defined fallback here
                    'X' => '',      // Time Zone: ISO8601 basic hm?, with Z for 0, e.g. -08, +0530, Z
                    'XX' => '',     // Time Zone: ISO8601 basic hm, with Z, e.g. -0800, Z
                    'XXX' => '',    // Time Zone: ISO8601 extended hm, with Z, e.g. -08:00, Z
                    'XXXX' => '',   // Time Zone: ISO8601 basic hms?, with Z, e.g. -0800, -075258, Z
                    'XXXXX' => '',  // Time Zone: ISO8601 extended hms?, with Z, e.g. -08:00, -07:52:58, Z
                    'x' => '',      // Time Zone: ISO8601 basic hm?, without Z for 0, e.g. -08, +0530
                    'xx' => '',     // Time Zone: ISO8601 basic hm, without Z, e.g. -0800
                    'xxx' => '',    // Time Zone: ISO8601 extended hm, without Z, e.g. -08:00
                    'xxxx' => '',   // Time Zone: ISO8601 basic hms?, without Z, e.g. -0800, -075258
                    'xxxxx' => '',  // Time Zone: ISO8601 extended hms?, without Z, e.g. -08:00, -07:52:58
                ]
            )
        );
    }

    /**
     * Converts a date format pattern from php date() function format to Moment.js format.
     *
     * The conversion is limited to date patterns that do not use escaped characters.
     * Patterns like `jS \o\f F Y` which will result in a date like `1st of December 2014` may not be converted correctly
     * because of the use of escaped characters.
     *
     * Pattern constructs that are not supported by the Moment.js format will be removed.
     *
     * @param string $pattern date format pattern in php date()-function format.
     * @return string The converted date format pattern.
     */
    public static function convertDatePhpToMoment(string $pattern): string
    {
        // http://php.net/manual/en/function.date.php
        return strtr(
            $pattern,
            [
                // Day
                'd' => 'DD',
                // Day of the month, 2 digits with leading zeros    01 to 31
                'D' => 'ddd',
                // A textual representation of a day, three letters     Mon through Sun
                'j' => 'D',
                // Day of the month without leading zeros   1 to 31
                'l' => 'dddd',
                // A full textual representation of the day of the week     Sunday through Saturday
                'N' => 'E',
                // ISO-8601 numeric representation of the day of the week, 1 (for Monday) through 7 (for Sunday)
                'S' => 'Do',
                // English ordinal suffix for the day of the month, 2 characters    st, nd, rd or th. Works well with j
                'w' => 'e',
                // Numeric representation of the day of the week    0 (for Sunday) through 6 (for Saturday)
                'z' => '',
                // The day of the year (starting from 0)    0 through 365
                // Week
                'W' => 'WW',
                // ISO-8601 week number of year, weeks starting on Monday (added in PHP 4.1.0)  Example: 42 (the 42nd week in the year)
                // Month
                'F' => 'MMMM',
                // A full textual representation of a month, January through December
                'm' => 'MM',
                // Numeric representation of a month, with leading zeros    01 through 12
                'M' => 'MMM',
                // A short textual representation of a month, three letters     Jan through Dec
                'n' => 'M',
                // Numeric representation of a month, without leading zeros     1 through 12
                't' => '',
                // Number of days in the given month    28 through 31
                // Year
                'L' => '',
                // Whether it's a leap year, 1 if it is a leap year, 0 otherwise.
                'o' => 'GGGG',
                // ISO-8601 year number. This has the same value as Y, except that if the ISO week number (W) belongs to the previous or next year, that year is used instead.
                'Y' => 'YYYY',
                // A full numeric representation of a year, 4 digits    Examples: 1999 or 2003
                'y' => 'YY',
                // A two digit representation of a year     Examples: 99 or 03
                // Time
                'a' => 'a',
                // Lowercase Ante meridiem and Post meridiem, am or pm
                'A' => 'A',
                // Uppercase Ante meridiem and Post meridiem, AM or PM, not supported by ICU but we fallback to lowercase
                'B' => '',
                // Swatch Internet time     000 through 999
                'g' => 'h',
                // 12-hour format of an hour without leading zeros  1 through 12
                'G' => 'H',
                // 24-hour format of an hour without leading zeros 0 to 23h
                'h' => 'hh',
                // 12-hour format of an hour with leading zeros, 01 to 12 h
                'H' => 'HH',
                // 24-hour format of an hour with leading zeros, 00 to 23 h
                'i' => 'mm',
                // Minutes with leading zeros   00 to 59
                's' => 'ss',
                // Seconds, with leading zeros  00 through 59
                'u' => '',
                // Microseconds. Example: 654321
                // Timezone
                'e' => '',
                // Timezone identifier. Examples: UTC, GMT, Atlantic/Azores
                'I' => '',
                // Whether or not the date is in daylight saving time, 1 if Daylight Saving Time, 0 otherwise.
                'O' => 'ZZ',
                // Difference to Greenwich time (GMT) in hours, Example: +0200
                'P' => 'Z',
                // Difference to Greenwich time (GMT) with colon between hours and minutes, Example: +02:00
                'T' => '',
                // Timezone abbreviation, Examples: EST, MDT ...
                'Z' => '',
                // Timezone offset in seconds. The offset for timezones west of UTC is always negative, and for those east of UTC is always positive. -43200 through 50400
                // Full Date/Time
                'c' => 'YYYY-MM-DD[T]HH:mm:ssZ',
                // ISO 8601 date, e.g. 2004-02-12T15:19:21+00:00
                'r' => 'ddd, DD MMM YYYY HH:mm:ss ZZ',
                // RFC 2822 formatted date, Example: Thu, 21 Dec 2000 16:01:07 +0200
                'U' => 'X',
                // Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)
            ]
        );
    }
}
