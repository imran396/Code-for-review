<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\PriceInfo;

use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\User\Access\LotAccessCheckerAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Load\AdvancedSearchLotDto;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Renderer\BiddingHistoryLinkRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslator;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslatorAwareTrait;

/**
 * Class BiddingHistoryPriceInfoCreator
 */
class BiddingHistoryPriceInfoBuilder extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use BiddingHistoryLinkRendererCreateTrait;
    use CachedTranslatorAwareTrait;
    use EditorUserAwareTrait;
    use LotAccessCheckerAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(CachedTranslator $cachedTranslator): static
    {
        $this->setCachedTranslator($cachedTranslator);
        return $this;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @return array
     */
    public function buildBiddingHistoryLinkForLive(AdvancedSearchLotDto $dto): array
    {
        $biddingHistory = $this->buildBiddingHistoryLink($dto);
        return $biddingHistory;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @param int $auctionLotId
     * @return array
     */
    public function buildBiddingHistoryLinkForTimed(AdvancedSearchLotDto $dto, int $auctionLotId): array
    {
        $biddingHistory = $this->buildBiddingHistoryLink($dto);
        if ($biddingHistory) {
            $biddingHistoryAdditional = [
                'data-id' => $auctionLotId,
                'bid-count' => $dto->bidCount,
                'auction-type' => Constants\Auction::TIMED,
            ];
            $biddingHistory = array_merge($biddingHistory, $biddingHistoryAdditional);
        }
        return $biddingHistory;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @param int $auctionLotId
     * @return array
     */
    public function buildBiddingHistoryLinkForTimedCompact(AdvancedSearchLotDto $dto, int $auctionLotId): array
    {
        $biddingHistory = $this->buildBiddingHistoryLinkForTimed($dto, $auctionLotId);
        if ($biddingHistory) {
            $biddingHistory['title'] = null;
        }
        return $biddingHistory;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @return array
     */
    protected function buildBiddingHistoryLink(AdvancedSearchLotDto $dto): array
    {
        $biddingHistory = [];
        $auctionId = $dto->auctionId;
        $auction = $this->getAuctionLoader()->load($auctionId);
        if ($auction) {
            $accountId = $auction->AccountId;
            $access = $auction->LotBiddingHistoryAccess;
        } else {
            $accountId = $dto->lotAccountId;
            $access = $this->getSettingsManager()->get(Constants\Setting::LOT_BIDDING_HISTORY_ACCESS, $accountId);
        }
        $userId = $this->getEditorUserId();
        $hasAccess = $this->getLotAccessChecker()->isAccess($access, $userId, $accountId, $auctionId, $dto->consignorId);
        if (!$hasAccess) {
            return $biddingHistory;
        }
        $historyLink = $this->createBiddingHistoryLinkRenderer()
            ->construct($this->getCachedTranslator())
            ->render($dto);
        if ($historyLink) {
            $biddingHistory = [
                'title' => $this->getCachedTranslator()->translate('MYITEMS_BIDDINGHISTORY', 'myitems'),
                'value-type' => 'item-bidhistory',
                'value' => $historyLink,
            ];
        }
        return $biddingHistory;
    }
}
