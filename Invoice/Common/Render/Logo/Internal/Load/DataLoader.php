<?php
/**
 * SAM-10234: Add unit tests for path resolver classes
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Apr 09, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Render\Logo\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceHeaderDataLoaderAwareTrait;
use Sam\Settings\Layout\Image\Path\LayoutImagePathResolverCreateTrait;

/**
 * Class DataLoader
 * @package Sam/Invoice/Render/Logo/Internal/Load
 */
class DataLoader extends CustomizableClass
{
    use InvoiceHeaderDataLoaderAwareTrait;
    use LayoutImagePathResolverCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    /**
     * @param int $invoiceId
     * @return array
     */
    public function loadInvoiceHeader(int $invoiceId): array
    {
        return $this->getInvoiceHeaderDataLoader()->load($invoiceId);
    }

    /**
     * @param int $accountId
     * @return bool
     */
    public function hasInvoiceLogo(int $accountId): bool
    {
        return $this->createLayoutImagePathResolver()->hasInvoiceLogo($accountId);
    }
}
