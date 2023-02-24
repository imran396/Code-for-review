<?php
/**
 * SAM-11525: Stacked Tax. Actions at the Admin Invoice List page. Extract general validation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\InvoiceList\Validate\Internal\Load;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\Common\Validate\InvoiceRelatedEntityValidatorAwareTrait;

/**
 * Class DataProvider
 * @package Sam\Invoice\StackedTax\Charge\Admin\InvoiceList\Validate\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use InvoiceLoaderAwareTrait;
    use InvoiceRelatedEntityValidatorAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        return $this;
    }

    public function isOperable(int $invoiceId): bool
    {
        return $this->getInvoiceRelatedEntityValidator()
            ->setInvoiceId($invoiceId)
            ->validate();
    }

    public function loadInvoice(int $invoiceId, bool $isReadOnlyDb = false): ?Invoice
    {
        return $this->getInvoiceLoader()
            ->clear()
            ->load($invoiceId, $isReadOnlyDb);
    }
}
