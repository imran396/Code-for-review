<?php
/**
 * SAM-4450: Apply Bidder Terms Agreement Manager
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/17/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\BidderTerms;

use AuctionLotItemBidderTerms;
use DateTime;
use Sam\Bidder\BidderTerms\Load\AuctionLotItemBidderTermsLoaderCreateTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\DeleteRepository\Entity\AuctionLotItemBidderTerms\AuctionLotItemBidderTermsDeleteRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItemBidderTerms\AuctionLotItemBidderTermsWriteRepositoryAwareTrait;

/**
 * Class BidderTermsAgreementManager
 * @package Sam\Bidder\BidderTerms
 */
class BidderTermsAgreementManager extends CustomizableClass
{
    use AuctionLotItemBidderTermsLoaderCreateTrait;
    use AuctionLotItemBidderTermsDeleteRepositoryCreateTrait;
    use AuctionLotItemBidderTermsWriteRepositoryAwareTrait;
    use EntityFactoryCreateTrait;

    /**
     * Get instance of BidderTerms_Manager
     *
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Reset existing agreements for lot in `auction_lot_item_bidder_terms` for all users
     *
     * @param int $lotItemId
     * @param int $auctionId
     */
    public function resetForLot(int $lotItemId, int $auctionId): void
    {
        $this->createAuctionLotItemBidderTermsDeleteRepository()
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->delete();
    }

    /**
     * Add new agreement in `auction_lot_item_bidder_terms`
     *
     * @param int $userId
     * @param int $lotItemId Lot Id
     * @param int $auctionId
     * @param int $editorUserId
     */
    public function add(int $userId, int $lotItemId, int $auctionId, int $editorUserId): void
    {
        $agreement = $this->createEntityFactory()->auctionLotItemBidderTerms();
        $ts = (int)date("U");
        $agreement->AgreedOn = (new DateTime())->setTimestamp($ts);
        $agreement->AuctionId = $auctionId;
        $agreement->LotItemId = $lotItemId;
        $agreement->UserId = $userId;
        $this->getAuctionLotItemBidderTermsWriteRepository()->saveWithModifier($agreement, $editorUserId);
    }

    /**
     * Get current agreement from auction_lot_bidder_terms
     *
     * @param int|null $userId for
     * @param int|null $lotItemId Lot Id
     * @param int|null $auctionId
     * @return AuctionLotItemBidderTerms
     */
    public function load(?int $userId, ?int $lotItemId, ?int $auctionId): ?AuctionLotItemBidderTerms
    {
        if (
            !$userId
            || !$lotItemId
            || !$auctionId
        ) {
            return null;
        }

        return $this->createAuctionLotItemBidderTermsLoader()->load($auctionId, $lotItemId, $userId);
    }

    /**
     * Get current agreement from auction_lot_bidder_terms
     *
     * @param int|null $userId for
     * @param int|null $lotItemId Lot Id
     * @param int|null $auctionId
     * @return bool
     */
    public function has(?int $userId, ?int $lotItemId, ?int $auctionId): bool
    {
        if (
            !$userId
            || !$lotItemId
            || !$auctionId
        ) {
            return false;
        }

        $agreement = $this->createAuctionLotItemBidderTermsLoader()->load($auctionId, $lotItemId, $userId);
        $has = $agreement instanceof AuctionLotItemBidderTerms;
        return $has;
    }
}
