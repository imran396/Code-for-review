<?php
/**
 * SAM-2776: Optimize user csv export
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\User\UserList\Csv\Internal\Load;

use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\User\Save\UserCustomDataProducerCreateTrait;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\Invoice\InvoiceReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserCustData\UserCustDataReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserLogin\UserLoginReadRepositoryCreateTrait;
use UserCustData;
use UserCustField;

/**
 * Class ChunkDataProvider
 * @package Sam\Report\User\UserList\Csv\Internal\Load
 */
class ChunkDataProvider extends CustomizableClass
{
    use BidTransactionReadRepositoryCreateTrait;
    use DbConnectionTrait;
    use InvoiceReadRepositoryCreateTrait;
    use UserCustDataReadRepositoryCreateTrait;
    use UserCustomDataProducerCreateTrait;
    use UserLoginReadRepositoryCreateTrait;

    protected array $chunkUserIds;
    protected ?array $lastBidDatesCache = null;
    protected ?array $lastWinBidDatesCache = null;
    protected ?array $lastInvoiceDatesCache = null;
    protected ?array $lastPaymentDatesCache = null;
    protected ?array $userLoginIpAddressesCache = null;
    protected ?array $userCustomDataCache = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int[] $chunkUserIds
     * @param bool $isReadOnlyDb
     * @return static
     */
    public function construct(array $chunkUserIds, bool $isReadOnlyDb = false): static
    {
        $this->chunkUserIds = $chunkUserIds;
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }

    /**
     * Return date of last bid of user in UTC
     *
     * @param int $userId
     * @return string|null
     */
    public function detectLastBidDateUtc(int $userId): ?string
    {
        return $this->loadLastBidDates($this->chunkUserIds, $this->isReadOnlyDb)[$userId]['created_on'] ?? null;
    }

    /**
     * Return date of last winning bid of user for sold lot in UTC
     *
     * @param int $userId
     * @return string|null
     */
    public function detectLastWinBidDateUtc(int $userId): ?string
    {
        return $this->loadLastWinBidDates($this->chunkUserIds, $this->isReadOnlyDb)[$userId]['created_on'] ?? null;
    }

    /**
     * Return date of the last invoice for the user in UTC
     *
     * @param int $userId
     * @return string|null
     */
    public function detectLastInvoiceDateUtc(int $userId): ?string
    {
        return $this->loadLastInvoiceDates($this->chunkUserIds, $this->isReadOnlyDb)[$userId]['created_on'] ?? null;
    }

    /**
     * Return date of last payment for user in UTC
     *
     * @param int $userId
     * @return string|null
     */
    public function detectLastPaymentDateUtc(int $userId): ?string
    {
        return $this->loadLastPaymentDates($this->chunkUserIds)[$userId]['last_payment_date'] ?? null;
    }

    public function detectUserLoginIpAddress(int $userId): ?string
    {
        return $this->loadUserLoginIpAddresses($this->chunkUserIds, $this->isReadOnlyDb)[$userId]['ip_address'] ?? null;
    }

    public function loadUserCustomDataOrCreate(UserCustField $userCustomField, int $userId): UserCustData
    {
        return $this->loadCustomDataForUsers($this->chunkUserIds, $this->isReadOnlyDb)[$userId][$userCustomField->Id]
            ?? $this->createUserCustomDataProducer()->create($userCustomField, $userId, true);
    }

    protected function loadCustomDataForUsers(array $userIds, bool $isReadOnlyDb): array
    {
        if ($this->userCustomDataCache === null) {
            $this->userCustomDataCache = [];
            $data = $this->createUserCustDataReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterActive(true)
                ->filterUserId($userIds)
                ->loadEntities();
            foreach ($data as $datum) {
                $this->userCustomDataCache[$datum->UserId][$datum->UserCustFieldId] = $datum;
            }
        }
        return $this->userCustomDataCache;
    }

