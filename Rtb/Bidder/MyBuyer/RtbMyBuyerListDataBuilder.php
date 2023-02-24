<?php
/**
 * SAM-5751: Rtb my buyers list builder
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Bidder\MyBuyer;

use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\Outstanding\BidderOutstandingHelper as OutstandingHelper;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;

/**
 * This class is responsible for building list of rtb agent buyers
 *
 * Class RtbMyBuyerListDataBuilder
 * @package Sam\Rtb\Bidder\MyBuyer
 */
class RtbMyBuyerListDataBuilder extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use UserAwareTrait;
    use UserReadRepositoryCreateTrait;

    private ?OutstandingHelper $outstandingHelper = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $agentUserId
     * @param int|null $auctionId
     * @return $this
     */
    public function construct(?int $agentUserId, ?int $auctionId): RtbMyBuyerListDataBuilder
    {
        $this->setUserId($agentUserId);
        $this->setAuctionId($auctionId);
        return $this;
    }

    /**
     * @return array
     */
    public function build(): array
    {
        $buyers = $this->loadAppropriateBuyerRows();
        array_unshift(
            $buyers,
            [
                'id' => $this->getUserId(),
                'username' => 'Myself'
            ]
        );

        $buyersIndexed = [];
        foreach ($buyers as $buyer) {
            $buyerUserId = $buyer['id'];
            $buyersIndexed[$buyerUserId] = $buyer['username'];
        }

        return [
            'Buyers' => $buyersIndexed
        ];
    }

    /**
     * SAM-2710, 3 Apr 2015, IK: Except bidders, who have exceeded outstanding limit.
     * It should be optimized to select buyers considering outstanding in one query.
     * It isn't implemented because "nobody uses the feature" as Nima said
     *
     * @return array
     */
    protected function loadAppropriateBuyerRows(): array
    {
        return array_filter($this->loadAllBuyerRows(), [$this, 'isAppropriateBuyer']);
    }

    /**
     * @param array $buyerRow
     * @return bool
     */
    protected function isAppropriateBuyer(array $buyerRow): bool
    {
        $auctionBidder = $this->getAuctionBidderLoader()->load((int)$buyerRow['id'], $this->getAuctionId(), true);
        // We render either not bidders of auction, or bidders with correct outstanding value
        $is = !$auctionBidder
            || !$this->getOutstandingHelper()->isLimitExceeded($auctionBidder);
        return $is;
    }

    /**
     * @return array
     */
    protected function loadAllBuyerRows(): array
    {
        $agentUserId = $this->getUserId();
        $select = ['u.id', 'username'];
        return $this->createUserReadRepository()
            ->filterCreatedBy($agentUserId)
            ->filterFlag(Constants\User::FLAG_NONE)
            ->filterUserStatusId(Constants\User::US_ACTIVE)
            ->innerJoinBidder()
            ->orderByUsername()
            ->select($select)
            ->skipId((array)$agentUserId)
            ->loadRows();
    }

    /**
     * @return OutstandingHelper
     */
    protected function getOutstandingHelper(): OutstandingHelper
    {
        if ($this->outstandingHelper === null) {
            $this->outstandingHelper = OutstandingHelper::new();
        }
        return $this->outstandingHelper;
    }
}
