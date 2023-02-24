<?php
/**
 * SAM-9370: Access checker for lot category delete operation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Access\Management;


/**
 * Trait LotCategoryManagementAccessCheckerAwareTrait
 * @package Sam\Lot\Category\Access\Management
 */
trait LotCategoryManagementAccessCheckerCreateTrait
{
    /**
     * @var LotCategoryManagementAccessChecker|null
     */
    protected ?LotCategoryManagementAccessChecker $lotCategoryManagementAccessChecker = null;

    /**
     * @return LotCategoryManagementAccessChecker
     */
    protected function createLotCategoryManagementAccessChecker(): LotCategoryManagementAccessChecker
    {
        return $this->lotCategoryManagementAccessChecker ?: LotCategoryManagementAccessChecker::new();
    }

    /**
     * @param LotCategoryManagementAccessChecker $lotCategoryManagementAccessChecker
     * @return $this
     * @internal
     */
    public function setLotCategoryManagementAccessChecker(LotCategoryManagementAccessChecker $lotCategoryManagementAccessChecker): static
    {
        $this->lotCategoryManagementAccessChecker = $lotCategoryManagementAccessChecker;
        return $this;
    }
}
