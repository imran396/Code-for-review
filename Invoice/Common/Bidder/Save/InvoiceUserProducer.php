<?php
/**
 *
 * SAM-4554: Move Invoice_Bidder logic to InvoiceUserLoader, InvoiceUserProducer
 *
 * Class implements functionality for creating invoice bidder billing and shipping info
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/13/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Bidder\Save;

use InvalidArgumentException;
use InvoiceUser;
use InvoiceUserBilling;
use InvoiceUserShipping;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceUser\InvoiceUserWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceUserBilling\InvoiceUserBillingWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceUserShipping\InvoiceUserShippingWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class InvoiceUserProducer
 * @package Sam\Invoice\Common\Bidder\Save
 */
class InvoiceUserProducer extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use InvoiceLoaderAwareTrait;
    use InvoiceUserBillingWriteRepositoryAwareTrait;
    use InvoiceUserLoaderAwareTrait;
    use InvoiceUserShippingWriteRepositoryAwareTrait;
    use InvoiceUserWriteRepositoryAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create user billing object for invoice with coping data from winning user
     * @param int $invoiceId target invoice id
     * @param bool $isReadOnlyDb
     * @return InvoiceUserBilling
     */
    public function createInvoiceUserBillingAndInitByWinningUser(
        int $invoiceId,
        bool $isReadOnlyDb = false
    ): InvoiceUserBilling {
        $invoice = $this->getInvoiceLoader()->load($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            $message = "Available invoice not found, when creating invoice user billing record"
                . composeSuffix(['i' => $invoiceId]);
            log_error($message);
            throw new InvalidArgumentException($message);
        }
        $invoiceBilling = $this->createEntityFactory()->invoiceUserBilling();
        $invoiceBilling->InvoiceId = $invoice->Id;
        $sourceUserBilling = $this->getUserLoader()->loadUserBillingOrCreate($invoice->BidderId, $isReadOnlyDb);
        $invoiceBilling->CompanyName = $sourceUserBilling->CompanyName;
        $invoiceBilling->FirstName = $sourceUserBilling->FirstName;
        $invoiceBilling->LastName = $sourceUserBilling->LastName;
        $invoiceBilling->Phone = $sourceUserBilling->Phone;
        $invoiceBilling->Fax = $sourceUserBilling->Fax;
        $invoiceBilling->Country = $sourceUserBilling->Country;
        $invoiceBilling->Address = $sourceUserBilling->Address;
        $invoiceBilling->Address2 = $sourceUserBilling->Address2;
        $invoiceBilling->Address3 = $sourceUserBilling->Address3;
        $invoiceBilling->City = $sourceUserBilling->City;
        $invoiceBilling->State = $sourceUserBilling->State;
        $invoiceBilling->Zip = $sourceUserBilling->Zip;
        $invoiceBilling->Email = $sourceUserBilling->Email;
        return $invoiceBilling;
    }

    /**
     * Create and Persist user billing object for invoice with coping data from winning user
     * @param int $invoiceId target invoice id
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return InvoiceUserBilling
     */
    public function createPersistedInvoiceUserBillingAndInitByWinningUser(
        int $invoiceId,
        int $editorUserId,
        bool $isReadOnlyDb = false
    ): InvoiceUserBilling {
        $invoiceBilling = $this->createInvoiceUserBillingAndInitByWinningUser($invoiceId, $isReadOnlyDb);
        $this->getInvoiceUserBillingWriteRepository()->saveWithModifier($invoiceBilling, $editorUserId);
        return $invoiceBilling;
    }

    /**
     * Create user billing object for invoice with coping data from existing invoice user billing object
     * @param int $targetInvoiceId target invoice id
     * @param int $sourceInvoiceId source to copy data
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return InvoiceUserBilling
     */
    public function createInvoiceUserBillingAndInitByInvoice(
        int $targetInvoiceId,
        int $sourceInvoiceId,
        int $editorUserId,
        bool $isReadOnlyDb = false
    ): InvoiceUserBilling {
        $invoiceBilling = $this->createEntityFactory()->invoiceUserBilling();
        $invoiceBilling->InvoiceId = $targetInvoiceId;
        $sourceInvoiceBilling = $this->getInvoiceUserLoader()
            ->loadInvoiceUserBillingOrCreatePersisted($sourceInvoiceId, $editorUserId, $isReadOnlyDb);
        $invoiceBilling->CompanyName = $sourceInvoiceBilling->CompanyName;
        $invoiceBilling->FirstName = $sourceInvoiceBilling->FirstName;
        $invoiceBilling->LastName = $sourceInvoiceBilling->LastName;
        $invoiceBilling->Phone = $sourceInvoiceBilling->Phone;
        $invoiceBilling->Fax = $sourceInvoiceBilling->Fax;
        $invoiceBilling->Country = $sourceInvoiceBilling->Country;
        $invoiceBilling->Address = $sourceInvoiceBilling->Address;
        $invoiceBilling->Address2 = $sourceInvoiceBilling->Address2;
        $invoiceBilling->Address3 = $sourceInvoiceBilling->Address3;
        $invoiceBilling->City = $sourceInvoiceBilling->City;
        $invoiceBilling->State = $sourceInvoiceBilling->State;
        $invoiceBilling->Zip = $sourceInvoiceBilling->Zip;
        $invoiceBilling->Email = $sourceInvoiceBilling->Email;
        return $invoiceBilling;
    }

    /**
     * Create and Persist user billing object for invoice with coping data from existing invoice user billing object
     * @param int $targetInvoiceId target invoice id
     * @param int $sourceInvoiceId source to copy data
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return InvoiceUserBilling
     */
    public function createPersistedInvoiceUserBillingAndInitByInvoice(
        int $targetInvoiceId,
        int $sourceInvoiceId,
        int $editorUserId,
        bool $isReadOnlyDb = false
    ): InvoiceUserBilling {
        $invoiceBilling = $this->createInvoiceUserBillingAndInitByInvoice(
            $targetInvoiceId,
            $sourceInvoiceId,
            $editorUserId,
            $isReadOnlyDb
        );
        $this->getInvoiceUserBillingWriteRepository()->saveWithModifier($invoiceBilling, $editorUserId);
        return $invoiceBilling;
    }

    // --- InvoiceUserShipping ---

    /**
     * Create user shipping object for invoice with coping data from winning user shipping object
     * @param int $invoiceId target invoice id
     * @param bool $isReadOnlyDb
     * @return InvoiceUserShipping
     */
    public function createInvoiceUserShippingAndInitByWinningUser(
        int $invoiceId,
        bool $isReadOnlyDb = false
    ): InvoiceUserShipping {
        $invoice = $this->getInvoiceLoader()->load($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            $message = "Available invoice not found, when creating invoice user shipping record"
                . composeSuffix(['i' => $invoiceId]);
            log_error($message);
            throw new InvalidArgumentException($message);
        }
        $invoiceShipping = $this->createEntityFactory()->invoiceUserShipping();
        $invoiceShipping->InvoiceId = $invoice->Id;
        $sourceUserShipping = $this->getUserLoader()->loadUserShippingOrCreate($invoice->BidderId);
        $invoiceShipping->CompanyName = $sourceUserShipping->CompanyName;
        $invoiceShipping->FirstName = $sourceUserShipping->FirstName;
        $invoiceShipping->LastName = $sourceUserShipping->LastName;
        $invoiceShipping->Phone = $sourceUserShipping->Phone;
        $invoiceShipping->Fax = $sourceUserShipping->Fax;
        $invoiceShipping->Country = $sourceUserShipping->Country;
        $invoiceShipping->Address = $sourceUserShipping->Address;
        $invoiceShipping->Address2 = $sourceUserShipping->Address2;
        $invoiceShipping->Address3 = $sourceUserShipping->Address3;
        $invoiceShipping->City = $sourceUserShipping->City;
        $invoiceShipping->State = $sourceUserShipping->State;
        $invoiceShipping->Zip = $sourceUserShipping->Zip;
        return $invoiceShipping;
    }

    /**
     * Create and Persist user shipping object for invoice coping data from bidder's shipping object
     * @param int $invoiceId
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return InvoiceUserShipping
     */
    public function createPersistedInvoiceUserShippingAndInitByWinningUser(
        int $invoiceId,
        int $editorUserId,
        bool $isReadOnlyDb = false
    ): InvoiceUserShipping {
        $invoiceShipping = $this->createInvoiceUserShippingAndInitByWinningUser($invoiceId, $isReadOnlyDb);
        $this->getInvoiceUserShippingWriteRepository()->saveWithModifier($invoiceShipping, $editorUserId);
        return $invoiceShipping;
    }

    public function createInvoiceUserAndInitByWinningUser(
        int $invoiceId,
        bool $isReadOnlyDb = false
    ): InvoiceUser {
        $invoice = $this->getInvoiceLoader()->load($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            $message = "Available invoice not found, when creating invoice user record"
                . composeSuffix(['i' => $invoiceId]);
            log_error($message);
            throw new InvalidArgumentException($message);
        }
        $invoiceUser = $this->createEntityFactory()->invoiceUser();
        $invoiceUser->InvoiceId = $invoice->Id;
        $sourceUser = $this->getUserLoader()->load($invoice->BidderId);
        $sourceUserInfo = $this->getUserLoader()->loadUserInfoOrCreate($invoice->BidderId);
        $invoiceUser->Username = $sourceUser->Username ?? null;
        $invoiceUser->Email = $sourceUser->Email ?? null;
        $invoiceUser->CustomerNo = $sourceUser->CustomerNo ?? null;
        $invoiceUser->FirstName = $sourceUserInfo->FirstName;
        $invoiceUser->LastName = $sourceUserInfo->LastName;
        $invoiceUser->Phone = $sourceUserInfo->Phone;
        $invoiceUser->Referrer = $sourceUserInfo->Referrer;
        $invoiceUser->ReferrerHost = $sourceUserInfo->ReferrerHost;
        $invoiceUser->Identification = $sourceUserInfo->Identification;
        $invoiceUser->IdentificationType = $sourceUserInfo->IdentificationType;
        return $invoiceUser;
    }

    public function createPersistedInvoiceUserAndInitByWinningUser(
        int $invoiceId,
        int $editorUserId,
        bool $isReadOnlyDb = false
    ): InvoiceUser {
        $invoiceUser = $this->createInvoiceUserAndInitByWinningUser($invoiceId, $isReadOnlyDb);
        $this->getInvoiceUserWriteRepository()->saveWithModifier($invoiceUser, $editorUserId);
        return $invoiceUser;
    }

    /**
     * Create user shipping object for invoice with coping data from existing invoice user shipping object
     *
     * @param int $targetInvoiceId target invoice id
     * @param int $sourceInvoiceId source to copy data
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return InvoiceUserShipping
     */
    public function createInvoiceUserShippingAndInitByInvoice(
        int $targetInvoiceId,
        int $sourceInvoiceId,
        int $editorUserId,
        bool $isReadOnlyDb = false
    ): InvoiceUserShipping {
        $invoiceShipping = $this->createEntityFactory()->invoiceUserShipping();
        $invoiceShipping->InvoiceId = $targetInvoiceId;
        $sourceInvoiceShipping = $this->getInvoiceUserLoader()
            ->loadInvoiceUserShippingOrCreatePersisted($sourceInvoiceId, $editorUserId, $isReadOnlyDb);
        $invoiceShipping->CompanyName = $sourceInvoiceShipping->CompanyName;
        $invoiceShipping->FirstName = $sourceInvoiceShipping->FirstName;
        $invoiceShipping->LastName = $sourceInvoiceShipping->LastName;
        $invoiceShipping->Phone = $sourceInvoiceShipping->Phone;
        $invoiceShipping->Fax = $sourceInvoiceShipping->Fax;
        $invoiceShipping->Country = $sourceInvoiceShipping->Country;
        $invoiceShipping->Address = $sourceInvoiceShipping->Address;
        $invoiceShipping->Address2 = $sourceInvoiceShipping->Address2;
        $invoiceShipping->Address3 = $sourceInvoiceShipping->Address3;
        $invoiceShipping->City = $sourceInvoiceShipping->City;
        $invoiceShipping->State = $sourceInvoiceShipping->State;
        $invoiceShipping->Zip = $sourceInvoiceShipping->Zip;
        return $invoiceShipping;
    }

    /**
     * Create and Persist user shipping object for invoice coping data from existing invoice user shipping object
     *
     * @param int $targetInvoiceId target invoice id
     * @param int $sourceInvoiceId source to copy data
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return InvoiceUserShipping
     */
    public function createPersistedInvoiceUserShippingAndInitByInvoice(
        int $targetInvoiceId,
        int $sourceInvoiceId,
        int $editorUserId,
        bool $isReadOnlyDb = false
    ): InvoiceUserShipping {
        $invoiceShipping = $this->createInvoiceUserShippingAndInitByInvoice(
            $targetInvoiceId,
            $sourceInvoiceId,
            $editorUserId,
            $isReadOnlyDb
        );
        $this->getInvoiceUserShippingWriteRepository()->saveWithModifier($invoiceShipping, $editorUserId);
        return $invoiceShipping;
    }
}
