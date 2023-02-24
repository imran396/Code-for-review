<?php
/**
 * Help methods for auction custom field data loading
 *
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 18, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Help;

/**
 * Trait UserCustomFieldHelperAwareTrait
 * @package Sam\CustomField\User\Help
 */
trait UserCustomFieldHelperAwareTrait
{
    /**
     * @var UserCustomFieldHelper|null
     */
    protected ?UserCustomFieldHelper $userCustomFieldHelper = null;

    /**
     * @return UserCustomFieldHelper
     */
    protected function getUserCustomFieldHelper(): UserCustomFieldHelper
    {
        if ($this->userCustomFieldHelper === null) {
            $this->userCustomFieldHelper = UserCustomFieldHelper::new();
        }
        return $this->userCustomFieldHelper;
    }

    /**
     * @param UserCustomFieldHelper $userCustomFieldHelper
     * @return static
     * @internal
     */
    public function setUserCustomFieldHelper(UserCustomFieldHelper $userCustomFieldHelper): static
    {
        $this->userCustomFieldHelper = $userCustomFieldHelper;
        return $this;
    }
}