    protected function loadUserLoginIpAddresses(array $userIds, bool $isReadOnlyDb): array
    {
        if ($this->userLoginIpAddressesCache === null) {
            $rows = $this->createUserLoginReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterUserId($userIds)
                ->select(['user_id', 'MAX(ip_address) AS ip_address'])
                ->inlineCondition('ul.logged_date = (SELECT MAX(ul1.logged_date) FROM user_login ul1 WHERE ul1.user_id = ul.user_id GROUP BY ul1.user_id)')
                ->groupByUserId()
                ->loadRows();
            $this->userLoginIpAddressesCache = ArrayHelper::produceIndexedArray($rows, 'user_id');
        }
        return $this->userLoginIpAddressesCache;
    }

    protected function loadLastPaymentDates(array $userIds): array
    {
        if ($this->lastPaymentDatesCache === null) {
            $settlementStatusList = implode(',', Constants\Settlement::$availableSettlementStatuses);
            $invoiceStatusList = implode(',', Constants\Invoice::$availableInvoiceStatuses);
            $userIdList = implode(',', $userIds);
            $query = <<<SQL
SELECT IFNULL(i.bidder_id, s.consignor_id) AS user_id, MAX(IF(p.paid_on !='' AND p.paid_on IS NOT NULL, p.paid_on, p.created_on)) AS last_payment_date
FROM payment AS p
  LEFT JOIN invoice AS i ON i.id = p.tran_id
  LEFT JOIN settlement AS s ON s.id = p.tran_id
WHERE p.amount > 0
  AND p.active = true
  AND (i.bidder_id IN ({$userIdList}) OR s.consignor_id IN ({$userIdList}))
  AND (i.invoice_status_id IN ({$invoiceStatusList}) OR s.settlement_status_id IN ({$settlementStatusList}))
  AND (p.tran_id = i.id OR p.tran_id = s.id)
GROUP BY user_id
SQL;
            $this->query($query);
            $rows = $this->fetchAllAssoc();
            $this->lastPaymentDatesCache = ArrayHelper::produceIndexedArray($rows, 'user_id');
        }
        return $this->lastPaymentDatesCache;
    }

    protected function loadLastInvoiceDates(array $userIds, bool $isReadOnlyDb): array
    {
        if ($this->lastInvoiceDatesCache === null) {
            $rows = $this->createInvoiceReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterBidderId($userIds)
                ->filterInvoiceStatusId(Constants\Invoice::$openInvoiceStatuses)
                ->groupByBidderId()
                ->inlineCondition('(SELECT COUNT(1) FROM invoice_item AS ii WHERE ii.invoice_id = i.id AND ii.hammer_price IS NOT NULL AND ii.active) > 0')
                ->select(['i.bidder_id', 'MAX(i.created_on) AS created_on'])
                ->loadRows();
            $this->lastInvoiceDatesCache = ArrayHelper::produceIndexedArray($rows, 'bidder_id');
        }
        return $this->lastInvoiceDatesCache;
    }

    protected function loadLastBidDates(array $userIds, bool $isReadOnlyDb): array
    {
        if ($this->lastBidDatesCache === null) {
            $rows = $this->createBidTransactionReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterBidGreater(0)
                ->filterDeleted(false)
                ->filterUserId($userIds)
                ->groupByUserId()
                ->select(['user_id', 'MAX(bt.created_on) AS created_on'])
                ->skipAuctionId(null)
                ->skipLotItemId(null)
                ->loadRows();
            $this->lastBidDatesCache = ArrayHelper::produceIndexedArray($rows, 'user_id');
        }
        return $this->lastBidDatesCache;
    }

    protected function loadLastWinBidDates(array $userIds, bool $isReadOnlyDb): array
    {
        if ($this->lastWinBidDatesCache === null) {
            $rows = $this->createBidTransactionReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->defineLotItemJoinByIdAndAuctionAndWinningBidder()
                ->filterBidStatus(Constants\BidTransaction::BS_WINNER)
                ->filterDeleted(false)
                ->filterUserId($userIds)
                ->groupByUserId()
                ->joinLotItemFilterInternetBidGreater(0)
                ->joinLotItemSkipHammerPrice(null)
                ->select(['user_id', 'MAX(bt.created_on) AS created_on'])
                ->loadRows();
            $this->lastWinBidDatesCache = ArrayHelper::produceIndexedArray($rows, 'user_id');
        }
        return $this->lastWinBidDatesCache;
    }
}
