<?php
/**
 * Bidder# padding related functions
 *
 * SAM-8662: Adjustments for Bidder Number Padding and Adviser services and apply unit tests
 *
 * @author        Igors Kotlevskis
 * @since         Sep 14, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidder\BidderNum\Pad;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;

/**
 * Class Padding
 * @package Sam\Bidder\BidderNum\Pad
 */
class BidderNumberPadding extends CustomizableClass implements BidderNumberPaddingInterface
{
    use BidderNumberPaddingConfigProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Add bidder number padding
     * @param int|string|null $bidderNum Bidder number as int or string
     * @return string padded string
     */
    public function add(int|string|null $bidderNum): string
    {
        $bidderNumberPaddingProvider = $this->createBidderNumberPaddingConfigProvider();
        $padLength = $bidderNumberPaddingProvider->getLength();
        $padString = $bidderNumberPaddingProvider->getChar();
        if ($padString === Constants\Bidder::PCH_NONE) {
            return (string)$bidderNum;
        }
        $bidderNumPadded = str_pad((string)$bidderNum, $padLength, $padString, STR_PAD_LEFT);
        return $bidderNumPadded;
    }

    /**
     * Remove padding characters from padded bidder number string
     * @param int|string|null $bidderNumPad padded bidder number
     * @return string cleaned bidder number string
     */
    public function clear(int|string|null $bidderNumPad): string
    {
        if ((string)$bidderNumPad === '') {
            return '';
        }
        $padString = $this->createBidderNumberPaddingConfigProvider()->getChar();
        $clean = ltrim($bidderNumPad, $padString);
        return $clean;
    }

    /**
     * Remove padding characters from padded bidder number string for under-bidder report
     * @param string $bidderNumPad padded bidder number
     * @return string cleaned bidder number string
     */
    public function clearForUnderBidder(string $bidderNumPad): string
    {
        if ($bidderNumPad === '') {
            return 'floor';
        }
        return $this->clear($bidderNumPad);
    }

    /**
     * Check if bidder# is not appropriate for approval - i.e. empty or zero.
     * @param string|null $bidderNum bidder# with padding or without.
     * @return bool
     */
    public function isNone(?string $bidderNum): bool
    {
        $clean = $this->clear($bidderNum);
        return in_array($clean, ['', '0'], true);
    }

    /**
     * Check if bidder# is filled, so we can assign it.
     * @param string|null $bidderNum
     * @return bool
     */
    public function isFilled(?string $bidderNum): bool
    {
        return !$this->isNone($bidderNum);
    }

    /**
     * Check if bidder# is filled and has numeric format.
     * @param string|null $bidderNum
     * @return bool
     */
    public function isFilledNumeric(?string $bidderNum): bool
    {
        if ($this->isNone($bidderNum)) {
            return false;
        }

        $clean = $this->clear($bidderNum);
        return NumberValidator::new()->isInt($clean);
    }

    /**
     * Clean bidder# from forbidden non alpha-numeric and not ASCII characters.
     * @param string $bidderNum
     * @return string
     */
    public function sanitize(string $bidderNum): string
    {
        return preg_replace('/[^a-zA-Z0-9]/', '', $bidderNum);
    }
}
