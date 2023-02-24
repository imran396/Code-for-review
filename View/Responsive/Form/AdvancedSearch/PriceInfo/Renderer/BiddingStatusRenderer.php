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

namespace Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Renderer;

use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidding\ReservePrice\LotReservePriceChecker;
use Sam\Bidding\ReservePrice\LotReservePriceCheckerCreateTrait;
use Sam\Core\Constants\Responsive\AdvancedSearchConstants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslator;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslatorAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Load\AdvancedSearchLotDto;

/**
 * Class BiddingStatusRenderer
 */
class BiddingStatusRenderer extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use CachedTranslatorAwareTrait;
    use EditorUserAwareTrait;
    use LotReservePriceCheckerCreateTrait;

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
     * @return string
     */
    public function render(AdvancedSearchLotDto $dto): string
    {
        $auctionLotId = $dto->auctionLotId;
        if (!$auctionLotId) {
            log_error('Auction lot id undefined, when rendering bidding status' . composeSuffix(((array)$dto)));
            return '';
        }

        $controlId = sprintf(AdvancedSearchConstants::CID_LBL_BIDDING_STATUS_TPL, $auctionLotId);
        $output = '<span id="' . $controlId . '" class="winning"></span>';
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();

        if (
            $dto->currentBid
            && $dto->currentBidderId
            && (
                $dto->isNotifyAbsenteeBidders
                || $auctionStatusPureChecker->isTimed($dto->auctionType)
            )
        ) {
            $currentBidderId = $dto->currentBidderId;
            if ($this->equalEditorUserId($currentBidderId)) {
                $bidStatusMsg = $this->getBidStatusMessage($dto);
                $output = "<span id=\"{$controlId}\" class=\"youre-winning\">{$bidStatusMsg}</span>";
            } elseif (Floating::gt($dto->maxBid, 0.)) {
                $langOutbid = $this->getCachedTranslator()->translate('GENERAL_OUTBID', 'general');
                $output = "<span id=\"{$controlId}\" class=\"outbid\">{$langOutbid}</span>";
            }
        }
        return $output;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @return string
     */
    protected function getBidStatusMessage(AdvancedSearchLotDto $dto): string
    {
        $auctionId = $dto->auctionId;
        $lotItemId = $dto->lotItemId;
        $auctionType = $dto->auctionType;
        $auction = $this->getAuctionLoader()->load($auctionId);
        $reserveCheckResult = $this->createLotReservePriceChecker()
            ->setLotItemId($lotItemId)
            ->setAuction($auction)
            ->check();
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isTimed($auctionType)) {
            $bidStatusMsg = $this->getBidStatusMessageForTimed($reserveCheckResult, $dto->isAuctionReverse);
        } else {
            $bidStatusMsg = $this->getBidStatusMessageForLiveAndHybrid(
                $dto->isReserveNotMetNotice,
                $reserveCheckResult,
                $dto->isAuctionReverse
            );
        }
        return $bidStatusMsg;
    }

    /**
     * @param int $reserveCheckResult
     * @param bool $isAuctionReverse
     * @return string
     */
    protected function getBidStatusMessageForTimed(int $reserveCheckResult, bool $isAuctionReverse): string
    {
        if ($reserveCheckResult === LotReservePriceChecker::NOT_MET) {
            $bidStatusMsg = $this->getCachedTranslator()
                ->translateByAuctionReverse(
                    'GENERAL_YOUR_HIGH_BID_BELOW_RESERVE',
                    'general',
                    $isAuctionReverse
                );
        } else {
            $bidStatusMsg = $this->getCachedTranslator()
                ->translateByAuctionReverse(
                    'GENERAL_YOUR_HIGH_BID',
                    'general',
                    $isAuctionReverse
                );
        }
        return $bidStatusMsg;
    }

    /**
     * @param bool $reserveNotMetNotice
     * @param int $reserveCheckResult
     * @param bool $isAuctionReverse
     * @return string
     */
    protected function getBidStatusMessageForLiveAndHybrid(
        bool $reserveNotMetNotice,
        int $reserveCheckResult,
        bool $isAuctionReverse
    ): string {
        if (
            $reserveNotMetNotice
            && $reserveCheckResult === LotReservePriceChecker::NOT_MET
        ) {
            $bidStatusMsg = $this->getCachedTranslator()
                ->translateByAuctionReverse(
                    'GENERAL_YOUR_HIGH_BID_BELOW_RESERVE',
                    'general',
                    $isAuctionReverse
                );
        } else {
            $bidStatusMsg = $this->getCachedTranslator()
                ->translateByAuctionReverse(
                    'GENERAL_YOUR_HIGH_BID',
                    'general',
                    $isAuctionReverse
                );
        }
        return $bidStatusMsg;
    }
}
