<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\Settlement;

use Sam\Core\Constants;

/**
 * Class ResponsiveInvoiceViewUrlConfig
 * @package Sam\Application\Url\Build\Config\SingleInvoice
 */
class ResponsiveSettlementViewUrlConfig extends AbstractResponsiveSingleSettlementUrlConfig
{
    protected ?int $urlType = Constants\Url::P_SETTLEMENTS_VIEW;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

}
