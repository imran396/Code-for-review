<?php
/**
 * SAM-6830: Enrich Invoice entity
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\InvoiceItem\Status;

use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceItemStatusPureChecker
 * @package Sam\Core\Entity\Model\Invoice\Status
 */
class InvoiceItemStatusPureChecker extends CustomizableClass
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
     * Check, if invoice item status is active.
     * @param bool|null $active null check JIC
     * @return bool
     */
    public function isActive(?bool $active): bool
    {
        return $active === true;
    }

    /**
     * Check, if invoice item status is soft-deleted.
     * @param bool|null $active null check JIC
     * @return bool
     */
    public function isDeleted(?bool $active): bool
    {
        return $active === false;
    }
}
