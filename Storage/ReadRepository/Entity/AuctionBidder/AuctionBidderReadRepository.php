<?php
/**
 * General repository for AuctionBidder Parameters entity
 *
 * SAM-3680: Bidder and consignor related repositories https://bidpath.atlassian.net/browse/SAM-3680
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           17 Feb, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * Usage example:
 * $auctionBidderRepository = \Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterAuctionId($auctionIds);
 * $isFound = $auctionBidderRepository->exist();
 * $count = $auctionBidderRepository->count();
 * $item = $auctionBidderRepository->loadEntity();
 * $items = $auctionBidderRepository->loadEntities();
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionBidder;

use Sam\Bidder\AuctionBidder\Query\AuctionBidderQueryBuilderHelperCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserCustData\UserCustDataJoiningTrait;

/**
 * Class AuctionBidderReadRepository
 * @package Sam\Storage\ReadRepository\Entity\AuctionBidder
 */
class AuctionBidderReadRepository extends AbstractAuctionBidderReadRepository
{
    use AuctionBidderQueryBuilderHelperCreateTrait;
    use UserCustDataJoiningTrait;

    /**
     * Used by UserCustDataJoiningTrait
     */
    protected string $userCustDataJoinFieldName = 'user_id';

    protected array $joins = [
        'account' => 'JOIN account acc ON a.account_id = acc.id',
        'auction' => 'JOIN auction a ON aub.auction_id = a.id',
        'bidder' => 'JOIN bidder b ON aub.user_id = b.user_id',
        'user' => 'JOIN user u ON aub.user_id = u.id',
        'user_by_added_by' => 'JOIN user uaddedby ON u.added_by = uaddedby.id',
        'user_info' => 'JOIN user_info ui ON aub.user_id = ui.user_id',
        'user_billing' => 'JOIN user_billing ub ON aub.user_id = ub.user_id',
        'user_shipping' => 'JOIN user_shipping us ON aub.user_id = us.user_id',
        'rtb_session' => 'JOIN rtb_session rs ON aub.user_id = rs.user_id ',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Define filtering by approved aub
     * @param bool $approved
     * @return static
     */
    public function filterApproved(bool $approved): static
    {
        $queryBuilderHelper = $this->createAuctionBidderQueryBuilderHelper();
        $this->inlineCondition(
            $approved
                ? $queryBuilderHelper->makeApprovedBidderWhereClause()
                : $queryBuilderHelper->makeUnApprovedBidderWhereClause()
        );
        return $this;
    }

    /**
     * Define filtering by non-empty aub.bidder_num
     * @return static
     */
    public function filterBidderNumFilled(): static
    {
        $this->inlineCondition('(aub.bidder_num IS NOT null AND aub.bidder_num != \'\')');
        return $this;
    }

    /**
     * Filter absentee and bidding activity
     * Users with bid_transaction or absentee_bid records in this auction
     * @param bool $filterValue default true
     * @return static
     */
    public function filterSubqueryHasAnyBid(bool $filterValue): static
    {
        $subQuery = '(SELECT COUNT(1)>0 '
            . 'FROM bid_transaction bt '
            . 'WHERE NOT bt.deleted '
            . 'AND bt.auction_id = ' . $this->alias . '.auction_id '
            . 'AND bt.user_id = ' . $this->alias . '.user_id '
            . ') + ('
            . 'SELECT COUNT(1)>0 '
            . 'FROM absentee_bid ab '
            . 'WHERE ab.auction_id = ' . $this->alias . '.auction_id '
            . 'AND ab.user_id = ' . $this->alias . '.user_id'
            . ')';
        $this->filterSubquery($subQuery, $filterValue);
        return $this;
    }

    /**
     * Filter winning bidders
     * users with lot_item.winning_bidder in this auction
     *
     * @param bool $filterValue true
     * @return static
     */
    public function filterSubqueryHasWinningLot(bool $filterValue): static
    {
        $subQuery = '(SELECT COUNT(1)>0 '
            . 'FROM lot_item li '
            . 'WHERE li.active '
            . 'AND li.winning_bidder_id = ' . $this->alias . '.user_id '
            . 'AND li.auction_id = ' . $this->alias . '.auction_id)';
        $this->filterSubquery($subQuery, $filterValue);
        return $this;
    }

    /**
     * @return static
     */
    public function innerJoinBidder(): static
    {
        $this->innerJoin('bidder');
        return $this;
    }

    /**
     * Left join Account
     * @return static
     */
    public function joinAccount(): static
    {
        $this->joinAuction();
        $this->join('account');
        return $this;
    }

    /**
     * filtering by acc.active
     * @param bool|bool[]|null $active
     * @return static
     */
    public function joinAccountFilterActive(bool|array|null $active): static
    {
        $this->joinAccount();
        $this->filterArray('acc.active', $active);
        return $this;
    }

    /**
     * Join `auction` table
     * @return static
     */
    public function joinAuction(): static
    {
        $this->join('auction');
        return $this;
    }

    /**
     * Left join auction table and filter by account
     * Define filtering by a.account_id
     * @param int|int[] $accountIds
     * @return static
     */
    public function joinAuctionFilterAccountId(int|array|null $accountIds): static
    {
        $this->joinAuction();
        $this->filterArray('a.account_id', $accountIds);
        return $this;
    }

    /**
     * Left join auction table and filter by auction Id
     * Define filtering by a.id
     * @param int|int[] $auctionIds
     * @return static
     */
    public function joinAuctionFilterAuctionId(int|array|null $auctionIds): static
    {
        $this->joinAuction();
        $this->filterArray('a.id', $auctionIds);
        return $this;
    }

    /**
     * Left join auction table and filter by auction status
     * Define filtering by a.auction_status_id
     * @param int|int[] $auctionStatusIds
     * @return static
     */
    public function joinAuctionFilterAuctionStatusId(int|array|null $auctionStatusIds): static
    {
        $this->joinAuction();
        $this->filterArray('a.auction_status_id', $auctionStatusIds);
        return $this;
    }

    /**
     * Left join auction table and filter by auction sale group
     * Define filtering by a.sale_group
     * @param string|string[] $saleGroup
     * @return static
     */
    public function joinAuctionFilterSaleGroup(string|array|null $saleGroup): static
    {
        $this->joinAuction();
        $this->filterArray('a.sale_group', $saleGroup);
        return $this;
    }

    /**
     * Join `rtb_session` table
     * @return static
     */
    public function joinRtbSession(): static
    {
        $this->join('rtb_session');
        return $this;
    }

    /**
     * Left join rtb_session table
     * Define $userType (rs.user_type), that should be skipped while checking
     * @param int|array|null $userType
     * @return static
     */
    public function joinRtbSessionSkipUserType(int|array|null $userType): static
    {
        $this->joinRtbSession();
        $this->skipArray('rs.user_type', $userType);
        return $this;
    }

    /**
     * Left join `user` table
     * @return static
     */
    public function joinUser(): static
    {
        $this->join('user');
        return $this;
    }

    /**
     * join `user_by_added_by` table
     * @return static
     */
    public function joinUserByAddedBy(): static
    {
        $this->join('user');
        $this->join('user_by_added_by');
        return $this;
    }

    /**
     * Left join user table and filter by user account
     * Define filtering by a.auction_status_id
     * @param int|int[] $accountIds
     * @return static
     */
    public function joinUserFilterAccountId(int|array|null $accountIds): static
    {
        $this->joinUser();
        $this->filterArray('u.account_id', $accountIds);
        return $this;
    }

    /**
     * Left join user table and filter by user status
     * Define filtering by u.user_status_id
     * @param int|int[] $userStatusIds
     * @return static
     */
    public function joinUserFilterUserStatusId(int|array|null $userStatusIds): static
    {
        $this->joinUser();
        $this->filterArray('u.user_status_id', $userStatusIds);
        return $this;
    }

    /**
     * Left join user table and define ORDER BY u.username
     * @param bool $ascending
     * @return static
     */
    public function joinUserOrderByUsername(bool $ascending = true): static
    {
        $this->joinUser();
        $this->order('u.username', $ascending);
        return $this;
    }

    /**
     * Join `user` table and skip flags
     * @param int|int[] $flags
     * @return static
     */
    public function joinUserSkipFlag(int|array|null $flags): static
    {
        $this->joinUser();
        $this->skipArray('u.flag', $flags);
        return $this;
    }

    /**
     * Left join user_billing table
     * @return static
     */
    public function joinUserBilling(): static
    {
        $this->join('user_billing');
        return $this;
    }

    /**
     * Left join user_info table
     * @return static
     */
    public function joinUserInfo(): static
    {
        $this->join('user_info');
        return $this;
    }

    /**
     * Left join user_info table
     * Define ORDER BY ui.first_name
     * @param bool $ascending
     * @return static
     */
    public function joinUserInfoOrderByFirstName(bool $ascending = true): static
    {
        $this->joinUserInfo();
        $this->order('ui.first_name', $ascending);
        return $this;
    }

    /**
     * Left join user_info table
     * Define ORDER BY ui.last_name
     * @param bool $ascending
     * @return static
     */
    public function joinUserInfoOrderByLastName(bool $ascending = true): static
    {
        $this->joinUserInfo();
        $this->order('ui.last_name', $ascending);
        return $this;
    }

    /**
     * Left join user_shipping table
     * @return static
     */
    public function joinUserShipping(): static
    {
        $this->join('user_shipping');
        return $this;
    }
}
