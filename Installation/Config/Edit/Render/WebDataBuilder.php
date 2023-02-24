<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           31/05/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollection;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollectionAwareTrait;
use Sam\Installation\Config\Edit\Meta\OptionHelperAwareTrait;
use Sam\Installation\Config\Edit\Render\WebData\ConfigMenu\ConfigMenuItemWebData;
use Sam\Installation\Config\Edit\Render\WebData\ConfigMenu\ConfigMenuBuilder;
use Sam\Installation\Config\Edit\Render\WebData\FormData\FormDataBuilder;
use Sam\Installation\Config\Edit\Render\WebData\FormData\FormDataPreparer;
use Sam\Installation\Config\Edit\Render\WebData\Area\LocalConfig\LocalConfigValuesBuilder;
use Sam\Installation\Config\Edit\Render\WebData\Area\MainMenu\AreaMainMenuBuilder;
use Sam\Installation\Config\Edit\Render\WebData\Option\ValidationErrorWebData;
use Sam\Installation\Config\Edit\Render\WebData\ValidationErrorWebDataBuilder;
use Sam\Installation\Config\Edit\Validate\Web\ValidatedData;

/**
 * Class WebDataBuilder
 * Build web ready data arrays. See description for each class methods.
 *
 * Main method $this->build()
 * @package Sam\Installation\Config
 * @author: Yura Vakulenko
 */
class WebDataBuilder extends CustomizableClass
{
    use DescriptorCollectionAwareTrait;
    use OptionHelperAwareTrait;

    /**
     * Web ready main form data, that will be use for building html
     * for web-interface form in edit.tpl.php
     * @var array
     */
    protected array $formData = [];

    /**
     * Store after validation validated data for building input validation success||error messages and highlight
     * these config options in web interface config form.
     * @var ValidatedData[]
     */
    protected array $validatorValidatedData = [];

    // ------ Web Ready data properties --------
    /**
     * Web ready data with validation failed config keys, that will be use for building html
     * for web-interface form in edit.tpl.php
     * @var ValidationErrorWebData[]
     */
    protected array $validationErrorWebDatas = [];

    /**
     * Navigation for local config values.
     * @var array
     */
    protected array $localConfigNavigation = [];

    /**
     * Web ready Local config values and keys, that will be use for building html
     * for web-interface form in edit.tpl.php
     * @var array
     */
    protected array $localConfigValues = [];

    /**
     * Web ready list of available configs for fast switch between them.
     * @var ConfigMenuItemWebData[]
     */
    protected array $configMenu = [];

    /**
     * Web ready navigation array with all main config keys, that will be use for building html
     * for web-interface form in edit.tpl.php
     * @var array
     */
    protected array $mainMenu = [];

    /**
     * Web ready statistics config data, that will be use for building html
     * for web-interface form in edit.tpl.php
     * @var array
     */
    protected array $areaStatistics = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * build web ready data form config data arrays for building web-form interface.
     * That data will be use in InstallationSettingsController
     * @param DescriptorCollection $descriptorCollection
     * @param array $validatedData
     * @return static
     */
    public function build(DescriptorCollection $descriptorCollection, array $validatedData): static
    {
        $this->setDescriptorCollection($descriptorCollection);
        $this->setValidatorValidatedData($validatedData);

        $this->prepareFormData()
            ->buildConfigMenu()
            ->buildMainMenu()
            ->buildFormData()
            ->buildLocalConfigValues()
            ->buildLocalConfigNavigation()
            ->buildValidationErrors($validatedData, $descriptorCollection->getConfigName());
        return $this;
    }

    /**
     * @return static
     */
    protected function prepareFormData(): static
    {
        $webReadyFormData = FormDataPreparer::new()->prepareFormData($this->getDescriptorCollection());
        $this
            ->setFormData($webReadyFormData->getFormData())
            ->setAreaStatistics($webReadyFormData->getFormStatistics());
        return $this;
    }

    /**
     * Build web ready list of available configs, that will be use in web interface form.
     * @return static
     */
    protected function buildConfigMenu(): static
    {
        $configName = $this->getDescriptorCollection()->getConfigName();
        $availableConfigsList = ConfigMenuBuilder::new()->build($configName);
        $this->setConfigMenu($availableConfigsList);
        return $this;
    }

    /**
     * Build web ready navigation data.
     * @return static
     */
    protected function buildMainMenu(): static
    {
        $actualOptions = $this->getOptionHelper()->toActualOptions($this->getDescriptorCollection());
        $navigation = AreaMainMenuBuilder::new()->build($actualOptions);
        $this->setMainMenu($navigation);
        return $this;
    }

