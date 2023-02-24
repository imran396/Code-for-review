<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Load;

use InvoiceUserShipping;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\InvoiceUserShipping\InvoiceUserShippingReadRepositoryCreateTrait;

/**
 * Class InvoiceUserShippingLoader
 * @package Sam\Invoice\Common\Load
 */
class InvoiceUserShippingLoader extends CustomizableClass
{
    use InvoiceUserShippingReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load a InvoiceUserShipping
     * @param int|null $invoiceId
     * @param bool $isReadOnlyDb
     * @return InvoiceUserShipping|null
     */
    public function load(?int $invoiceId, bool $isReadOnlyDb = false): ?InvoiceUserShipping
    {
        if (!$invoiceId) {
            return null;
        }
        $invoiceUserShipping = $this->createInvoiceUserShippingReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->loadEntity();
        return $invoiceUserShipping;
    }
}
