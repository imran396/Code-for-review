<?php
/**
 * SAM-6758: Rtb console output builders
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Console\Responsive\Internal\Load;

use Sam\AuctionLot\Agreement\ChangesAgreement;
use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderInfo\BidderInfoRenderer;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Bidder\Outstanding\BidderOutstandingHelper;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Lot\BuyerGroup\Load\BuyerGroupUserLoaderCreateTrait;
use Sam\Lot\Count\LotCounterAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Class DataProvider
 * @package Sam\Rtb\Console\Responsive\Internal
 */
class DataProvider extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionBidderHelperAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use BidderNumPaddingAwareTrait;
    use CurrencyLoaderAwareTrait;
    use EditorUserAwareTrait;
    use BuyerGroupUserLoaderCreateTrait;
    use LotCounterAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param int|null $editorUserId null for anonymous visitor
     * @return $this
     */
    public function construct(int $auctionId, ?int $editorUserId): static
    {
        $this->setAuctionId($auctionId);
        $this->setEditorUserId($editorUserId);
        return $this;
    }

    /**
     * @return int
     */
    public function countLots(): int
    {
        return $this->getLotCounter()
            ->setAuction($this->getAuction())
            ->count();
    }

    /**
     * Return exchange rate for auction currency
     * @return float
     */
    public function detectExchangeRate(): float
    {
        $currencyId = $this->getAuction()->Currency;
        $currency = $this->getCurrencyLoader()->load($currencyId, true);
        $exRate = $currency->ExRate ?? 0.;
        return $exRate;
    }

    /**
     * Return the array of buyer_group.id where the user was assigned.
     * Return empty array for anonymous user.
     * @return array
     */
    public function loadBuyerGroupIds(): array
    {
        return $this->createBuyerGroupUserLoader()->loadBuyerGroupIdsForUser($this->getEditorUserId(), true);
    }

    /**
     * @return array
     */
    public function loadChangesAgreementAcceptedAuctionLotIds(): array
    {
        return ChangesAgreement::new()->loadAcceptedLotItemIds($this->getEditorUserId(), $this->getAuctionId());
    }

    /**
     * Return bidder info
     * @return string
     */
    public function detectBidderInfo(): string
    {
        $bidderInfo = BidderInfoRenderer::new()->renderForPublicRtb($this->getEditorUserId(), $this->getAuctionId());
        return $bidderInfo;
    }

    /**
     * @return bool
     */
    public function isOutstandingLimitExceed(): bool
    {
        $auctionBidder = $this->getAuctionBidderLoader()->load($this->getEditorUserId(), $this->getAuctionId(), true);
        $isOutstandingExceed = $auctionBidder
            && BidderOutstandingHelper::new()->isLimitExceeded($auctionBidder);
        return $isOutstandingExceed;
    }
}
