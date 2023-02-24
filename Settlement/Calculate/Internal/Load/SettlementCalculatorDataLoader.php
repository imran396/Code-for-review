<?php
/**
 * SAM-6499: Refactor Settlement Calculator module (2020 year)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Calculate\Internal\Load;


use QMySqli5DatabaseResult;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Calculate\Internal\Load\Cache\SettlementProcessingCacheAwareTrait;
use Sam\Storage\ReadRepository\Entity\Payment\PaymentReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\SettlementAdditional\SettlementAdditionalReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\SettlementItem\SettlementItemReadRepositoryCreateTrait;

/**
 * Class SettlementCalculatorDataLoader
 * @package Sam\Settlement\Calculate\Internal\Load
 * @internal
 */
class SettlementCalculatorDataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use PaymentReadRepositoryCreateTrait;
    use SettlementAdditionalReadRepositoryCreateTrait;
    use SettlementItemReadRepositoryCreateTrait;
    use SettlementProcessingCacheAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $tranId
     * @param bool $isReadOnlyDb
     * @return float
     * @internal
     */
    public function calcTotalPayments(int $tranId, bool $isReadOnlyDb = false): float
    {
        $row = $this->createPaymentReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterTranId($tranId)
            ->filterTranType(Constants\Payment::TT_SETTLEMENT)
            ->select(['SUM(amount) AS total_payment'])
            ->loadRow();
        return (float)($row['total_payment'] ?? 0.);
    }

    /**
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return float
     * @internal
     */
    public function calcTotalHammerPrice(int $settlementId, bool $isReadOnlyDb = false): float
    {
        if ($this->getSettlementProcessingCache()->has(__FUNCTION__, $settlementId)) {
            $value = (float)$this->getSettlementProcessingCache()->get(__FUNCTION__, $settlementId);
        } else {
            $row = $this->createSettlementItemReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterActive(true)
                ->filterSettlementId($settlementId)
                ->select(['SUM(hammer_price) AS total_hammer'])
                ->loadRow();
            $value = (float)($row['total_hammer'] ?? 0.);
            $this->getSettlementProcessingCache()->set(__FUNCTION__, $settlementId, $value);
        }
        return $value;
    }

    /**
     * Calculate total fee for settlement
     *
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return float
     * @internal
     */
    public function calcTotalFee(int $settlementId, bool $isReadOnlyDb = false): float
    {
        if ($this->getSettlementProcessingCache()->has(__FUNCTION__, $settlementId)) {
            $value = (float)$this->getSettlementProcessingCache()->get(__FUNCTION__, $settlementId);
        } else {
            $row = $this->createSettlementItemReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterActive(true)
                ->filterSettlementId($settlementId)
                ->select(['SUM(fee) AS total_fee'])
                ->loadRow();
            $value = (float)($row['total_fee'] ?? 0.);
            $this->getSettlementProcessingCache()->set(__FUNCTION__, $settlementId, $value);
        }
        return $value;
    }

    /**
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return float
     * @internal
     */
    public function calcTotalCommission(int $settlementId, bool $isReadOnlyDb = false): float
    {
        if ($this->getSettlementProcessingCache()->has(__FUNCTION__, $settlementId)) {
            $value = (float)$this->getSettlementProcessingCache()->get(__FUNCTION__, $settlementId);
        } else {
            $row = $this->createSettlementItemReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterActive(true)
                ->filterSettlementId($settlementId)
                ->select(['SUM(ROUND(commission,2)) AS item_commission'])
                ->loadRow();
            $value = (float)($row['item_commission'] ?? 0.);
            $this->getSettlementProcessingCache()->set(__FUNCTION__, $settlementId, $value);
        }
        return $value;
    }

    /**
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return float
     * @internal
     */
    public function calcTotalCharges(int $settlementId, bool $isReadOnlyDb = false): float
    {
        if ($this->getSettlementProcessingCache()->has(__FUNCTION__, $settlementId)) {
            $value = (float)$this->getSettlementProcessingCache()->get(__FUNCTION__, $settlementId);
        } else {
            $row = $this->createSettlementAdditionalReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterSettlementId($settlementId)
                ->select(['SUM(amount) AS total_charge'])
                ->loadRow();
            $value = (float)($row['total_charge'] ?? 0.);
            $this->getSettlementProcessingCache()->set(__FUNCTION__, $settlementId, $value);
        }

        return $value;
    }

    /**
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return float
     * @internal
     */
    public function calcTotal(int $settlementId, bool $isReadOnlyDb = false): float
    {
        if ($this->getSettlementProcessingCache()->has(__FUNCTION__, $settlementId)) {
            $value = (float)$this->getSettlementProcessingCache()->get(__FUNCTION__, $settlementId);
        } else {
            $row = $this->createSettlementItemReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterActive(true)
                ->filterSettlementId($settlementId)
                ->select(['SUM(subtotal) AS total'])
                ->loadRow();
            $value = (float)($row['total'] ?? 0.);
            $this->getSettlementProcessingCache()->set(__FUNCTION__, $settlementId, $value);
        }
        return $value;
    }

    /**
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return float
     * @internal
     */
    public function calcPaidLotsSubTotal(int $settlementId, bool $isReadOnlyDb = false): float
    {
        if ($this->getSettlementProcessingCache()->has(__FUNCTION__, $settlementId)) {
            $value = (float)$this->getSettlementProcessingCache()->get(__FUNCTION__, $settlementId);
        } else {
            $query = $this->preparePaidLotsQuery('SUM(si.subtotal) AS sub_total', $settlementId);
            $this
                ->enableReadOnlyDb($isReadOnlyDb)
                ->query($query);
            $row = $this->fetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
            $value = (float)$row['sub_total'];
            $this->getSettlementProcessingCache()->set(__FUNCTION__, $settlementId, $value);
        }
        return $value;
    }

    /**
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return float
     * @internal
     */
    public function calcPaidLotsConsignmentCommission(int $settlementId, bool $isReadOnlyDb = false): float
    {
        if ($this->getSettlementProcessingCache()->has(__FUNCTION__, $settlementId)) {
            $value = (float)$this->getSettlementProcessingCache()->get(__FUNCTION__, $settlementId);
        } else {
            /* Changes related to SAM-946: Consignor Commission Ranges
             * "SUM(ROUND(si.hammer_price * ((SELECT IF ((si.consignment_commission IS NULL OR si.consignment_commission = '' " .
                "OR si.consignment_commission < 1), s.consignment_commission, si.consignment_commission))/100),2)) AS consignment_commission " . */
            $query = $this->preparePaidLotsQuery('SUM(ROUND(si.commission,2)) AS commission', $settlementId);
            $this
                ->enableReadOnlyDb($isReadOnlyDb)
                ->query($query);
            $row = $this->fetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
            $value = (float)$row['commission'];
            $this->getSettlementProcessingCache()->set(__FUNCTION__, $settlementId, $value);
        }
        return $value;
    }

    /**
     * @param string $select
     * @param int $settlementId
     * @return string
     */
    protected function preparePaidLotsQuery(string $select, int $settlementId): string
    {
        // @formatter:off
        $query =
            "SELECT {$select} " .
            "FROM settlement_item AS si " .
            "inner join settlement AS s ON si.settlement_id = s.id " .
            "inner join invoice_item AS ii ON si.auction_id = ii.auction_id AND si.lot_item_id = ii.lot_item_id AND ii.active = true " .
            "inner join invoice AS i ON i.id = ii.invoice_id AND i.invoice_status_id IN (" . Constants\Invoice::IS_PAID . "," . Constants\Invoice::IS_SHIPPED . ") " .
            "WHERE " .
            "si.settlement_id = " . $this->escape($settlementId) . " " .
            "AND si.active = true";
        // @formatter:on
        return $query;
    }
}
