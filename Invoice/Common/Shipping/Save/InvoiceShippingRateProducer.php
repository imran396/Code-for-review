<?php
/**
 * Shipping rate saving service in Invoice
 *
 * SAM-3222: AuctionInc Shipping calculation missing on invoice auto-generate (timed online and buy now)
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           20 March, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Shipping\Save;

use InvoiceItem;
use Sam\Core\Service\CustomizableClass;
use Invoice;
use Sam\Core\Constants;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Shipping\AuctionInc\AuctionInc;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class InvoiceShippingRateProducer
 * @package Sam\Invoice\Common\Shipping\Save
 */
class InvoiceShippingRateProducer extends CustomizableClass
{
    use InvoiceAdditionalChargeManagerAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use UserLoaderAwareTrait;

    /** @var string */
    private string $errorMessage = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Store shipping rate and surcharges in Invoice.
     *
     * @param Invoice $invoice
     * @param array $rateResultData
     * @param int $editorUserId
     * @return void
     */
    public function save(Invoice $invoice, array $rateResultData, int $editorUserId): void
    {
        $n = "\n";
        $label = 'Shipping & Handling';
        $amount = $rateResultData['Rate'] ?? null;
        if (!$amount) {
            log_error(
                'Shipping type service charge not added, because "Rate" field result is undefined'
                . composeSuffix(['i' => $invoice->Id] + $rateResultData)
            );
            return;
        }

        $note = 'ServiceName: ' . ($rateResultData['ServiceName'] ?? '') . $n .
            'Rate: ' . ($rateResultData['CarrierRate'] ?? '') . $n .
            'Surcharges: ' . ($rateResultData['Surcharges'] ?? '') . $n .
            'FuelSurcharges: ' . ($rateResultData['FuelSurcharges'] ?? '') . $n .
            'HandlingFees: ' . ($rateResultData['HandlingFees'] ?? '') . $n .
            'InsuranceCharges: ' . ($rateResultData['InsuranceCharges'] ?? '');
        $this->getInvoiceAdditionalChargeManager()->add(
            Constants\Invoice::IA_SHIPPING,
            $invoice->Id,
            $label,
            $amount,
            $editorUserId,
            $note
        );
    }

    /**
     * Find shipping rate and save them in Invoice.
     *
     * @param Invoice $invoice
     * @param int $editorUserId
     */
    public function update(Invoice $invoice, int $editorUserId): void
    {
        $this->errorMessage = '';
        $invoiceItems = $this->getInvoiceItemLoader()->loadByInvoiceId($invoice->Id);
        $userShipping = $this->getUserLoader()->loadUserShippingOrCreate($invoice->BidderId);
        $auctionInc = AuctionInc::new()->construct($invoice->AccountId);
        if (
            $userShipping->CarrierMethod === Constants\CarrierService::M_PICKUP
            || (
                $userShipping->Country
                && $userShipping->Zip
            )
        ) {
            if ($auctionInc) {
                $rateResultData = $auctionInc->process($invoice, $invoiceItems, $editorUserId);
                if (
                    !empty($rateResultData)
                    && !isset($rateResultData['ErrorList'])
                ) {
                    $this->save($invoice, $rateResultData, $editorUserId);
                } else {
                    $this->setErrorMessage($rateResultData, $auctionInc, $invoiceItems);
                }
            } else {
                log_warning(
                    'AuctionInc account is not defined NO shipping rate applied to invoice'
                    . composeSuffix(['i' => $invoice->Id])
                );
            }
        }
    }

    /**
     * @param array $rateResultData
     * @param AuctionInc $auctionIncManager
     * @param InvoiceItem[] $invoiceItems
     * @return static
     */
    protected function setErrorMessage(array $rateResultData, AuctionInc $auctionIncManager, array $invoiceItems): static
    {
        $errorMsg = isset($rateResultData['ErrorList']) ? $rateResultData['ErrorList'][0]["Message"] : 'n/a';
        if ($rateResultData['ErrorList'][0]["Code"] === "510") {
            $errorMsg = $auctionIncManager->getError($invoiceItems, $errorMsg);
        }
        $this->errorMessage = 'error: ' . (isset($rateResultData['ErrorList']) ? $errorMsg : 'n/a');
        return $this;
    }

    /**
     * Get the last error message
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage ?? '';
    }
}
