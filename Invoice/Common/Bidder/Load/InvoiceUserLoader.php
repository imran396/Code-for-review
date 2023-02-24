<?php
/**
 * SAM-4554: Move Invoice_Bidder logic to InvoiceUserLoader, InvoiceUserProducer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis, Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/13/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Bidder\Load;

use InvoiceUser;
use InvoiceUserBilling;
use InvoiceUserShipping;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Invoice\Common\Bidder\Save\InvoiceUserProducerAwareTrait;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceUser\InvoiceUserReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceUserBilling\InvoiceUserBillingReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceUserShipping\InvoiceUserShippingReadRepositoryCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class InvoiceUserLoader
 * @package Sam\Invoice\Common\Bidder\Load
 */
class InvoiceUserLoader extends EntityLoaderBase
{
    use EntityMemoryCacheManagerAwareTrait;
    use InvoiceUserReadRepositoryCreateTrait;
    use InvoiceUserBillingReadRepositoryCreateTrait;
    use InvoiceUserProducerAwareTrait;
    use InvoiceUserShippingReadRepositoryCreateTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- InvoiceUser ---

    public function loadInvoiceUser(int $invoiceId, bool $isReadOnlyDb = false): ?InvoiceUser
    {
        $invoiceUser = $this->createInvoiceUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->loadEntity();
        return $invoiceUser;
    }

    public function loadInvoiceUserOrCreate(
        int $invoiceId,
        bool $isReadOnlyDb = false
    ): InvoiceUser {
        $invoiceUser = $this->loadInvoiceUser($invoiceId, $isReadOnlyDb);
        if (!$invoiceUser) {
            // Create InvoiceUser
            $invoiceUser = $this->getInvoiceUserProducer()
                ->createInvoiceUserAndInitByWinningUser($invoiceId, $isReadOnlyDb);
        }
        return $invoiceUser;
    }

    // --- InvoiceUserBilling ---

    /**
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @return InvoiceUserBilling|null
     */
    public function loadInvoiceUserBilling(int $invoiceId, bool $isReadOnlyDb = false): ?InvoiceUserBilling
    {
        $invoiceUserBilling = $this->createInvoiceUserBillingReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->loadEntity();
        return $invoiceUserBilling;
    }

    /**
     * Load user billing info record for invoice
     * If it is not exists, new object will be Created (not saved), data will be copied from corresponding user billing object
     * @param int $invoiceId
     * @param bool $isReadOnlyDb use read-only db, if possible
     * @return InvoiceUserBilling
     */
    public function loadInvoiceUserBillingOrCreate(
        int $invoiceId,
        bool $isReadOnlyDb = false
    ): InvoiceUserBilling {
        $invoiceUserBilling = $this->loadInvoiceUserBilling($invoiceId, $isReadOnlyDb);
        if (!$invoiceUserBilling) {
            // Create InvoiceUserBilling
            $invoiceUserBilling = $this->getInvoiceUserProducer()
                ->createInvoiceUserBillingAndInitByWinningUser($invoiceId, $isReadOnlyDb);
        }
        return $invoiceUserBilling;
    }

    /**
     * Load user billing info record for invoice
     * If it is not exists, new Persisted record will be Created, data will be copied from corresponding user billing object
     * @param int $invoiceId
     * @param int $editorUserId
     * @param bool $isReadOnlyDb use read-only db, if possible
     * @return InvoiceUserBilling
     */
    public function loadInvoiceUserBillingOrCreatePersisted(
        int $invoiceId,
        int $editorUserId,
        bool $isReadOnlyDb = false
    ): InvoiceUserBilling {
        $invoiceUserBilling = $this->loadInvoiceUserBilling($invoiceId, $isReadOnlyDb);
        if (!$invoiceUserBilling) {
            // Create persisted InvoiceUserBilling
            $invoiceUserBilling = $this->getInvoiceUserProducer()
                ->createPersistedInvoiceUserBillingAndInitByWinningUser($invoiceId, $editorUserId, $isReadOnlyDb);
        }
        return $invoiceUserBilling;
    }

    // --- InvoiceUserShipping ---

    /**
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @return InvoiceUserShipping|null
     */
    public function loadInvoiceUserShipping(int $invoiceId, bool $isReadOnlyDb = false): ?InvoiceUserShipping
    {
        $invoiceUserShipping = $this->createInvoiceUserShippingReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->loadEntity();
        return $invoiceUserShipping;
    }

    /**
     * Load user shipping info record for invoice
     * If it is not exists, new object will be created (not saved), data will be copied from corresponding user shipping object
     * @param int $invoiceId
     * @param bool $isReadOnlyDb use read-only db, if possible
     * @return InvoiceUserShipping
     */
    public function loadInvoiceUserShippingOrCreate(
        int $invoiceId,
        bool $isReadOnlyDb = false
    ): InvoiceUserShipping {
        $invoiceUserShipping = $this->loadInvoiceUserShipping($invoiceId, $isReadOnlyDb);
        if (!$invoiceUserShipping) {
            // Create persisted InvoiceUserShipping
            $invoiceUserShipping = $this->getInvoiceUserProducer()
                ->createInvoiceUserShippingAndInitByWinningUser($invoiceId, $isReadOnlyDb);
        }
        return $invoiceUserShipping;
    }

    /**
     * Load user shipping info record for invoice
     * If it is not exists, new persisted record will be created, data will be copied from corresponding user shipping object
     * @param int $invoiceId
     * @param int $editorUserId
     * @param bool $isReadOnlyDb use read-only db, if possible
     * @return InvoiceUserShipping
     */
    public function loadInvoiceUserShippingOrCreatePersisted(
        int $invoiceId,
        int $editorUserId,
        bool $isReadOnlyDb = false
    ): InvoiceUserShipping {
        $invoiceUserShipping = $this->loadInvoiceUserShipping($invoiceId, $isReadOnlyDb);
        if (!$invoiceUserShipping) {
            // Create persisted InvoiceUserShipping
            $invoiceUserShipping = $this->getInvoiceUserProducer()
                ->createPersistedInvoiceUserShippingAndInitByWinningUser($invoiceId, $editorUserId, $isReadOnlyDb);
        }
        return $invoiceUserShipping;
    }
}
