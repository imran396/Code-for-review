<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\InvoiceItem\SellInfo;

use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceItemSellInfoPureChecker
 * @package Sam\Core\Entity\Model\InvoiceItem\SellInfo
 */
class InvoiceItemSellInfoPureChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check, if HP is set
     * @param float|null $hammerPrice
     * @return bool
     */
    public function isHammerPrice(?float $hammerPrice): bool
    {
        return $hammerPrice !== null;
    }

}
