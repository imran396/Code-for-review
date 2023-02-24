<?php
/**
 * General repository for User entity
 *
 * SAM-3624: User general repository class https://bidpath.atlassian.net/browse/SAM-3624
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           14 Feb, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of users filtered by criteria
 * $userRepository = \Sam\Storage\ReadRepository\Entity\User\UserReadRepository::new()
 *     ->filterAccountId($mainAccountId)          // single value passed as argument
 *     ->filterUsername(['admin', 'bidder'])      // array passed as argument
 *     ->filterUserStatusId(null)                 // filter by user_status_id IS NULL
 *     ->skipId([$myId]);                         // search avoiding these user ids
 * $isFound = $userRepository->exist();
 * $count = $userRepository->count();
 * $users = $userRepository->loadEntities();
 *
 * // Sample2. Load single user
 * $userRepository = \Sam\Storage\ReadRepository\Entity\User\UserReadRepository::new()
 *     ->filterId(1);
 * $user = $userRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\User;

use Sam\Core\Constants;
use Sam\Storage\ReadRepository\Entity\UserCustData\UserCustDataJoiningTrait;

/**
 * Class UserReadRepository
 * @package Sam\Storage\ReadRepository\Entity\User
 */
class UserReadRepository extends AbstractUserReadRepository
{
    use UserCustDataJoiningTrait;

    public const ALIAS_IS_SALES_STAFF = 'is_sales_staff';
    public const SELECT_IS_SALES_STAFF = '(SELECT COUNT(1) FROM admin WHERE user_id = `u`.`id` AND admin_privileges & 128)';

