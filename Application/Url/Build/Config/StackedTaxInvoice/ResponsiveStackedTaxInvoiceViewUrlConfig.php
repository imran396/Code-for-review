<?php
/**
 * SAM-11122: Stacked Tax. Public My Invoice pages. Responsive Invoice View page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\StackedTaxInvoice;

use Sam\Core\Constants;

/**
 * Class ResponsiveStackedTaxInvoiceViewUrlConfig
 * @package Sam\Application\Url\Build\Config\SingleInvoice
 */
class ResponsiveStackedTaxInvoiceViewUrlConfig extends AbstractResponsiveSingleStackedTaxInvoiceUrlConfig
{
    protected ?int $urlType = Constants\Url::P_STACKED_TAX_INVOICE_VIEW;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

}
