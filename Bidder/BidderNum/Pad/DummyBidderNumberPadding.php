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
use Sam\Installation\Config\Repository\DummyConfigRepository;

/**
 * Class Padding
 * @package Sam\Bidder\BidderNum\Pad
 */
class DummyBidderNumberPadding implements BidderNumberPaddingInterface
{
    public function add(int|string|null $bidderNum): string
    {
        return $this->createBidderNumberPadding()->add($bidderNum);
    }

    public function clear(int|string|null $bidderNumPad): string
    {
        return $this->createBidderNumberPadding()->clear($bidderNumPad);
    }

    public function clearForUnderBidder(string $bidderNumPad): string
    {
        return $this->createBidderNumberPadding()->clearForUnderBidder($bidderNumPad);
    }

    public function isNone(string $bidderNum): bool
    {
        return $this->createBidderNumberPadding()->isNone($bidderNum);
    }

    public function isFilled(?string $bidderNum): bool
    {
        return $this->createBidderNumberPadding()->isFilled($bidderNum);
    }

    public function isFilledNumeric(string $bidderNum): bool
    {
        return $this->createBidderNumberPadding()->isFilledNumeric($bidderNum);
    }

    public function sanitize(string $bidderNum): string
    {
        return $this->createBidderNumberPadding()->sanitize($bidderNum);
    }

    protected function createBidderNumberPadding(): BidderNumberPadding
    {
        $config = BidderNumberPaddingConfigProvider::new()->setConfigRepository(
            new DummyConfigRepository(
                [
                    'core->user->bidderNumber->padLength' => 4,
                    'core->user->bidderNumber->padString' => Constants\Bidder::PCH_ZERO,
                ]
            )
        );
        return BidderNumberPadding::new()->setBidderNumberPaddingConfigProvider($config);
    }
}
