<?php
/**
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 12, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\OrderNo;

/**
 * Trait UserCustomFieldOrderNoAdviserAwareTrait
 * @package Sam\CustomField\User\OrderNo
 */
trait UserCustomFieldOrderNoAdviserAwareTrait
{
    /**
     * @var UserCustomFieldOrderNoAdviser|null
     */
    protected ?UserCustomFieldOrderNoAdviser $userCustomFieldOrderNoAdviser = null;

    /**
     * @return UserCustomFieldOrderNoAdviser
     */
    protected function getUserCustomFieldOrderNoAdviser(): UserCustomFieldOrderNoAdviser
    {
        if ($this->userCustomFieldOrderNoAdviser === null) {
            $this->userCustomFieldOrderNoAdviser = UserCustomFieldOrderNoAdviser::new();
        }
        return $this->userCustomFieldOrderNoAdviser;
    }

    /**
     * @param UserCustomFieldOrderNoAdviser $userCustomFieldOrderNoAdviser
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setUserCustomFieldOrderNoAdviser(UserCustomFieldOrderNoAdviser $userCustomFieldOrderNoAdviser): static
    {
        $this->userCustomFieldOrderNoAdviser = $userCustomFieldOrderNoAdviser;
        return $this;
    }
}
