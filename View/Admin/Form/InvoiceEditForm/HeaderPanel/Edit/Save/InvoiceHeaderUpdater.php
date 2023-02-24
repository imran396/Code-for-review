<?php
/**
 * SAM-10995: Stacked Tax. New Invoice Edit page: Initial layout and header section
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceEditForm\HeaderPanel\Edit\Save;

use DateTime;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Entity\Model\Invoice\Status\InvoiceStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;
use Sam\Invoice\Common\Load\InvoiceAuctionLoaderCreateTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentMethodManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceAuction\InvoiceAuctionWriteRepositoryAwareTrait;
use Sam\View\Admin\Form\InvoiceEditForm\HeaderPanel\Edit\Dto\InvoiceHeaderInputDto;

/**
 * Class InvoiceHeaderUpdater
 * @package Sam\View\Admin\Form\InvoiceEditForm\HeaderPanel\Edit\Save
 */
class InvoiceHeaderUpdater extends CustomizableClass
{
    use DateHelperAwareTrait;
    use EntityFactoryCreateTrait;
    use InvoiceAuctionLoaderCreateTrait;
    use InvoiceAuctionWriteRepositoryAwareTrait;
    use InvoiceLoaderAwareTrait;
    use InvoicePaymentMethodManagerAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function save(InvoiceHeaderInputDto $input, int $invoiceId, int $editorUserId): void
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId);
        if (!$invoice) {
            throw CouldNotFindInvoice::withId($invoiceId);
        }

        $invoiceDate = null;
        if ($input->invoiceDate) {
            $invoiceDate = clone $input->invoiceDate;
            $invoiceDate = $invoiceDate->setTime(12, 0);
            $invoiceDate = $this->getDateHelper()->convertSysToUtc($invoiceDate);
        }
        $invoice->InvoiceDate = $invoiceDate;
        $invoice->InvoiceNo = (int)$input->invoiceNo;
        $invoice->ExcludeInThreshold = $input->excludeInThreshold;
        if (!InvoiceStatusPureChecker::new()->isDeleted($input->status)) {
            /**
             * Don't mark invoice as "deleted" here, because we will delete it later with help of SingleInvoiceDeleter.
             * Otherwise, invoice entity not able to pass validation of SingleInvoiceDeleter service (SAM-7616).
             */
            $invoice->InvoiceStatusId = $input->status;
        }
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $editorUserId);
        $this->getInvoicePaymentMethodManager()->savePaymentMethods($invoiceId, $input->paymentMethods, $editorUserId);
        $this->updateAuctionDates($input->auctionDates, $invoiceId, $editorUserId);
    }

    /**
     * @param array<DateTime|null> $auctionDates
     * @param int $invoiceId
     * @param int $editorUserId
     */
    protected function updateAuctionDates(array $auctionDates, int $invoiceId, int $editorUserId): void
    {
        foreach ($auctionDates as $auctionId => $auctionDate) {
            $invoiceAuction = $this->createInvoiceAuctionLoader()->load($invoiceId, $auctionId);
            if (!$invoiceAuction) {
                $invoiceAuction = $this->createEntityFactory()->invoiceAuction();
                $invoiceAuction->InvoiceId = $invoiceId;
                $invoiceAuction->AuctionId = $auctionId;
            }

            if ($auctionDate) {
                $invoiceAuction->SaleDate = $this->getDateHelper()->convertTimezone(
                    $auctionDate,
                    $invoiceAuction->TimezoneLocation ?: 'UTC',
                    'UTC'
                );
            }

            $this->getInvoiceAuctionWriteRepository()->saveWithModifier($invoiceAuction, $editorUserId);
        }
    }
}
