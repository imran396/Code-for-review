<?php
/**
 * SAM-9579: Check access for buyer group management
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyerGroupEditForm\Access\Management;

/**
 * Trait BuyerGroupManagementAccessCheckerCreateTrait
 * @package
 */
trait BuyerGroupManagementAccessCheckerCreateTrait
{
    protected ?BuyerGroupManagementAccessChecker $buyerGroupManagementAccessChecker = null;

    /**
     * @return BuyerGroupManagementAccessChecker
     */
    protected function createBuyerGroupManagementAccessChecker(): BuyerGroupManagementAccessChecker
    {
        return $this->buyerGroupManagementAccessChecker ?: BuyerGroupManagementAccessChecker::new();
    }

    /**
     * @param BuyerGroupManagementAccessChecker $buyerGroupManagementAccessChecker
     * @return $this
     * @internal
     */
    public function setBuyerGroupManagementAccessChecker(BuyerGroupManagementAccessChecker $buyerGroupManagementAccessChecker): static
    {
        $this->buyerGroupManagementAccessChecker = $buyerGroupManagementAccessChecker;
        return $this;
    }
}