    /**
     * @return static
     */
    protected function buildLocalConfigNavigation(): static
    {
        $localConfigValues = $this->getLocalConfigValues();
        $localConfigNavigation = AreaMainMenuBuilder::new()->buildLocalConfigNavigation($localConfigValues);
        $this->setLocalConfigNavigation($localConfigNavigation);
        return $this;
    }

    /**
     * Build web ready form data.
     * @return static
     */
    protected function buildFormData(): static
    {
        $readyFormData = FormDataBuilder::new()->build(
            $this->getValidatorValidatedData(),
            $this->getFormData()
        );
        $this->setFormData($readyFormData);
        $this->syncFormDataAndNavigation();
        return $this;
    }

    /**
     * build web-ready local config values.
     * @return static
     */
    protected function buildLocalConfigValues(): static
    {
        $localOptions = LocalConfigValuesBuilder::new()->build(
            $this->getFormData(),
            $this->getDescriptorCollection()
        );
        $this->setLocalConfigValues($localOptions);
        return $this;
    }

    /**
     * Build web-ready validation errors and success data.
     * @param array $validatedData
     * @param string $configName
     * @return static
     */
    protected function buildValidationErrors(array $validatedData, string $configName): static
    {
        $validationErrorWebDatas = ValidationErrorWebDataBuilder::new()
            ->build($validatedData, $configName);
        $this->setValidationErrorWebDatas($validationErrorWebDatas);
        return $this;
    }

    /**
     * sync navigation with form data
     */
    private function syncFormDataAndNavigation(): void
    {
        $syncedNavigation = array_intersect_key($this->getMainMenu(), $this->getFormData());
        $this->setMainMenu($syncedNavigation);
    }

    /**
     * get web ready form data array.
     * @return array
     */
    public function getFormData(): array
    {
        return $this->formData;
    }

    /**
     * set web ready form data array.
     * @param array $data
     * @return static
     */
    public function setFormData(array $data): static
    {
        $this->formData = $data;
        return $this;
    }

    /**
     * @return ValidatedData[]
     */
    protected function getValidatorValidatedData(): array
    {
        return $this->validatorValidatedData;
    }

    /**
     * @param ValidatedData[] $data
     * @return static
     */
    public function setValidatorValidatedData(array $data): static
    {
        $this->validatorValidatedData = $data;
        return $this;
    }

    /**
     * Get web ready validation errors.
     * @return ValidationErrorWebData[]
     */
    public function getValidationErrorWebDatas(): array
    {
        return $this->validationErrorWebDatas;
    }

    /**
     * @param ValidationErrorWebData[] $data
     * @return $this
     */
    protected function setValidationErrorWebDatas(array $data): static
    {
        $this->validationErrorWebDatas = $data;
        return $this;
    }

    /**
     * Get web-ready local config navigation.
     * @return array
     */
    public function getLocalConfigNavigation(): array
    {
        return $this->localConfigNavigation;
    }

    /**
     * @param array $data
     * @return $this
     */
    protected function setLocalConfigNavigation(array $data): static
    {
        $this->localConfigNavigation = $data;
        return $this;
    }

    /**
     * Get web ready local config values.
     * @return array
     */
    public function getLocalConfigValues(): array
    {
        return $this->localConfigValues;
    }

    /**
     * @param array $data
     * @return $this
     */
    protected function setLocalConfigValues(array $data): static
    {
        $this->localConfigValues = $data;
        return $this;
    }

    /**
     * Get web ready available configs list.
     * @return array
     */
    public function getConfigMenu(): array
    {
        return $this->configMenu;
    }

    /**
     * @param ConfigMenuItemWebData[] $data
     * @return $this
     */
    protected function setConfigMenu(array $data): static
    {
        $this->configMenu = $data;
        return $this;
    }

    /**
     * Get web-ready navigation.
     * @return array
     */
    public function getMainMenu(): array
    {
        return $this->mainMenu;
    }

    /**
     * @param array $data
     * @return $this
     */
    protected function setMainMenu(array $data): static
    {
        $this->mainMenu = $data;
        return $this;
    }

    /**
     * Get web ready statistics.
     * @return array
     */
    public function getAreaStatistics(): array
    {
        return $this->areaStatistics;
    }

    /**
     * @param array $data
     * @return $this
     */
    protected function setAreaStatistics(array $data): static
    {
        $this->areaStatistics = $data;
        return $this;
    }
}
