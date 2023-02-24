<?php
/**
 * Data loading for AuctionBidderReporter
 *
 * SAM-4618: Refactor auction bidder report
 *
 * @author        Vahagn Hovsepyan
 * @since         Dec 14, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Report\Auction\Bidder;

use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserCustData\UserCustDataReadRepositoryCreateTrait;
use Sam\Timezone\ApplicationTimezoneProviderAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use User;
use UserCustData;
use UserCustField;

/**
 * Class DataLoader
 */
class DataLoader extends CustomizableClass
{
    use ApplicationTimezoneProviderAwareTrait;
    use AuctionBidderReadRepositoryCreateTrait;
    use BidTransactionReadRepositoryCreateTrait;
    use DbConnectionTrait;
    use FilterAuctionAwareTrait;
    use SystemAccountAwareTrait;
    use TimezoneLoaderAwareTrait;
    use UserCustDataReadRepositoryCreateTrait;
    use UserCustomFieldLoaderAwareTrait;
    use UserReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int
     */
    public function countTotal(): int
    {
        $total = $this->prepareRepository()->count();
        return $total;
    }

    /**
     * @return AuctionBidderReadRepository
     */
    public function prepareRepository(): AuctionBidderReadRepository
    {
        $auctionBidderRepository = $this->createAuctionBidderReadRepository()
            ->enableReadOnlyDb(true)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->joinUserByAddedBy()
            ->joinUserInfo()
            ->joinUserBilling()
            ->joinUserShipping()
            ->filterAuctionId($this->getFilterAuctionId())
            ->orderByBidderNum(false)
            ->select(
                [
                    'aub.auction_id',
                    'aub.bidder_num',
                    'aub.registered_on',
                    'aub.user_id',
                    'ui.company_name',
                    'ui.first_name',
                    'ui.last_name',
                    'ui.sales_tax',
                    'ui.tax_application',
                    'ui.note',
                    'ui.phone',
                    'ui.news_letter',
                    'ui.referrer',
                    'ui.referrer_host',
                    'ub.contact_type AS bill_contact_type',
                    'ub.company_name AS bill_company_name',
                    'ub.first_name AS bill_first_name',
                    'ub.last_name AS bill_last_name',
                    'ub.phone AS bill_phone',
                    'ub.fax AS bill_fax',
                    'ub.country AS bill_country',
                    'ub.address AS bill_address',
                    'ub.address2 AS bill_address2',
                    'ub.address3 AS bill_address3',
                    'ub.city AS bill_city',
                    'ub.state AS bill_state',
                    'ub.zip AS bill_zip',
                    'ub.use_card',
                    'ub.cc_type',
                    'ub.cc_number',
                    'ub.cc_exp_date',
                    'ub.bank_routing_number',
                    'ub.bank_account_number',
                    'ub.bank_account_type',
                    'ub.bank_account_name',
                    'ub.bank_name',
                    'us.contact_type AS ship_contact_type',
                    'us.company_name AS ship_company_name',
                    'us.first_name AS ship_first_name',
                    'us.last_name AS ship_last_name',
                    'us.phone AS ship_phone',
                    'us.fax AS ship_fax',
                    'us.country AS ship_country',
                    'us.address AS ship_address',
                    'us.address2 AS ship_address2',
                    'us.address3 AS ship_address3',
                    'us.city AS ship_city',
                    'us.state AS ship_state',
                    'us.zip AS ship_zip',
                    'uaddedby.username AS addedby_username',
                ]
            )
            ->setChunkSize(200);
        return $auctionBidderRepository;
    }

    /**
     * Load array of user custom data indexed by user custom field id
     * @param int $userId
     * @param int[] $userCustomFieldIds
     * @return UserCustData[]
     */
    public function loadUserCustomData(int $userId, array $userCustomFieldIds): array
    {
        $userCustomDatas = $this->createUserCustDataReadRepository()
            ->filterUserId($userId)
            ->filterUserCustFieldId($userCustomFieldIds)
            ->filterActive(true)
            ->loadEntities();
        $userCustomDatas = ArrayHelper::indexEntities($userCustomDatas, 'UserCustFieldId');
        return $userCustomDatas;
    }

