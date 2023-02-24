<?php
/**
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @since           Oct 17, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Qform\Component;

/**
 * Trait UserCustomFieldComponentBuilderAwareTrait
 * @package Sam\CustomField\User\Qform\Component
 */
trait UserCustomFieldComponentBuilderAwareTrait
{
    protected ?UserCustomFieldComponentBuilder $userCustomFieldComponentBuilder = null;

    /**
     * @return UserCustomFieldComponentBuilder
     */
    protected function getUserCustomFieldComponentBuilder(): UserCustomFieldComponentBuilder
    {
        if ($this->userCustomFieldComponentBuilder === null) {
            $this->userCustomFieldComponentBuilder = UserCustomFieldComponentBuilder::new();
        }
        return $this->userCustomFieldComponentBuilder;
    }

    /**
     * @param UserCustomFieldComponentBuilder $userCustomFieldComponentBuilder
     * @return static
     * @internal
     */
    public function setUserCustomFieldComponentBuilder(UserCustomFieldComponentBuilder $userCustomFieldComponentBuilder): static
    {
        $this->userCustomFieldComponentBuilder = $userCustomFieldComponentBuilder;
        return $this;
    }
}
