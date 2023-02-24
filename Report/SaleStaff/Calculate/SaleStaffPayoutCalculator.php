<?php
/**
 * SAM-4633: Refactor sales staff report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 17, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\SaleStaff\Calculate;

use Admin;
use Sam\Core\Entity\Model\Invoice\Status\InvoiceStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Report\SaleStaff\Calculate\Dto\SaleStaffPayoutCalculatorData;
use Sam\Report\SaleStaff\Calculate\Load\DataLoader;
use Sam\Report\SaleStaff\Common\PayoutApplyStatusAwareTrait;
use Sam\Report\SaleStaff\Common\PayoutTypeAwareTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Constants\Admin\SaleStaffReportFormConstants;
use Sam\User\Load\UserLoader;
use Sam\User\Load\UserLoaderAwareTrait;
use User;

/**
 * Class SaleStaffPayoutCalculator
 * @package Sam\Report\SaleStaff\Calculate
 */
class SaleStaffPayoutCalculator extends CustomizableClass
{
    use AccountAwareTrait;
    use OptionalsTrait;
    use PayoutApplyStatusAwareTrait;
    use PayoutTypeAwareTrait;
    use UserLoaderAwareTrait;

    public const OP_ADMIN = 'admin'; // ?Admin
    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_SALES_COMMISSIONS = 'salesCommissions'; // array
    public const OP_USER = 'user'; // ?User

    // --- Internal values ---
    protected SaleStaffPayoutCalculatorData $saleStaffPayoutCalculationData;
    protected ?DataLoader $dataLoader = null;

    // --- Constructors ---

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string|null $payoutType
     * @param string|null $payoutApplyStatus
     * @param SaleStaffPayoutCalculatorData $dto
     * @param array $optionals
     * @return static
     */
    public function construct(
        ?string $payoutType,
        ?string $payoutApplyStatus,
        SaleStaffPayoutCalculatorData $dto,
        array $optionals = []
    ): static {
        $this->setPayoutType($payoutType);
        $this->setPayoutApplyStatus($payoutApplyStatus);
        $this->saleStaffPayoutCalculationData = $dto;
        $this->initOptionals($optionals);
        return $this;
    }

    // --- Main method ---

    /**
     * Payout calculation
     * @return float
     */
    public function calculate(): float
    {
        $amountPayout = $this->getPayoutType();
        $payout = 0.;
        $isPayout = $this->checkPayoutAvailable();
        $hammer = $this->saleStaffPayoutCalculationData->hammerPrice;
        $premium = $this->saleStaffPayoutCalculationData->buyersPremium;
        $total = $this->saleStaffPayoutCalculationData->total;
        if ($isPayout) {
            $amount = 0.;
            if ($amountPayout === SaleStaffReportFormConstants::PAT_HP) {
                $amount = $hammer;
            } elseif ($amountPayout === SaleStaffReportFormConstants::PAT_HPBP) {
                $amount = $hammer + $premium;
            } elseif ($amountPayout === SaleStaffReportFormConstants::PAT_TTL) {
                $amount = $total;
            }
            $payout = $this->calcPayoutAmount($amount);
        }
        return $payout;
    }

    // --- Internal methods ---

    /**
     * @return bool
     */
    protected function checkPayoutAvailable(): bool
    {
        $isPayout = false;
        $invoiceStatus = $this->saleStaffPayoutCalculationData->invoiceStatus;
        $applyStatus = $this->getPayoutApplyStatus();
        $invoiceStatusPureChecker = InvoiceStatusPureChecker::new();
        if (
            $applyStatus === SaleStaffReportFormConstants::PAIS_SHIPPED
            && $invoiceStatusPureChecker->isShipped($invoiceStatus)
        ) {
            $isPayout = true;
        } elseif (
            $applyStatus === SaleStaffReportFormConstants::PAIS_PAID_OR_SHIPPED
            && $invoiceStatusPureChecker->isPaidOrShipped($invoiceStatus)
        ) {
            $isPayout = true;
        } elseif ($applyStatus === SaleStaffReportFormConstants::PAIS_ANY) { // Any
            $isPayout = true;
        }
        return $isPayout;
    }

    /**
     * @param float $amount
     * @return float
     */
    protected function calcPayoutAmount(float $amount): float
    {
        $payout = 0.;
        $user = $this->fetchOptional(self::OP_USER);
        if ($user) {
            $admin = $this->fetchOptional(self::OP_ADMIN, [$user->Id]);
            if (
                $admin
                && $admin->SalesCommissionStepdown
            ) {
                $salesCommissions = (array)$this->fetchOptional(self::OP_SALES_COMMISSIONS, [$user->Id, $amount]);
                $payout = $this->calcTotalSalesCommission($salesCommissions, $amount);
            } else {
                $salesCommission = $this->fetchOptional(self::OP_SALES_COMMISSIONS, [$user->Id, $amount]);
                if ($salesCommission) {
                    $percent = $salesCommission[0]['percent'] / 100;
                    $payout = $amount * $percent;
                }
            }
        }
        return $payout;
    }

    /**
     * @return DataLoader
     */
    public function getDataLoader(): DataLoader
    {
        if ($this->dataLoader === null) {
            $this->dataLoader = DataLoader::new();
        }
        return $this->dataLoader;
    }

    /**
     * @param DataLoader $dataLoader
     * @return $this
     */
    public function setDataLoader(DataLoader $dataLoader): static
    {
        $this->dataLoader = $dataLoader;
        return $this;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $userId = $this->saleStaffPayoutCalculationData->salesStaff;
        $accountId = $this->saleStaffPayoutCalculationData->accountId;
        $isReadOnlyDb = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;

        $optionals[self::OP_USER] = $optionals[self::OP_USER]
            ?? static function () use ($userId, $isReadOnlyDb): ?User {
                return UserLoader::new()->load($userId, $isReadOnlyDb);
            };
        $optionals[self::OP_ADMIN] = $optionals[self::OP_ADMIN]
            ?? static function ($userId) use ($isReadOnlyDb): ?Admin {
                return UserLoader::new()->loadAdmin($userId, $isReadOnlyDb);
            };
        $optionals[self::OP_SALES_COMMISSIONS] = $optionals[self::OP_SALES_COMMISSIONS]
            ?? static function ($userId, $amount) use ($accountId): array {
                return DataLoader::new()->loadSalesCommissions($accountId, $userId, $amount);
            };
        $this->setOptionals($optionals);
    }

    /**
     * @param array $salesCommissions
     * @param float $amount
     * @return float
     */
    protected function calcTotalSalesCommission(array $salesCommissions, float $amount): float
    {
        $totalSalesCommission = 0.;
        foreach ($salesCommissions as $key => $salesCommission) {
            if ($key === 0) {
                $hpValue = $amount - $salesCommission['amount'];
                $percent = $salesCommission['percent'] / 100;
            } else {
                $hpValue = $salesCommissions[$key - 1]['amount'] - $salesCommission['amount'];
                $percent = $salesCommission['percent'] / 100;
            }
            $totalSalesCommission += ($percent * $hpValue);
        }
        return $totalSalesCommission;
    }
}
