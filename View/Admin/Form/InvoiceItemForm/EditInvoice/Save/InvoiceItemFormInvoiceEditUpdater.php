<?php
/**
 * SAM-10923: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract invoice General validation and save (#invoice-save-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Save;

use DateTime;
use QOptimisticLockingException;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Model\Invoice\Status\InvoiceStatusPureChecker;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\Legacy\Calculate\Summary\LegacyInvoiceSummaryCalculatorAwareTrait;
use Sam\Invoice\Common\Delete\Single\SingleInvoiceDeleterCreateTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentMethodManagerAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Validate\State\LotStateDetectorCreateTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceItem\InvoiceItemWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Common\InvoiceItemFormInvoiceEditChargeInput;
use Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Common\InvoiceItemFormInvoiceEditInput as Input;
use Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Common\InvoiceItemFormInvoiceEditPaymentInput;
use Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Save\InvoiceItemFormInvoiceEditUpdatingResult as Result;

/**
 * Class InvoiceItemFormInvoiceEditingUpdater
 * @package Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Save
 */
class InvoiceItemFormInvoiceEditUpdater extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use DateHelperAwareTrait;
    use DbConnectionTrait;
    use InvoiceAdditionalChargeManagerAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceItemWriteRepositoryAwareTrait;
    use InvoicePaymentManagerAwareTrait;
    use InvoicePaymentMethodManagerAwareTrait;
    use LegacyInvoiceSummaryCalculatorAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotStateDetectorCreateTrait;
    use NumberFormatterAwareTrait;
    use SingleInvoiceDeleterCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function update(Input $input): Result
    {
        $invoice = $input->invoice;
        $editorUserId = $input->editorUserId;
        $selectedInvoiceStatus = $input->selectedInvoiceStatus;
        $nf = $this->getNumberFormatter()->constructForInvoice($invoice->AccountId);

        $invoiceStatusPureChecker = InvoiceStatusPureChecker::new();
        if ($invoiceStatusPureChecker->isOpen($selectedInvoiceStatus)) {
            $invoice->toOpen();
        } elseif ($invoiceStatusPureChecker->isPending($selectedInvoiceStatus)) {
            $invoice->toPending();
        } elseif ($invoiceStatusPureChecker->isPaid($selectedInvoiceStatus)) {
            $invoice->toPaid();
        } elseif ($invoiceStatusPureChecker->isShipped($selectedInvoiceStatus)) {
            $invoice->toShipped();
        } elseif ($invoiceStatusPureChecker->isCanceled($selectedInvoiceStatus)) {
            $invoice->toCanceled();
        } elseif ($invoiceStatusPureChecker->isDeleted($selectedInvoiceStatus)) {
            /**
             * Don't mark invoice as "deleted" here, because we will delete it later with help of SingleInvoiceDeleter.
             * Otherwise, invoice entity not able to pass validation of SingleInvoiceDeleter service (SAM-7616).
             */
        }
        $invoice->Note = $input->note;
        $invoice->Shipping = $nf->parseMoney($input->shippingAmountFormatted);
        $invoice->ShippingNote = $input->shippingNote;
        $invoice->CashDiscount = $input->isCashDiscount;
        $invoice->ExcludeInThreshold = $input->isExcludeInThreshold;
        if ($invoice->InternalNote !== $input->internalNote) {
            $invoice->InternalNoteModified = (new DateTime())->setTimestamp((int)date("U"));
            $invoice->InternalNote = $input->internalNote;
        }

        $this->getDb()->TransactionBegin();
        try {
            $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);

            $result = Result::new()->construct();
            $result = $this->processReleased($input, $result);
            $result = $this->processPaymentAndCharges($input, $result);

            if ($invoiceStatusPureChecker->isDeleted($selectedInvoiceStatus)) {
                $this->processDeleted($input);
            }
        } catch (QOptimisticLockingException) {
            $this->getDb()->TransactionRollback();
            $result = Result::new()->construct();
            $result->addError(Result::ERR_OLC_FAILED);
            return $result;
        }

        $this->getDb()->TransactionCommit();
        return $result;
    }

    /**
     * @param int $invoiceId
     * @param bool $unsoldLot
     * @param int $systemAccountId
     * @param int $editorUserId
     */
    public function deleteSingleInvoice(
        int $invoiceId,
        bool $unsoldLot,
        int $systemAccountId,
        int $editorUserId
    ): void {
        $singleInvoiceDeleter = $this->createSingleInvoiceDeleter()->construct(
            $invoiceId,
            $unsoldLot,
            $systemAccountId, // system account, not invoice account
            $editorUserId
        );
        if ($singleInvoiceDeleter->validate()) {
            $singleInvoiceDeleter->delete($editorUserId);
        } else {
            log_error(($singleInvoiceDeleter->errorMessage()));
        }
    }

    protected function processReleased(Input $input, Result $result): Result
    {
        /**
         * Cycle through checkbox control if release feature
         * is checked or not then save it to db.
         */
        $isUnsold = false;
        $hasLotMarkedAsReleased = false;
        $releasedInvoiceItemIds = [];
        foreach ($input->invoiceReleaseStatuses as $invoiceItemId => $isInvoiceRelease) {
            $invoiceItem = $this->getInvoiceItemLoader()->load($invoiceItemId, true);
            if ($invoiceItem) {
                if ($isInvoiceRelease) {
                    $lotItem = $this->getLotItemLoader()->load($invoiceItem->LotItemId, true);
                    if (
                        $lotItem
                        && (
                            $lotItem->hasHammerPrice()
                            || $lotItem->hasWinningBidder()
                            || $lotItem->hasSaleSoldAuction()
                        )
                    ) {
                        /**
                         * Let's check if there is an item that can be mark unsold
                         * For timed auctions: If the lot end date is past, then mark the lot as "Unsold"
                         * For live: If the auction is closed, mark it as "Unsold"
                         **/
                        if (!$isUnsold) {
                            $auctionLot = $this->getAuctionLotLoader()->load(
                                $invoiceItem->LotItemId,
                                $invoiceItem->AuctionId
                            );
                            if ($auctionLot) {
                                $auction = $this->getAuctionLoader()->load($invoiceItem->AuctionId);
                                if ($auction) {
                                    if ($auction->isTimed()) {
                                        $isLotEnded = $this->createLotStateDetector()->isLotEnded($auctionLot);
                                        if ($isLotEnded) {
                                            $isUnsold = true;
                                        }
                                    } elseif ($auction->isClosed()) {
                                        $isUnsold = true;
                                    }
                                }
                            }
                        }
                        $releasedInvoiceItemIds[] = $invoiceItem->Id;
                        $hasLotMarkedAsReleased = true;
                    }
                }
                $invoiceItem->Release = $isInvoiceRelease;
                $this->getInvoiceItemWriteRepository()->saveWithModifier($invoiceItem, $input->editorUserId);
            }
        }

        return $result
            ->enableHasLotMarkedAsReleased($hasLotMarkedAsReleased)
            ->enableUnsold($isUnsold)
            ->setReleasedInvoiceItemIds($releasedInvoiceItemIds);
    }

    protected function processPaymentAndCharges(Input $input, Result $result): Result
    {
        $invoiceId = $input->invoice->Id;
        $paymentAndControlMap = $input->paymentAndChargeMap; // TODO: decouple
        $paymentAndChargeMap = $input->paymentAndChargeMap;  // TODO: decouple

        // (!) Temporal coupling: Must be executed first before payment
        $remainingChargeIds = array_map(fn(InvoiceItemFormInvoiceEditChargeInput $chargeInput) => $chargeInput->invoiceAdditionalId, $input->charges);
        $this->getInvoiceAdditionalChargeManager()->deleteForInvoice($invoiceId, array_filter($remainingChargeIds));

        if ($input->paymentDetails) {
            $this->addPaymentDetails($input);
        } else {
            $paymentAndControlMap = $this->rebuildPayments($input);
        }

        $this->addAdditionalCharges($input, $paymentAndControlMap, $paymentAndChargeMap);

        if (
            !$result->hasLotMarkedAsReleased
            && in_array($input->selectedInvoiceStatus, Constants\Invoice::$openInvoiceStatuses, true) //  If there is any release checked.
        ) {
            $this->getInvoicePaymentMethodManager()->savePaymentMethods(
                $invoiceId,
                array_flip($input->paymentMethods),
                $input->editorUserId
            );

            $this->getLegacyInvoiceSummaryCalculator()->recalculate($invoiceId, $input->editorUserId);
        }
        return $result;
    }

    protected function addPaymentDetails(Input $input): void
    {
        $invoicePaymentManager = $this->getInvoicePaymentManager();
        [$amountParam, $noteParam, $date, $paymentMethodId] = $input->paymentDetails;
        if ($noteParam === Constants\Payment::PAYMENT_NOTE_DEF) {
            $noteParam = '';
        }
        $invoicePaymentManager->addFull(
            $input->invoice->Id,
            $input->invoice->AccountId,
            $paymentMethodId,
            $amountParam,
            $input->editorUserId,
            $noteParam,
            $this->getDateHelper()->convertSysToUtc($date)
        );
    }

    protected function rebuildPayments(Input $input): array
    {
        $nf = $this->getNumberFormatter();
        $invoicePaymentManager = $this->getInvoicePaymentManager();
        $remainingPaymentIds = array_map(fn(InvoiceItemFormInvoiceEditPaymentInput $paymentInput) => $paymentInput->paymentId, $input->payments);
        $invoicePaymentManager->deleteForInvoice($input->invoice->Id, array_filter($remainingPaymentIds), $input->editorUserId);
        // Note, that $paymentAndControlMap is re-collected again
        $paymentAndControlMap = [];
        foreach ($input->payments as $paymentInput) {
            $creditCardId = $paymentInput->paymentMethodId === Constants\Payment::PM_CC
                ? $paymentInput->creditCardId
                : null;
            $amount = $nf->parseMoney($paymentInput->amountFormatted);
            $note = $paymentInput->note;

            // TODO: we must avoid this logic, and display "note" as placeholder of field instead of input's value
            if ($note === Constants\Payment::PAYMENT_NOTE_DEF) {
                $note = '';
            }

            if (
                $paymentInput->paymentMethodId
                && Floating::neq($amount, 0.)
            ) {
                if ($paymentInput->paymentId) {
                    $payment = $invoicePaymentManager->updateFull(
                        $paymentInput->paymentId,
                        $input->invoice->Id,
                        $input->invoice->AccountId,
                        $paymentInput->paymentMethodId,
                        $amount,
                        $input->editorUserId,
                        $note,
                        $this->getDateHelper()->convertSysToUtc($paymentInput->date),
                        null,
                        $creditCardId
                    );
                } else {
                    $payment = $invoicePaymentManager->addFull(
                        $input->invoice->Id,
                        $input->invoice->AccountId,
                        $paymentInput->paymentMethodId,
                        $amount,
                        $input->editorUserId,
                        $note,
                        $this->getDateHelper()->convertSysToUtc($paymentInput->date),
                        null,
                        $creditCardId
                    );
                }

                if ($payment->PaymentMethodId === Constants\Payment::PM_CC) {
                    $paymentAndControlMap[$payment->Id] = $paymentInput->index;
                }
            }
        }
        return $paymentAndControlMap;
    }

    protected function addAdditionalCharges(Input $input, array $paymentAndControlMap, array $paymentAndChargeMap): void
    {
        $controlAndPaymentMap = (count($paymentAndControlMap) > 0)
            ? array_flip($paymentAndControlMap) : [];
        $chargeAndPaymentMap = (count($paymentAndChargeMap) > 0)
            ? array_flip($paymentAndChargeMap)
            : [];

        $invoiceId = $input->invoice->Id;
        $editorUserId = $input->editorUserId;
        $nf = $this->getNumberFormatter();

        foreach ($input->charges as $chargeInput) {
            $chargeIndex = $chargeInput->index;
            $amount = $nf->parseMoney($chargeInput->amountFormatted);
            $couponId = (int)$chargeInput->couponId;
            $couponCode = $chargeInput->couponCode;
            $name = trim($chargeInput->name);

            // These values are not actual in legacy invoicing
            $note = '';
            $taxSchemaId = null;

            if ($name === '') {
                continue;
            }
            $paymentIndex = $chargeAndPaymentMap[$chargeIndex] ?? null;
            $paymentId = $controlAndPaymentMap[$paymentIndex] ?? null;
            // Payment was deleted
            if ($paymentIndex && !$paymentId) {
                continue;
            }

            if ($chargeInput->invoiceAdditionalId) {
                $this->getInvoiceAdditionalChargeManager()->update(
                    $chargeInput->invoiceAdditionalId,
                    $invoiceId,
                    $name,
                    $amount,
                    $editorUserId,
                    $note,
                    $taxSchemaId,
                    $couponId,
                    $couponCode,
                    $paymentId
                );
            } else {
                $this->getInvoiceAdditionalChargeManager()->add(
                    Constants\Invoice::IA_EXTRA_CHARGE,
                    $invoiceId,
                    $name,
                    $amount,
                    $editorUserId,
                    '',
                    null,
                    $couponId,
                    $couponCode,
                    $paymentId
                );
            }
        }
    }

    protected function processDeleted(Input $input): void
    {
        if ($this->cfg()->get('core->admin->invoice->disableMarkUnsoldOnDelete')) {
            $this->deleteSingleInvoice($input->invoice->Id, false, $input->systemAccountId, $input->editorUserId);
            $this->getInvoicePaymentMethodManager()->savePaymentMethods(
                $input->invoice->Id,
                array_flip($input->paymentMethods),
                $input->editorUserId
            );
        }
    }
}
