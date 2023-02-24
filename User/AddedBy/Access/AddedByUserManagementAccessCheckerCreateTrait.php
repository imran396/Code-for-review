<?php
/**
 * SAM-6928: Sales staff user assignment and filtering control adjustments at the "User Edit" and the "Sales Report" pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\AddedBy\Access;

/**
 * Trait AddedByUserManagementAccessCheckerCreateTrait
 * @package Sam\User\AddedBy\Access
 */
trait AddedByUserManagementAccessCheckerCreateTrait
{
    protected ?AddedByUserManagementAccessChecker $addedByUserManagementAccessChecker = null;

    /**
     * @return AddedByUserManagementAccessChecker
     */
    protected function createAddedByUserManagementAccessChecker(): AddedByUserManagementAccessChecker
    {
        return $this->addedByUserManagementAccessChecker ?: AddedByUserManagementAccessChecker::new();
    }

    /**
     * @param AddedByUserManagementAccessChecker $addedByUserManagementAccessChecker
     * @return $this
     * @internal
     */
    public function setAddedByUserManagementAccessChecker(AddedByUserManagementAccessChecker $addedByUserManagementAccessChecker): static
    {
        $this->addedByUserManagementAccessChecker = $addedByUserManagementAccessChecker;
        return $this;
    }
}