    /**
     * Used by UserCustDataJoiningTrait
     * @var string
     */
    protected string $userCustDataJoinFieldName = 'id';
    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = u.account_id ',
        'admin' => 'JOIN admin ad ON ad.user_id = u.id ',
        'auction_bidder' => 'JOIN auction_bidder aub ON aub.user_id = u.id',
        'bidder' => 'JOIN bidder b ON b.user_id = u.id',
        'buyer_group_user' => 'JOIN buyer_group_user bgu ON bgu.user_id = u.id',
        'consignor' => 'JOIN consignor cons ON cons.user_id = u.id',
        'entity_sync' => 'JOIN entity_sync esync ON (u.id = esync.entity_id AND esync.entity_type = ' . Constants\EntitySync::TYPE_USER . ')',
        'user_account' => 'JOIN user_account ua ON ua.user_id = u.id',
        'user_authentication' => 'JOIN user_authentication uau ON uau.user_id = u.id ',
        'user_cust_data' => 'JOIN user_cust_data ucd ON ucd.user_id = u.id',
        'user_info' => 'JOIN user_info ui ON ui.user_id = u.id ',
        'user_billing' => 'JOIN user_billing ub ON ub.user_id = u.id',
        'user_shipping' => 'JOIN user_shipping us ON us.user_id = u.id',
        'user_by_added_by' => 'JOIN user AS uaddedby ON u.added_by = uaddedby.id',
    ];

    /**
     * this array is used in the functions like filterGreater instead of the sub-query
     */
    private array $subQueryConditions = [
        // 'has_winning_bidder' => '(SELECT COUNT(1) FROM lot_item WHERE consignor_id = `u`.`id` AND winning_bidder_id > 0)',
        'is_agent' => '(SELECT COUNT(1) FROM bidder WHERE user_id = `u`.`id` AND agent)',
        // 'is_consignor' => '(SELECT COUNT(1) FROM consignor WHERE user_id = `u`.`id`)',
        self::ALIAS_IS_SALES_STAFF => self::SELECT_IS_SALES_STAFF,
        'has_bidder' => '(SELECT COUNT(1) FROM bidder WHERE user_id = u.id)'
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Filter by 'is_agent' value fetched by sub-query
     * @param int $value
     * @return static
     */
    public function filterSubqueryIsAgentGreater(int $value): static
    {
        $this->filterInequality($this->subQueryConditions['is_agent'], $value, '>');
        return $this;
    }

    /**
     * Filter by 'has_bidder' value fetched by sub-query
     * @param int $value
     * @return static
     */
    public function filterSubqueryHasBidderGreater(int $value): static
    {
        $this->filterInequality($this->subQueryConditions['has_bidder'], $value, '>');
        return $this;
    }

    /**
     * Filter by 'is_sales_staff' value fetched by sub-query
     * @param int $value
     * @return static
     */
    public function filterSubquerySalesStaffGreater(int $value): static
    {
        $this->filterInequality($this->subQueryConditions[self::ALIAS_IS_SALES_STAFF], $value, '>');
        return $this;
    }

    /**
     * Join 'account' table
     * @return static
     */
    public function joinAccount(): static
    {
        $this->join('account');
        return $this;
    }

    /**
     * Left join account table
     * Define filtering by account.active
     * @param bool|bool[] $active
     * @return static
     */
    public function joinAccountFilterActive(bool|array|null $active): static
    {
        $this->joinAccount();
        $this->filterArray('acc.active', $active);
        return $this;
    }

    /**
     * Left join to "auction_bidder" table.
     * @return $this
     */
    public function joinAuctionBidder(): static
    {
        $this->join('auction_bidder');
        return $this;
    }

    /**
     * Left join to "auction_bidder" table by user.id and apply strict filtering by auction id(s).
     * @param $auctionId
     * @return $this
     */
    public function joinAuctionBidderFilterAuctionId($auctionId): static
    {
        $this->joinAuctionBidder();
        $this->filterArray('aub.auction_id', $auctionId);
        return $this;
    }

    /**
     * Left join to "auction_bidder" table by user.id and by definite auction id.
     * @param int $auctionId
     * @return $this
     */
    public function joinAuctionBidderOnAuctionId(int $auctionId): static
    {
        $this->join('JOIN auction_bidder aub ON aub.user_id = u.id AND aub.auction_id = ' . $auctionId);
        return $this;
    }

    /**
     * Left join `buyer_group_user` table
     * @return static
     */
    public function joinBuyerGroupUser(): static
    {
        $this->join('buyer_group_user');
        return $this;
    }

    /**
     * Left join buyer_group_user table
     * Define filtering by bgu.buyer_group_id
     * @param int|array|null $buyerGroupId
     * @return static
     */
    public function joinBuyerGroupUserFilterBuyerGroupId(int|array|null $buyerGroupId): static
    {
        $this->joinBuyerGroupUser();
        $this->filterArray('bgu.buyer_group_id', $buyerGroupId);
        return $this;
    }

    /**
     * Left join buyer_group_user table
     * Define filtering by bgu.active
     * @param bool $active
     * @return static
     */
    public function joinBuyerGroupUserFilterActive(bool $active): static
    {
        $this->joinBuyerGroupUser();
        $this->filterArray('bgu.active', $active);
        return $this;
    }

    /**
     * Left join buyer_group_user table
     * Define filtering by bgu.id
     * @param int|int[] $ids
     * @return static
     */
    public function joinBuyerGroupUserFilterId(int|array|null $ids): static
    {
        $this->joinBuyerGroupUser();
        $this->filterArray('bgu.id', $ids);
        return $this;
    }

    /**
     * Left join user_account table
     * @return static
     */
    public function joinUserAccount(): static
    {
        $this->join('user_account');
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
     * join user_info table
     * Define filtering by ui.phone
     * @param string|string[] $phone
     * @return static
     */
    public function joinUserInfoFilterPhone(string|array|null $phone): static
    {
        $this->joinUserInfo();
        $this->filterArray('ui.phone', $phone);
        return $this;
    }

    /**
     * Left join `user_billing` table
     * @return static
     */
    public function joinUserBilling(): static
    {
        $this->join('user_billing');
        return $this;
    }

    /**
     * join user_billing table
     * Define filtering by ub.phone
     * @param string|string[] $phone
     * @return static
     */
    public function joinUserBillingFilterPhone(string|array|null $phone): static
    {
        $this->joinUserBilling();
        $this->filterArray('ub.phone', $phone);
        return $this;
    }

    /**
     * Left join `bidder` table
     * @return static
     */
    public function joinBidder(): static
    {
        $this->join('bidder');
        return $this;
    }

    /**
     * Inner join `bidder` table
     * @return static
     */
    public function innerJoinBidder(): static
    {
        $this->innerJoin('bidder');
        return $this;
    }

    /**
     * Filter by bidder.agent_id field
     * @param int|int[] $agentId
     * @return static
     */
    public function joinBidderFilterAgentId(int|array|null $agentId): static
    {
        $this->joinBidder();
        $this->filterArray('b.agent_id', $agentId);
        return $this;
    }

    /**
     * Left join 'consignor' table.
     * @return $this
     */
    public function joinConsignor(): static
    {
        $this->join('consignor');
        return $this;
    }

    /**
     * Inner join 'consignor' table.
     * @return $this
     */
    public function innerJoinConsignor(): static
    {
        $this->innerJoin('consignor');
        return $this;
    }

    /**
     * Left join `user_cust_data` table
     * @return static
     */
    public function joinUserCustData(): static
    {
        $this->join('user_cust_data');
        return $this;
    }

    /**
     * @param int|int[] $userCustomFieldId
     * @return static
     */
    public function joinUserCustDataFilterUserCustFieldId(int|array|null $userCustomFieldId): static
    {
        $this->joinUserCustData();
        $this->filterArray('ucd.user_cust_field_id', $userCustomFieldId);
        return $this;
    }

    /**
     * Left join `user_authentication` table
     * @return static
     */
    public function joinUserAuthentication(): static
    {
        $this->join('user_authentication');
        return $this;
    }

    public function joinUserAuthenticationFilterIdpUuid(string $idpUuid): static
    {
        $this->joinUserAuthentication();
        $this->filterArray('uau.idp_uuid', $idpUuid);
        return $this;
    }

    /**
     * Left join `user_shipping` table
     * @return static
     */
    public function joinUserShipping(): static
    {
        $this->join('user_shipping');
        return $this;
    }

    /**
     * join user_shipping table
     * Define filtering by us.phone
     * @param string|string[] $phone
     * @return static
     */
    public function joinUserShippingFilterPhone(string|array|null $phone): static
    {
        $this->joinUserShipping();
        $this->filterArray('us.phone', $phone);
        return $this;
    }

    /**
     * @return static
     */
    public function joinUserSync(): static
    {
        $this->join('entity_sync');
        return $this;
    }

    /**
     * @param int|int[] $syncNamespaceIds
     * @return static
     */
    public function joinUserSyncFilterSyncNamespaceId(int|array|null $syncNamespaceIds): static
    {
        $this->joinUserSync();
        $this->filterArray('esync.sync_namespace_id', $syncNamespaceIds);
        return $this;
    }

    /**
     * @param string|string[] $keys
     * @return static
     */
    public function joinUserSyncFilterKey(string|array|null $keys): static
    {
        $this->joinUserSync();
        $this->filterArray('esync.key', $keys);
        return $this;
    }

    /**
     * Set LIKE mysql patter for ui.first_name filter
     * @param string $firstName
     * @return static
     */
    public function joinUserInfoLikeFirstName(string $firstName): static
    {
        $this->joinUserInfo();
        $this->like('ui.first_name', $firstName);
        return $this;
    }

    /**
     * Set LIKE mysql patter for ui.last_name filter
     * @param string $lastName
     * @return static
     */
    public function joinUserInfoLikeLastName(string $lastName): static
    {
        $this->joinUserInfo();
        $this->like('ui.last_name', $lastName);
        return $this;
    }

    /**
     * Define LIKE filter condition CONCAT(IFNULL(ui.first_name,''),' ',IFNULL(ui.last_name,'')
     * @param string $firstLastName
     * @return static
     */
    public function joinUserInfoLikeFirstLastName(string $firstLastName): static
    {
        $this->joinUserInfo();
        $this->like("CONCAT(IFNULL(ui.first_name,''),' ',IFNULL(ui.last_name,''))", $firstLastName);
        return $this;
    }

    /**
     * join `user_by_added_by` table
     * @return static
     */
    public function joinUserByAddedBy(): static
    {
        $this->join('user_by_added_by');
        return $this;
    }

    /**
     * Left join user_info table
     * Define ORDER BY ui.company_name
     * @param bool $ascending
     * @return static
     */
    public function orderByUserInfoCompanyName(bool $ascending = true): static
    {
        $this->joinUserInfo();
        $this->order('ui.company_name', $ascending);
        return $this;
    }

    /**
     * Left join user_info table
     * Define ORDER BY ui.first_name
     * @param bool $ascending
     * @return static
     */
    public function orderByUserInfoFirstFame(bool $ascending = true): static
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
    public function orderByUserInfoLastName(bool $ascending = true): static
    {
        $this->joinUserInfo();
        $this->order('ui.last_name', $ascending);
        return $this;
    }

    /**
     * Left join buyer_group_user table
     * Define ORDER BY bgu.id
     * @param bool $ascending
     * @return static
     */
    public function joinBuyerGroupUserOrderById(bool $ascending = true): static
    {
        $this->joinBuyerGroupUser();
        $this->order('bgu.id', $ascending);
        return $this;
    }

    /**
     * @param $ccNumberEncrypted
     * @return static
     */
    public function joinUserBillingFilterCcNumberHash($ccNumberEncrypted): static
    {
        $this->joinUserBilling();
        $this->filterArray('ub.cc_number_hash', $ccNumberEncrypted);
        return $this;
    }
}
