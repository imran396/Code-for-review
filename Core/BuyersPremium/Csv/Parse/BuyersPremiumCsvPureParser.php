<?php
/**
 * SAM-8107: Issues related to Validation and Values of Buyer's Premium
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\BuyersPremium\Csv\Parse;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class BuyersPremiumCsvPureParser
 * @package Sam\Core\BuyersPremium\Csv\Parse
 */
class BuyersPremiumCsvPureParser extends CustomizableClass
{
    public const OP_CLEAR_VALUE_MARKER = 'clearValueMarker';
    public const OP_DEFAULT_VALUE_MARKER = 'defaultValueMarker';
    public const OP_RANGES_DELIMITER = 'rangesDelimiter';
    public const OP_AMOUNT_DELIMITER = 'amountDelimiter';
    public const OP_SET_DELIMITER = 'setDelimiter';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Parse data from 'bpSetting' column
     * Ex: '2-0->|1000:5-2->|5000:0-7->'
     * @param string $input
     * @param array $optionals = [
     *      self::OP_CLEAR_VALUE_MARKER => string,
     *      self::OP_DEFAULT_VALUE_MARKER => string,
     *      self::OP_RANGES_DELIMITER => string,
     *      self::OP_AMOUNT_DELIMITER => string,
     *      self::OP_SET_DELIMITER => string,
     * ]
     * @return array Array of pairs [Amount, Fixed, Percent, Mode]
     */
    public function parse(string $input, array $optionals = []): array
    {
        $clearValueMarker = $optionals[self::OP_CLEAR_VALUE_MARKER] ?? Constants\BuyersPremium::CLEAR_VALUE_MARKER;
        $defaultValueMarker = $optionals[self::OP_DEFAULT_VALUE_MARKER] ?? Constants\BuyersPremium::DEFAULT_VALUE_MARKER;
        $rangesDelimiter = $optionals[self::OP_RANGES_DELIMITER] ?? Constants\BuyersPremium::RANGES_DELIMITER;
        $amountDelimiter = $optionals[self::OP_AMOUNT_DELIMITER] ?? Constants\BuyersPremium::AMOUNT_DELIMITER;
        $setDelimiter = $optionals[self::OP_SET_DELIMITER] ?? Constants\BuyersPremium::SET_DELIMITER;

        if (!$input) {
            return [];
        }

        if (in_array($input, [$clearValueMarker, $defaultValueMarker], true)) {
            return [$input];
        }

        $result = [];
        $ranges = explode($rangesDelimiter, $input);
        foreach ($ranges as $key => $pair) {
            $pair = explode($amountDelimiter, $pair);
            if (
                $key === 0
                && count($pair) === 1
            ) {
                $amount = '0';
                $set = $pair[0];
            } else {
                $amount = $pair[0];
                $set = $pair[1];
            }
            $result[] = array_merge([$amount], $this->parseSet($set, $setDelimiter));
        }
        return $result;
    }

    /**
     * Validates syntax of CSV formatted input.
     * @param string $input
     * @param array $optionals = [
     *      self::OP_CLEAR_VALUE_MARKER => string,
     *      self::OP_DEFAULT_VALUE_MARKER => string,
     *      self::OP_RANGES_DELIMITER => string,
     * ]
     * @return bool
     */
    public function validate(string $input, array $optionals = []): bool
    {
        $clearValueMarker = $optionals[self::OP_CLEAR_VALUE_MARKER] ?? Constants\BuyersPremium::CLEAR_VALUE_MARKER;
        $defaultValueMarker = $optionals[self::OP_DEFAULT_VALUE_MARKER] ?? Constants\BuyersPremium::DEFAULT_VALUE_MARKER;
        $rangesDelimiter = $optionals[self::OP_RANGES_DELIMITER] ?? Constants\BuyersPremium::RANGES_DELIMITER;
        $amountDelimiter = $optionals[self::OP_AMOUNT_DELIMITER] ?? Constants\BuyersPremium::AMOUNT_DELIMITER;
        $setDelimiter = $optionals[self::OP_SET_DELIMITER] ?? Constants\BuyersPremium::SET_DELIMITER;

        if (!$input) {
            return true;
        }

        if (in_array($input, [$clearValueMarker, $defaultValueMarker], true)) {
            return true;
        }

        $amountDelimiter = preg_quote($amountDelimiter, '/');
        $setDelimiter = preg_quote($setDelimiter, '/');
        $regExp = "/^([a-z0-9,.]*{$amountDelimiter})?[a-z0-9,.]*{$setDelimiter}[a-z0-9,.]*{$setDelimiter}[>+]{1}$/i";
        $ranges = explode($rangesDelimiter, $input);
        foreach ($ranges as $pair) {
            if (!preg_match($regExp, $pair)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $optionals = [
     *      self::OP_AMOUNT_DELIMITER => string,
     *      self::OP_SET_DELIMITER => string,
     * ]
     * @return string
     */
    public function hintPattern(array $optionals = []): string
    {
        $amountDelimiter = $optionals[self::OP_AMOUNT_DELIMITER] ?? Constants\BuyersPremium::AMOUNT_DELIMITER;
        $setDelimiter = $optionals[self::OP_SET_DELIMITER] ?? Constants\BuyersPremium::SET_DELIMITER;
        return "start{$amountDelimiter}fixed{$setDelimiter}percent{$setDelimiter}mode";
    }

    /**
     * @param string $setString
     * @param string $setDelimiter
     * @return array
     */
    protected function parseSet(string $setString, string $setDelimiter): array
    {
        $set = $setDelimiter ? explode($setDelimiter, $setString) : [];
        $fixed = $set[0] ?? 0;
        $percent = $set[1] ?? 0;
        $mode = isset($set[2], Constants\BuyersPremium::$rangeModeNames[$set[2]])
            ? Constants\BuyersPremium::$rangeModeNames[$set[2]]
            : Constants\BuyersPremium::MODE_NAME_GREATER;
        return [$fixed, $percent, $mode];
    }
}
