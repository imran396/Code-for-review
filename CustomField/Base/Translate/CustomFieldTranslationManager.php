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

namespace Sam\CustomField\Base\Translate;

use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslationManagerAwareTrait;
use Sam\Lang\TranslatorAwareTrait;

/**
 * Class CustomFieldTranslationManager
 * @package Sam\CustomField\Base\Translate\CustomFieldTranslationManager
 */
class CustomFieldTranslationManager extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use FileManagerCreateTrait;
    use TranslationManagerAwareTrait;
    use TranslatorAwareTrait;

    private const CUSTOM_PARAM = 'CUSTOM_PARAM_';
    private const CUSTOM_PARAM_PARAMETERS = '_PARAMETERS';
    private const CUSTOM_PARAM_CHECKBOX_ON = '_CHECKBOX_ON';
    private const CUSTOM_PARAM_CHECKBOX_OFF = '_CHECKBOX_OFF';
    private const CHECKBOX_ON_TEXT = 'Yes';    // default values
    private const CHECKBOX_OFF_TEXT = 'No';

    protected string $translationsFilename = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return translation key for a custom field name
     *
     * @param string $name
     * @return string
     */
    public function makeKeyForName(string $name): string
    {
        return self::CUSTOM_PARAM . $this->getTranslationManager()->toLanguageKey($name);
    }

    /**
     * Return translation key for custom field parameters
     *
     * @param string $name A custom field name
     * @return string
     */
    public function makeKeyForParameters(string $name): string
    {
        return self::CUSTOM_PARAM . $this->getTranslationManager()->toLanguageKey($name) . self::CUSTOM_PARAM_PARAMETERS;
    }

    /**
     * Return translation key for checkbox-type custom field rendering texts, when it is checked or not
     *
     * @param string $name the name of custom field
     * @param bool $isChecked the value of custom field (checked or not)
     * @return string
     */
    public function makeKeyForCheckboxText(string $name, bool $isChecked): string
    {
        return self::CUSTOM_PARAM . $this->getTranslationManager()->toLanguageKey($name) .
            ($isChecked ? self::CUSTOM_PARAM_CHECKBOX_ON
                : self::CUSTOM_PARAM_CHECKBOX_OFF);
    }

    /**
     * Refresh translations for custom field
     *
     * @param int $fieldType the custom field type
     * @param string $name the custom field name
     * @param string $parameters the custom field parameters
     * @param string|null $oldName the custom field name it was before, null means custom field is new
     */
    public function refreshTranslations(int $fieldType, string $name, string $parameters, ?string $oldName = null): void
    {
        if ($name !== $oldName) {
            $translations = [];
            $fileName = $this->translationsFilename;
            $fileManager = $this->createFileManager();
            $filePath = substr(path()->customFieldTranslationMaster(), strlen(path()->sysRoot())) . '/' . $fileName;
            try {
                $fileManager->get($filePath);
                $translations = $this->getTranslationManager()->getTranslationsAsKeyedArray($fileName, true);
            } catch (FileException) {
                // new file will be created
            }

            if ($oldName !== null) {
                // remove old translations
                $oldLangKey = $this->makeKeyForName($oldName);
                $oldLangKeyParam = $this->makeKeyForParameters($oldName);
                unset($translations[$oldLangKey], $translations[$oldLangKeyParam]);
            }

            $langKey = $this->makeKeyForName($name);
            $langKeyParam = $this->makeKeyForParameters($name);
            $translations[$langKey] = [$name, $name];
            $translations[$langKeyParam] = [$parameters, $parameters];

            if ($fieldType === Constants\CustomField::TYPE_CHECKBOX) {
                $langKeyCheckboxOn = $this->makeKeyForCheckboxText($name, true);
                $langKeyCheckboxOff = $this->makeKeyForCheckboxText($name, false);
                $translations[$langKeyCheckboxOn] = [self::CHECKBOX_ON_TEXT, self::CHECKBOX_ON_TEXT];
                $translations[$langKeyCheckboxOff] = [self::CHECKBOX_OFF_TEXT, self::CHECKBOX_OFF_TEXT];
            }

            $accounts = $this->getAccountLoader()->loadAll();
            foreach ($accounts as $account) {
                $this->getTranslationManager()->saveKeyedTranslations($translations, $fileName, $account->Id);
            }
        }
    }

    /**
     * Delete translations for custom field
     *
     * @param int $fieldType the custom field type
     * @param string $name the custom field name
     */
    public function deleteTranslations(int $fieldType, string $name): void
    {
        $accountId = $this->cfg()->get('core->portal->mainAccountId');
        $fileName = $this->translationsFilename;
        $langKey = $this->makeKeyForName($name);
        $langKeyParam = $this->makeKeyForParameters($name);
        $translations = $this->getTranslationManager()->getTranslationsAsKeyedArray($fileName, true);
        unset($translations[$langKey], $translations[$langKeyParam]);

        if ($fieldType === Constants\CustomField::TYPE_CHECKBOX) {
            $langKeyCheckboxOn = $this->makeKeyForCheckboxText($name, true);
            $langKeyCheckboxOff = $this->makeKeyForCheckboxText($name, false);
            unset($translations[$langKeyCheckboxOn], $translations[$langKeyCheckboxOff]);
        }
        $this->getTranslationManager()->saveKeyedTranslations($translations, $fileName, $accountId);
    }

    /**
     * Set name of translations file
     *
     * @param string $fileName
     * @return static
     */
    public function setTranslationsFilename(string $fileName): static
    {
        $this->translationsFilename = $fileName;
        return $this;
    }
}
