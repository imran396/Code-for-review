<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 14, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\PriceInfo;

use DateTime;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Exception;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Load\AdvancedSearchLotDto;

/**
 * Class LotEndChecker
 */
class LotEndChecker extends CustomizableClass
{
    use CurrentDateTrait;
    use DateHelperAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @return bool
     * @throws Exception
     */
    public function isEnded(AdvancedSearchLotDto $dto): bool
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isTimed($dto->auctionType)) {
            return $this->isEndForTimed($dto);
        }
        // Live / Hybrid
        return $this->isEndForLiveHybrid($dto);
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @return bool
     * @throws Exception
     */
    protected function isEndForTimed(AdvancedSearchLotDto $dto): bool
    {
        $lotStatus = $dto->lotStatusId;
        $startDate = new DateTime($dto->lotStartDateIso);
        $endDate = new DateTime($dto->lotEndDateIso);
        if (($this->getCurrentDateUtc()->getTimestamp() < $startDate->getTimestamp())) {
            return false;
        }
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        if (
            $auctionLotStatusPureChecker->isActive($lotStatus)
            && $this->getCurrentDateUtc()->getTimestamp() < $endDate->getTimestamp()
        ) {
            return false;
        }
        return true;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @return bool
     * @throws Exception
     */
    protected function isEndForLiveHybrid(AdvancedSearchLotDto $dto): bool
    {
        $auctionStatus = $dto->auctionStatusId;
        try {
            $startDate = new DateTime($dto->auctionStartClosingDateIso);
            $auctionStartDateTs = $startDate->getTimestamp();
        } catch (Exception) {
            $auctionStartDateTs = 0;
        }
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if (
            $auctionStatusPureChecker->isActive($auctionStatus)
            && $this->getCurrentDateUtc()->getTimestamp() < $auctionStartDateTs
        ) {
            return false;
        }
        if ($auctionStatusPureChecker->isStartedOrPaused($auctionStatus)) {
            return false;
        }
        return true;
    }

}
