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

namespace Sam\CustomField\Lot\Translate;

use LotItemCustField;
use Sam\CustomField\Base\Translate\CustomFieldTranslationManager;
use Sam\Core\Constants;

/**
 * Class LotCustomFieldTranslationManager
 * @package Sam\CustomField\Lot\Translate\LotCustomFieldTranslationManager
 */
class LotCustomFieldTranslationManager extends CustomFieldTranslationManager
{
    protected string $translationsFilename = Constants\CustomField::LOT_CUSTOM_FIELD_TRANSLATION_FILE;

    /**
     * Class instantiation method
     * @return static or customized class extending CustomFieldTranslationManager
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Refresh translations for lot item custom field
     *
     * @param LotItemCustField $lotCustomField
     * @param string|null $oldName , null if custom field is new
     */
    public function refresh(LotItemCustField $lotCustomField, ?string $oldName = null): void
    {
        $this->refreshTranslations($lotCustomField->Type, $lotCustomField->Name, $lotCustomField->Parameters, $oldName);
    }

    /**
     * Delete translations for lot item custom field
     *
     * @param LotItemCustField $lotCustomField
     */
    public function delete(LotItemCustField $lotCustomField): void
    {
        $this->deleteTranslations($lotCustomField->Type, $lotCustomField->Name);
    }

    /**
     * Return translation of custom field name
     *
     * @param LotItemCustField $lotCustomField
     * @return string
     */
    public function translateName(LotItemCustField $lotCustomField): string
    {
        $langKeyName = $this->makeKeyForName($lotCustomField->Name);
        return $this->getTranslator()->translate($langKeyName, 'customfields');
    }

    /**
     * Return translation of custom field parameters
     *
     * @param LotItemCustField $lotCustomField
     * @return string
     */
    public function translateParameters(LotItemCustField $lotCustomField): string
    {
        $langKeyParam = $this->makeKeyForParameters($lotCustomField->Name);
        return $this->getTranslator()->translate($langKeyParam, 'customfields');
    }
}
