<?php
/**
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 3, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Translate;

/**
 * Trait UserCustomFieldTranslationManagerAwareTrait
 * @package Sam\CustomField\User\Translate
 */
trait UserCustomFieldTranslationManagerAwareTrait
{
    /**
     * @var UserCustomFieldTranslationManager|null
     */
    protected ?UserCustomFieldTranslationManager $userCustomFieldTranslationManager = null;

    /**
     * @return UserCustomFieldTranslationManager
     */
    protected function getUserCustomFieldTranslationManager(): UserCustomFieldTranslationManager
    {
        if ($this->userCustomFieldTranslationManager === null) {
            $this->userCustomFieldTranslationManager = UserCustomFieldTranslationManager::new();
        }
        return $this->userCustomFieldTranslationManager;
    }

    /**
     * @param UserCustomFieldTranslationManager $userCustomFieldTranslationManager
     * @return static
     * @internal
     */
    public function setUserCustomFieldTranslationManager(UserCustomFieldTranslationManager $userCustomFieldTranslationManager): static
    {
        $this->userCustomFieldTranslationManager = $userCustomFieldTranslationManager;
        return $this;
    }
}
