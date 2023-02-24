<?php
/**
 * SAM-4664: User dates detector class
 * https://bidpath.atlassian.net/browse/SAM-4664
 *
 * @author        Vahagn Hovsepyan
 * @since         Dec 25, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\User\Calculate;

use DateTime;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\Invoice\InvoiceReadRepositoryCreateTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

/**
 * Class UserDateDetector
 */
class UserDateDetector extends CustomizableClass
{
    use BidTransactionReadRepositoryCreateTrait;
    use DateHelperAwareTrait;
    use DbConnectionTrait;
    use InvoiceReadRepositoryCreateTrait;
    use TimezoneLoaderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return date of the last invoice for the user in UTC
     * @param int $userId
     * @return DateTime|null
     */
    public function detectLastInvoiceDateUtc(int $userId): ?DateTime
    {
        $row = $this->createInvoiceReadRepository()
            ->enableReadOnlyDb(true)
            ->filterInvoiceStatusId(Constants\Invoice::$openInvoiceStatuses)
            ->filterBidderId($userId)
            ->inlineCondition('(SELECT COUNT(1) FROM invoice_item AS ii WHERE ii.invoice_id = i.id AND ii.hammer_price IS NOT NULL AND ii.active) > 0')
            ->orderByCreatedOn(false)
            ->select(["i.created_on"])
            ->loadRow();
        $dateUtc = isset($row['created_on']) ? new DateTime($row['created_on']) : null;
        return $dateUtc;
    }

    /**
     * Return date of the last invoice for the user in System Timezone
     * @param int $userId
     * @return DateTime|null
     */
    public function detectLastInvoiceDateSys(int $userId): ?DateTime
    {
        $dateUtc = $this->detectLastInvoiceDateUtc($userId);
        $dateSys = $this->getDateHelper()->convertUtcToSys($dateUtc);
        return $dateSys;
    }

    /**
     * Return date of last bid of user in UTC
     * @param int $userId
     * @return DateTime|null
     */
    public function detectLastBidDateUtc(int $userId): ?DateTime
    {
        $row = $this->createBidTransactionReadRepository()
            ->enableReadOnlyDb(true)
            ->filterUserId($userId)
            ->filterBidGreater(0)
            ->filterDeleted(false)
            ->orderByCreatedOn(false)
            ->select(["bt.created_on"])
            ->skipAuctionId(null)
            ->skipLotItemId(null)
            ->loadRow();
        $dateUtc = isset($row['created_on']) ? new DateTime($row['created_on']) : null;
        return $dateUtc;
    }

    /**
     * Return date of last bid of user in System Timezone
     * @param int $userId
     * @return DateTime|null
     */
    public function detectLastBidDateSys(int $userId): ?DateTime
    {
        $dateUtc = $this->detectLastBidDateUtc($userId);
        $dateSys = $this->getDateHelper()->convertUtcToSys($dateUtc);
        return $dateSys;
    }

    /**
     * Return date of last winning bid of user for sold lot in UTC
     * @param int $userId
     * @return DateTime|null
     */
    public function detectLastWinBidDateUtc(int $userId): ?DateTime
    {
        $row = $this->createBidTransactionReadRepository()
            ->enableReadOnlyDb(true)
            ->defineLotItemJoinByIdAndAuctionAndWinningBidder()
            ->filterUserId($userId)
            ->filterBidStatus(Constants\BidTransaction::BS_WINNER)
            ->filterDeleted(false)
            ->joinLotItemFilterInternetBidGreater(0)
            ->joinLotItemSkipHammerPrice(null)
            ->orderByCreatedOn(false)
            ->select(['bt.created_on'])
            ->loadRow();
        $dateUtc = isset($row['created_on']) ? new DateTime($row['created_on']) : null;
        return $dateUtc;
    }

    /**
     * Return date of last winning bid of user for sold lot in System Timezone
     * @param int $userId
     * @return DateTime|null
     */
    public function detectLastWinBidDateSys(int $userId): ?DateTime
    {
        $dateUtc = $this->detectLastWinBidDateUtc($userId);
        $dateSys = $this->getDateHelper()->convertUtcToSys($dateUtc);
        return $dateSys;
    }

    /**
     * Return date of last payment for user in UTC
     * @param int $userId
     * @return DateTime|null
     */
    public function detectLastPaymentDateUtc(int $userId): ?DateTime
    {
        $settlementStatusList = implode(',', Constants\Settlement::$availableSettlementStatuses);
        $invoiceStatusList = implode(',', Constants\Invoice::$availableInvoiceStatuses);
        $n = "\n";
        // @formatter:off
        $query =
            "SELECT IF(p.paid_on !='' AND p.paid_on IS NOT NULL, p.paid_on, p.created_on) AS last_payment_date " . $n .
            "FROM payment AS p " . $n .
            "LEFT JOIN invoice AS i ON i.id = p.tran_id " . $n .
            "LEFT JOIN settlement AS s ON s.id = p.tran_id " . $n .
            "WHERE p.amount > 0 " . $n .
                "AND p.active = true " . $n .
                "AND (i.bidder_id = " . $this->escape($userId) . "  " . $n .
                    "OR s.consignor_id = " . $this->escape($userId) . " ) " . $n .
                "AND (i.invoice_status_id IN ({$invoiceStatusList}) " .
                    "OR s.settlement_status_id IN ({$settlementStatusList})) " . $n .
                "AND (p.tran_id = i.id OR p.tran_id = s.id) " . $n .
            "ORDER BY p.paid_on DESC LIMIT 1; ";
        // @formatter:on
        $this->enableReadOnlyDb(true);
        $this->query($query);
        $row = $this->fetchAssoc();
        $dateUtc = isset($row['last_payment_date']) ? new DateTime($row['last_payment_date']) : null;
        return $dateUtc;
    }

    /**
     * Return date of last payment for user in System Timezone
     * @param int $userId
     * @return DateTime|null
     */
    public function detectLastPaymentDateSys(int $userId): ?DateTime
    {
        $dateUtc = $this->detectLastPaymentDateUtc($userId);
        $dateSys = $this->getDateHelper()->convertUtcToSys($dateUtc);
        return $dateSys;
    }
}
