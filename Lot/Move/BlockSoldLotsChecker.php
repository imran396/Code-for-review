<?php
/**
 * Check if passed lots (don't) meet requirement 'Block sold lots from being assigned to other sales'
 * in other words if passed lots are not sold/received
 *
 * SAM-4120: Extract Block Sold Lots checking
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           05 Dec, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Move;

use Auction;
use RuntimeException;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\AdminLotEditUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class BlockSoldLotsChecker
 * @package Sam\Lot\Move
 */
class BlockSoldLotsChecker extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotItemReadRepositoryCreateTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use CurrentDateTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * @var int[]
     */
    protected array $lotItemIds = [];
    /**
     * @var int[]
     */
    protected array $soldLotItemIds = [];
    protected ?int $sourceAuctionId = null;
    protected ?Auction $sourceAuction = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return bool
     */
    public function check(): bool
    {
        $rows = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb(true)
            ->filterLotItemId($this->lotItemIds)
            ->skipAuctionId($this->sourceAuctionId)
            ->filterLotStatusId(Constants\Lot::$wonLotStatuses)
            ->select(['ali.lot_item_id', 'ali.auction_id'])
            ->loadRows();
        foreach ($rows as $row) {
            $this->soldLotItemIds[(int)$row['lot_item_id']] = (int)$row['auction_id'];
        }
        $isSuccess = !count($this->soldLotItemIds);
        return $isSuccess;
    }

    /**
     * @return string
     */
    public function produceErrorMessageForWebAdmin(): string
    {
        $messages = [];
        foreach ($this->soldLotItemIds as $lotItemId => $auctionId) {
            $lotItemId = (int)$lotItemId;
            $sourceAuctionLot = $this->getAuctionLotLoader()->load($lotItemId, $this->sourceAuctionId);
            $lotNo = $this->getLotRenderer()->renderLotNo($sourceAuctionLot);
            $lotItem = $this->getLotItemLoader()->load($lotItemId, true);
            $itemNo = $this->getLotRenderer()->renderItemNo($lotItem);
            $lotEditUrl = $this->getUrlBuilder()->build(
                AdminLotEditUrlConfig::new()->forWeb($lotItemId, $auctionId)
            );
            $lotEditLink = "<a href=\"{$lotEditUrl}\">Lot# {$lotNo} (item# {$itemNo})</a>";
            $lotListUrl = $this->getUrlBuilder()->build(
                AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_LOT_LIST, $auctionId)
            );
            $auction = $this->getAuctionLoader()->load($auctionId);
            if (!$auction) {
                log_error("Available Auction not found, when producing error messages" . composeSuffix(['aid' => $auctionId]));
                continue;
            }
            $saleNo = $this->getAuctionRenderer()->renderSaleNo($auction);
            $lotListLink = "<a href=\"{$lotListUrl}\">{$saleNo}</a>";
            $messages[] = "{$lotEditLink} is already marked as sold in sale {$lotListLink}."
                . " Please mark it as unsold/active first.<br />";
        }
        $message = implode("<br />", $messages);
        return $message;
    }

    /**
     * @param int[] $lotItemIds
     * @return static
     */
    public function setLotItemIds(array $lotItemIds): static
    {
        $this->lotItemIds = $lotItemIds;
        return $this;
    }

    /**
     * @param int $auctionId
     * @return static
     */
    public function setSourceAuctionId(int $auctionId): static
    {
        $this->sourceAuctionId = $auctionId;
        return $this;
    }

    /**
     * @param Auction $auction
     * @return static
     */
    public function setSourceAuction(Auction $auction): static
    {
        $this->sourceAuction = $auction;
        $this->setSourceAuctionId($auction->Id);
        return $this;
    }

    /**
     * @return Auction
     */
    protected function getSourceAuction(): Auction
    {
        if ($this->sourceAuction === null) {
            $this->sourceAuction = $this->getAuctionLoader()->load($this->sourceAuctionId);
        }
        if (!$this->sourceAuction) {
            throw new RuntimeException("Source auction not found" . composeSuffix(['a' => $this->sourceAuctionId]));
        }
        return $this->sourceAuction;
    }
}
