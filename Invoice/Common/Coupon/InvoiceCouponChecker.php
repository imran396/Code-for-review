<?php
/**
 * Checking functionality for coupon applying to invoice
 *
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           26.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Common\Coupon;

use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Coupon\Validate\CouponExistenceCheckerAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Invoice\Common\Calculate\Basic\AnyInvoiceCalculatorCreateTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceAdditional\InvoiceAdditionalReadRepositoryCreateTrait;

/**
 * Class InvoiceCouponChecker
 * @package Sam\Invoice\Common\Coupon
 */
class InvoiceCouponChecker extends CustomizableClass
{
    use AnyInvoiceCalculatorCreateTrait;
    use CurrentDateTrait;
    use CouponExistenceCheckerAwareTrait;
    use InvoiceAdditionalReadRepositoryCreateTrait;
    use LotCategoryLoaderAwareTrait;
    use InvoiceLoaderAwareTrait;
    use InvoiceItemLoaderAwareTrait;

    public const OP_BALANCE_DUE = OptionalKeyConstants::KEY_BALANCE_DUE; //float

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Checks, if coupon application to this invoice is allowed and available:
     * - any other coupon wasn't applied to invoice,
     * - there is active coupons allowed to be applied to invoice.
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @param array $optionals [
     *   self::OP_BALANCE_DUE => float
     * ]
     * @return bool
     */
    public function isAvailable(int $invoiceId, bool $isReadOnlyDb = false, array $optionals = []): bool
    {
        $isAvailableCouponForInvoice = $this->existAvailableCouponForInvoice($invoiceId, $isReadOnlyDb, $optionals);
        $isAlreadyAppliedCouponOnInvoice = $this->existAlreadyAppliedCouponOnInvoice($invoiceId, $isReadOnlyDb);
        $isEnabled = $isAvailableCouponForInvoice
            && !$isAlreadyAppliedCouponOnInvoice;
        return $isEnabled;
    }

    /**
     * Checks, if active coupon exists for invoice.
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @param array $optionals [
     *  self::OP_BALANCE_DUE => float
     * ]
     * @return bool
     */
    protected function existAvailableCouponForInvoice(int $invoiceId, bool $isReadOnlyDb = false, array $optionals = []): bool
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            log_error(
                'Available invoice not found for coupon checking'
                . composeSuffix(['i' => $invoiceId])
            );
            return false;
        }

        $balanceDue = $optionals[self::OP_BALANCE_DUE] ?? $this->createAnyInvoiceCalculator()->calcRoundedBalanceDue($invoice);
        if (Floating::lteq($balanceDue, 0)) {
            return false;
        }

        $currentDateIso = $this->getCurrentDateUtcIso();
        $auctionIds = $this->getInvoiceItemLoader()->loadInvoicedAuctionIds($invoiceId, $isReadOnlyDb);
        $lotCategoryIds = $this->getLotCategoryLoader()->loadCategoryIdsWithAncestorsForInvoice($invoiceId, $isReadOnlyDb);

        $isFoundAvailableCoupon = $this->getCouponExistenceChecker()->existAvailableCoupon(
            $invoice->AccountId,
            $balanceDue,
            $currentDateIso,
            $invoice->BidderId,
            $auctionIds,
            $lotCategoryIds,
            $isReadOnlyDb
        );
        return $isFoundAvailableCoupon;
    }

    /**
     * Checks, if any coupon was previously applied to invoice
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    protected function existAlreadyAppliedCouponOnInvoice(int $invoiceId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->createInvoiceAdditionalReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->skipCouponId(null)
            ->exist();
        return $isFound;
    }
}
