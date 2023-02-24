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

use Sam\CustomField\Base\Translate\CustomFieldTranslationManager;
use UserCustField;
use Sam\Core\Constants;

/**
 * Class UserCustomFieldTranslationManager
 * @package Sam\CustomField\User\Translate\UserCustomFieldTranslationManager
 */
class UserCustomFieldTranslationManager extends CustomFieldTranslationManager
{
    protected string $translationsFilename = Constants\CustomField::USER_CUSTOM_FIELD_TRANSLATION_FILE;

    /**
     * Class instantiation method
     * @return static or customized class extending CustomFieldTranslationManager
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Refresh translations for user custom field
     *
     * @param UserCustField $userCustomField
     * @param string|null $oldName , null means custom field is new
     */
    public function refresh(UserCustField $userCustomField, ?string $oldName = null): void
    {
        $this->refreshTranslations($userCustomField->Type, $userCustomField->Name, $userCustomField->Parameters, $oldName);
    }

    /**
     * Delete translations for custom field
     *
     * @param UserCustField $userCustomField
     */
    public function delete(UserCustField $userCustomField): void
    {
        $this->deleteTranslations($userCustomField->Type, $userCustomField->Name);
    }

    /**
     * Return translation of custom field name
     *
     * @param UserCustField $userCustomField
     * @return string
     */
    public function translateName(UserCustField $userCustomField): string
    {
        $langKeyName = $this->makeKeyForName($userCustomField->Name);
        return $this->getTranslator()->translate($langKeyName, 'usercustomfields');
    }

    /**
     * Return translation of custom field parameters
     *
     * @param UserCustField $userCustomField
     * @return string
     */
    public function translateParameters(UserCustField $userCustomField): string
    {
        $langKeyParam = $this->makeKeyForParameters($userCustomField->Name);
        return $this->getTranslator()->translate($langKeyParam, 'usercustomfields');
    }
}
