<?php
/**
 * SAM-6542: Optimize db reads
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Load;

use InvoiceAuction;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\InvoiceAuction\InvoiceAuctionReadRepositoryCreateTrait;

/**
 * Class InvoiceAuctionLoader
 * @package Sam\Invoice\Common\Load
 */
class InvoiceAuctionLoader extends CustomizableClass
{
    use InvoiceAuctionReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $invoiceId null - absent invoice id leads to absent result
     * @param int|null $auctionId null - absent auction id leads to absent result
     * @param bool $isReadOnlyDb
     * @return InvoiceAuction|null
     */
    public function load(?int $invoiceId, ?int $auctionId, bool $isReadOnlyDb = false): ?InvoiceAuction
    {
        if (
            !$invoiceId
            || !$auctionId
        ) {
            return null;
        }

        $invoiceAuction = $this->createInvoiceAuctionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->filterAuctionId($auctionId)
            ->loadEntity();
        return $invoiceAuction;
    }

    /**
     * @param int|null $invoiceId null - absent invoice id leads to absent result
     * @param bool $isReadOnlyDb
     * @return InvoiceAuction[]
     */
    public function loadByInvoiceId(?int $invoiceId, bool $isReadOnlyDb = false): array
    {
        if (!$invoiceId) {
            return [];
        }

        $invoiceAuctions = $this->createInvoiceAuctionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->loadEntities();
        return $invoiceAuctions;
    }

    public function loadSelectedByInvoiceId(array $select, ?int $invoiceId, bool $isReadOnlyDb = false): array
    {
        if (!$invoiceId) {
            return [];
        }

        $rows = $this->createInvoiceAuctionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->select($select)
            ->loadRows();
        return $rows;
    }
}
