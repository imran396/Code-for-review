<?php
/**
 * SAM-5636  : Refactoring of auction_closer.php - move piecemeal lot updating logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 05, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Closer\BulkGroup\PiecemealLot;

use Sam\Core\Service\CustomizableClass;
use Exception;
use Sam\Bidding\BidTransaction\Place\Base\AnyBidSaverCreateTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionLotAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class SinglePiecemealLotCurrentBidUpdater
 * @package Sam\AuctionLot\Closer\BulkGroup\PiecemealLot
 */
class SinglePiecemealLotCurrentBidUpdater extends CustomizableClass
{
    use AnyBidSaverCreateTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotAwareTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use UserAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * @var float|null
     */
    protected ?float $maxBid = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return float|null
     */
    public function getMaxBid(): ?float
    {
        return $this->maxBid;
    }

    /**
     * @param float|null $maxBid . null- when no maxbid is set. bid_transaction.max_bid for current bid - null results to null
     * @return static
     */
    public function setMaxBid(?float $maxBid): static
    {
        $this->maxBid = $maxBid;
        return $this;
    }

    /**
     * Update bid record for piecemeal
     * @param int $editorUserId
     * @return void
     */
    public function update(int $editorUserId): void
    {
        $auctionLot = $this->getAuctionLot();
        if (!$auctionLot) {
            log_error(
                'Auction lot not found, when normalizing current bid value to max bid'
                . composeSuffix(['ali' => $this->getAuctionLotId()])
            );
            return;
        }
        $logData = [
            'u' => $this->getUserId(),
            'li' => $auctionLot->LotItemId,
            'a' => $auctionLot->AuctionId,
            'ali' => $this->getAuctionLotId()
        ];
        $user = $this->getUserLoader()->load($this->getUserId());
        if (!$user) {
            log_error(
                'Available bidder user not found, when normalizing current bid value to max bid'
                . composeSuffix($logData)
            );
            return;
        }
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
        if (!$auction) {
            log_error(
                "Available auction not found, when normalizing current bid value to max bid"
                . composeSuffix($logData)
            );
            return;
        }
        $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId);
        if (!$lotItem) {
            log_error(
                "Available lot item not found, when normalizing current bid value to max bid"
                . composeSuffix($logData)
            );
            return;
        }
        $maxBid = $this->getMaxBid();

        try {
            $bidTransaction = $this->createAnyBidSaver()
                ->setAuction($auction)
                ->setBidAmount($maxBid)
                ->setEditorUserId($editorUserId)
                ->setLotItem($lotItem)
                ->setMaxBidAmount($maxBid)
                ->setUser($user)
                ->create();

            $auctionLot->linkCurrentBid($bidTransaction->Id);
            $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
        } catch (Exception) {
            log_error('Failed to add bid record for piecemeal' . composeSuffix($logData));
            return;
        }
    }
}