    /**
     * Extract user data and user's name who added this user, indexed by user.id
     * @param int[] $ids
     * @return User[]
     */
    public function loadUsersByIds(array $ids): array
    {
        $users = $this->createUserReadRepository()
            ->filterId($ids)
            ->loadEntities();
        $users = ArrayHelper::indexEntities($users, 'Id');
        return $users;
    }

    /**
     * @param int[] $ids
     * @return array
     */
    public function loadAllLastUsersBidDates(array $ids): array
    {
        $rows = $this->createBidTransactionReadRepository()
            ->enableReadOnlyDb(true)
            ->filterUserId($ids)
            ->filterBidGreater(0)
            ->orderByCreatedOn(false)
            ->select(
                [
                    "created_on AS last_bid_date",
                    "user_id",
                ]
            )
            ->skipAuctionId(null)
            ->skipLotItemId(null)
            ->skipDeleted(true)
            ->loadRows();

        $results = [];
        foreach ($rows as $row) {
            $userId = (int)$row['user_id'];
            if (isset($results[$userId])) {
                continue;
            }
            $results[$userId] = $row['last_bid_date'];
        }
        return $results;
    }

    /**
     * @param int[] $ids
     * @return array
     */
    public function loadAllLastUsersWinDates(array $ids): array
    {
        $rows = $this->createBidTransactionReadRepository()
            ->enableReadOnlyDb(true)
            ->defineLotItemJoinByIdAndAuctionAndWinningBidder()
            ->filterUserId($ids)
            ->joinLotItemFilterInternetBidGreater(0)
            ->joinLotItemSkipHammerPrice(null)
            ->filterBidStatus(Constants\BidTransaction::BS_WINNER)
            ->orderByCreatedOn(false)
            ->select(
                [
                    "bt.created_on AS last_win_date",
                    "user_id",
                ]
            )
            ->skipAuctionId(null)
            ->skipLotItemId(null)
            ->skipDeleted(true)
            ->loadRows();

        $results = [];
        foreach ($rows as $row) {
            $userId = (int)$row['user_id'];
            if (isset($results[$userId])) {
                continue;
            }
            $results[$userId] = $row['last_win_date'];
        }
        return $results;
    }

    /**
     * @param int[] $ids
     * @return array
     */
    public function loadAllLastUserPaymentDates(array $ids): array
    {
        $idList = implode(',', $ids);
        $openInvoiceStatusList = implode(',', Constants\Invoice::$openInvoiceStatuses);
        $availableSettlementStatusList = implode(',', Constants\Settlement::$availableSettlementStatuses);
        $ttInvoice = Constants\Payment::TT_INVOICE;
        $ttSettlement = Constants\Payment::TT_SETTLEMENT;
        $query = <<<SQL
SELECT
  MAX(last_payment_date) AS last_payment_date,
  user_id
FROM (
  SELECT
    MAX(p.created_on) last_payment_date,
    i.bidder_id as user_id
  FROM invoice i
    LEFT JOIN payment p ON p.tran_id = i.id AND p.tran_type = '{$ttInvoice}' AND p.active = true 
  WHERE i.bidder_id in ({$idList})
    AND i.invoice_status_id IN ({$openInvoiceStatusList})
  GROUP BY i.bidder_id
  UNION
  SELECT
    MAX(p.created_on) last_payment_date,
    s.consignor_id as user_id
  FROM settlement s
  LEFT JOIN payment p ON p.tran_id = s.id AND p.tran_type = '{$ttSettlement}' AND p.active = true 
  WHERE s.consignor_id in ({$idList})
    AND s.settlement_status_id IN ({$availableSettlementStatusList})
  GROUP BY s.consignor_id
) last_payments
GROUP BY user_id;
SQL;

        $this->query($query);
        $rows = [];
        while ($row = $this->fetchAssoc()) {
            $userId = (int)$row['user_id'];
            if (isset($rows[$userId])) {
                continue;
            }
            $rows[$userId] = $row['last_payment_date'];
        }
        return $rows;
    }

    /**
     * @return UserCustField[]
     */
    public function loadUserCustomFields(): array
    {
        $customFields = $this->getUserCustomFieldLoader()->loadAllEditable([], true);
        return $customFields;
    }
}
