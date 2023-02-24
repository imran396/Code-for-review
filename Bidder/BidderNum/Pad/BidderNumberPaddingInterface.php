<?php
/**
 * SAM-8662: Adjustments for Bidder Number Padding and Adviser services and apply unit tests
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\BidderNum\Pad;

interface BidderNumberPaddingInterface
{
    /**
     * Add bidder number padding
     * @param int|string|null $bidderNum Bidder number as int or string
     * @return string padded string
     */
    public function add(int|string|null $bidderNum): string;

    /**
     * Remove padding characters from padded bidder number string
     * @param int|string|null $bidderNumPad padded bidder number
     * @return string cleaned bidder number string
     */
    public function clear(int|string|null $bidderNumPad): string;

    /**
     * Remove padding characters from padded bidder number string for under-bidder report
     * @param string $bidderNumPad padded bidder number
     * @return string cleaned bidder number string
     */
    public function clearForUnderBidder(string $bidderNumPad): string;

    /**
     * Check if bidder# is not appropriate for approval - i.e. empty or zero.
     * @param string $bidderNum
     * @return bool
     */
    public function isNone(string $bidderNum): bool;

    /**
     * Check if bidder# is filled, so we can assign it.
     * @param string|null $bidderNum
     * @return bool
     */
    public function isFilled(?string $bidderNum): bool;

    /**
     * Check if bidder# is filled and has correct format, so it is proper for assignment.
     * @param string $bidderNum
     * @return bool
     */
    public function isFilledNumeric(string $bidderNum): bool;

    /**
     * Clean bidder# from forbidden non alpha-numeric and not ASCII characters.
     * @param string $bidderNum
     * @return string
     */
    public function sanitize(string $bidderNum): string;
}
