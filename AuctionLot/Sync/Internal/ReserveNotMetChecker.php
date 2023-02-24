<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Internal;

use Sam\Bidding\ReservePrice\ReservePriceSimpleChecker;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * Class ReserveNotMetChecker
 * @package Sam\AuctionLot\Sync\Internal
 * @internal
 */
class ReserveNotMetChecker extends CustomizableClass
{
    use OptionalsTrait;

    public const OP_RESERVE_NOTICE_ENABLED = 'reserveNoticeEnabled';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals = [
     *      self::OP_RESERVE_NOTICE_ENABLED => (bool)
     *   ]
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Check if we should send 'rnm' data
     *
     * @param float|null $reservePrice
     * @param float|null $currentMaxBid
     * @param int $secondsLeft
     * @param bool $isReserveNotMet
     * @param bool $isReserveMet
     * @param bool $auctionReverse
     * @return int|null
     */
    public function detectReserveNotMet(
        ?float $reservePrice,
        ?float $currentMaxBid,
        int $secondsLeft,
        bool $isReserveNotMet,
        bool $isReserveMet,
        bool $auctionReverse
    ): ?int {
        $rnm = null;
        if (!$this->fetchOptional(self::OP_RESERVE_NOTICE_ENABLED)) {
            return null;
        }
        // reserve not met
        if (
            Floating::gt($reservePrice, 0)
            && Floating::gt($currentMaxBid, 0)
            && $secondsLeft
        ) {
            $rnm = (int)!ReservePriceSimpleChecker::new()->meet($currentMaxBid, $reservePrice, $auctionReverse);
        }

        if ($rnm === 1 && !$isReserveNotMet) {
            $rnm = null;
        }
        if ($rnm === 0 && !$isReserveMet) {
            $rnm = null;
        }
        return $rnm;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_RESERVE_NOTICE_ENABLED] = $optionals[self::OP_RESERVE_NOTICE_ENABLED]
            ?? static function () {
                return ConfigRepository::getInstance()->get('core->lot->reserveNotice->enabled');
            };
        $this->setOptionals($optionals);
    }
}
