<?php
/**
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

use Coupon;
use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Coupon\Load\CouponLoaderAwareTrait;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\Common\Calculate\Basic\AnyInvoiceCalculator;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;
use Sam\Invoice\Legacy\Calculate\Basic\LegacyInvoiceCalculatorAwareTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\CouponAwareTrait;
use Sam\Storage\Entity\AwareTrait\InvoiceAwareTrait;

/**
 * Class InvoiceCouponApplier
 * @package Sam\Invoice\Common\Coupon
 */
class InvoiceCouponApplier extends CustomizableClass
{
    use CouponAwareTrait;
    use CouponLoaderAwareTrait;
    use CurrentDateTrait;
    use InvoiceAdditionalChargeManagerAwareTrait;
    use InvoiceAwareTrait;
    use LegacyInvoiceCalculatorAwareTrait;
    use InvoiceCouponCheckerAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use TranslatorAwareTrait;

    public const ERR_CODE_REQUIRED = 1;
    public const ERR_CODE_INVALID = 2;
    public const ERR_INVOICE_NOT_AVAILABLE = 3;
    public const ERR_COUPON_NOT_AVAILABLE = 4;

    /** @var string */
    protected string $couponCode = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Override trait's method, because we want to overload initialization logic of default value
     * @return Coupon|null
     */
    public function getCoupon(): ?Coupon
    {
        $coupon = $this->getCouponAggregate()->getCoupon();
        if (!$coupon) {
            $coupon = $this->loadAvailableCouponForInvoice();
            $this->setCoupon($coupon);
        }
        return $coupon;
    }

    /**
     * Applies validated coupon code to current invoice
     * @param int $editorUserId
     */
    public function apply(int $editorUserId): void
    {
        $coupon = $this->getCoupon();
        if (!$coupon) {
            $message = "Available coupon not found for applying to invoice"
                . composeSuffix(['i' => $this->getInvoiceId(), 'code' => $this->getCouponCode()]);
            log_error($message);
            throw new InvalidArgumentException($message);
        }

        switch ($coupon->CouponType) {
            case Constants\Coupon::FREE_SHIPPING:
                $charges = 0;
                $shipping = (float)$this->getInvoice()->Shipping;
                if ($coupon->WaiveAdditionalCharges) {
                    $charges = $this
                        ->getLegacyInvoiceCalculator()
                        ->calcTotalAdditionalCharges($this->getInvoiceId());
                }

                $amount = $shipping + $charges;
                $amount *= -1;
                $this->getInvoiceAdditionalChargeManager()->add(
                    Constants\Invoice::IA_COUPON_FREE_SHIPPING,
                    $this->getInvoiceId(),
                    $coupon->Title,
                    $amount,
                    $editorUserId,
                    '',
                    null,
                    $coupon->Id,
                    $coupon->Code
                );
                break;

            case Constants\Coupon::FIXED_AMOUNT:
                $amount = $coupon->FixedAmountOff * -1;
                $this->getInvoiceAdditionalChargeManager()->add(
                    Constants\Invoice::IA_COUPON_FIXED_AMOUNT,
                    $this->getInvoiceId(),
                    $coupon->Title,
                    $amount,
                    $editorUserId,
                    '',
                    null,
                    $coupon->Id,
                    $coupon->Code
                );
                break;

            case Constants\Coupon::PERCENTAGE:
                $grandTotal = $this->getLegacyInvoiceCalculator()->calcGrandTotal($this->getInvoiceId());
                $amount = ($grandTotal * ($coupon->PercentageOff / 100)) * -1;
                $this->getInvoiceAdditionalChargeManager()->add(
                    Constants\Invoice::IA_COUPON_PERCENTAGE,
                    $this->getInvoiceId(),
                    $coupon->Title,
                    $amount,
                    $editorUserId,
                    '',
                    null,
                    $coupon->Id,
                    $coupon->Code
                );
                break;
        }
    }

    /**
     * Validates object
     * @return bool
     */
    public function validate(): bool
    {
        $collector = $this->getResultStatusCollector()->clear();
        $tr = $this->getTranslator();

        if ($this->getCouponCode() === '') {
            $langCouponRequired = $tr->translate('MYINVOICES_COUPON_CODE_REQUIRED', 'myinvoices');
            $collector->addError(self::ERR_CODE_REQUIRED, $langCouponRequired);
            return false;
        }

        $coupon = $this->getCoupon();
        if (!$coupon) {
            $langCouponInvalid = $tr->translate('MYINVOICES_COUPON_CODE_INVALID', 'myinvoices');
            $collector->addError(self::ERR_CODE_INVALID, $langCouponInvalid);
            return false;
        }

        if (!in_array($this->getInvoice()->InvoiceStatusId, Constants\Invoice::$publicAvailableInvoiceStatuses, true)) {
            $langInvoiceNotAvailable = $tr->translate('MYINVOICES_COUPON_INVOICE_NOT_AVAILABLE', 'myinvoices');
            $collector->addError(self::ERR_INVOICE_NOT_AVAILABLE, $langInvoiceNotAvailable);
            return false;
        }

        if (!$this->getInvoiceCouponChecker()->isAvailable($this->getInvoiceId())) {
            $langCouponExpired = $tr->translate('MYINVOICES_COUPON_NOT_AVAILABLE', 'myinvoices');
            $collector->addError(self::ERR_COUPON_NOT_AVAILABLE, $langCouponExpired);
            return false;
        }

        return !$collector->hasError();
    }

    /**
     * @return string
     */
    public function getCouponCode(): string
    {
        return (string)$this->couponCode;
    }

    /**
     * @param string $couponCode
     * @return static
     */
    public function setCouponCode(string $couponCode): static
    {
        $this->couponCode = $couponCode;
        return $this;
    }

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        $message = $this->getResultStatusCollector()->getConcatenatedErrorMessage();
        return trim($message);
    }

    /**
     * @return Coupon|null
     */
    protected function loadAvailableCouponForInvoice(): ?Coupon
    {
        $invoice = $this->getInvoice();
        if (!$invoice) {
            throw CouldNotFindInvoice::withId($this->getInvoiceId());
        }
        $auctionIds = $this->getInvoiceItemLoader()->loadInvoicedAuctionIds($this->getInvoiceId());
        $balanceDue = AnyInvoiceCalculator::new()->calcRoundedBalanceDue($invoice);
        $dateNow = $this->getCurrentDateUtc();
        $invDate = $dateNow->format(Constants\Date::ISO);
        $lotCategoryIds = $this->getLotCategoryLoader()->loadCategoryIdsWithAncestorsForInvoice($this->getInvoiceId());
        $coupon = $this->getCouponLoader()->loadAvailableCoupon(
            $this->getInvoice()->AccountId,
            $balanceDue,
            $invDate,
            $this->getInvoice()->BidderId,
            $this->getCouponCode(),
            $auctionIds,
            $lotCategoryIds,
        );
        return $coupon;
    }
}
