<?php
/**
 * Data Transfer Object (DTO) with data for using in Installation settings edit form View.
 *
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-15, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render\WebData;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Meta\ConfigNameAwareTrait;
use Sam\Installation\Config\Edit\Render\WebData\Option\ValidationErrorWebData;
use Sam\Installation\Config\Edit\Render\WebData\Area\LocalConfig\Section\LocalOptionSectionWebData;

/**
 * Class TemplateDataValueObject
 * @package Sam\Installation\Config
 */
class EditViewDataDto extends CustomizableClass
{
    use ConfigNameAwareTrait;

    /**
     * Web form action url.
     * @var string|null
     */
    protected ?string $formActionUrl;

    /**
     * Logout url for Edit Installation settings page.
     * @var string|null
     */
    protected ?string $logoutUrl;

    /**
     * Web ready available configs list for rendering.
     * @var array|null
     */
    protected ?array $configMenu;

    /**
     * Web ready navigation for rendering.
     * @var array|null
     */
    protected ?array $mainMenu;

    /**
     * Web ready form data for rendering.
     * @var array|null
     */
    protected ?array $form;

    /**
     * Web ready statistics for rendering.
     * @var array|null
     */
    protected ?array $statistics;

    /**
     * Web ready local config values for rendering.
     * @var LocalOptionSectionWebData[]|null
     */
    protected ?array $localConfigValues;

    /**
     * Web ready local config navigation for rendering.
     * @var array|null
     */
    protected ?array $localConfigNavigation;

    /**
     * Web ready validation errors for rendering on top of page.
     * @var array
     */
    protected array $validationErrors = [];

    /**
     * Css class for config updated alert.
     * @var string|null
     */
    protected ?string $configUpdatedAlertClass;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Internal class constructor.
     *
     * @param string $configName
     * @param string $formActionUrl
     * @param string $logoutUrl
     * @return $this
     */
    public function construct(
        string $configName,
        string $formActionUrl,
        string $logoutUrl
    ): static {
        $this->setConfigName($configName);
        $this->formActionUrl = $formActionUrl;
        $this->logoutUrl = $logoutUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormActionUrl(): string
    {
        return $this->formActionUrl;
    }

    /**
     * @return string
     */
    public function getLogoutUrl(): string
    {
        return $this->logoutUrl;
    }

    /**
     * @return array|null
     */
    public function getConfigMenu(): ?array
    {
        return $this->configMenu;
    }

    /**
     * @param array $configMenu
     * @return $this
     */
    public function setConfigMenu(array $configMenu): static
    {
        $this->configMenu = $configMenu;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getMainMenu(): ?array
    {
        return $this->mainMenu;
    }

    /**
     * @param array $mainMenu
     * @return $this
     */
    public function setMainMenu(array $mainMenu): static
    {
        $this->mainMenu = $mainMenu;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getForm(): ?array
    {
        return $this->form;
    }

    /**
     * @param array $form
     * @return $this
     */
    public function setForm(array $form): static
    {
        $this->form = $form;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getStatistics(): ?array
    {
        return $this->statistics;
    }

    /**
     * @param array $statistics
     * @return $this
     */
    public function setStatistics(array $statistics): static
    {
        $this->statistics = $statistics;
        return $this;
    }

    /**
     * @return LocalOptionSectionWebData[]|null
     */
    public function getLocalConfigValues(): ?array
    {
        return $this->localConfigValues;
    }

    /**
     * @param LocalOptionSectionWebData[] $localConfigValues
     * @return $this
     */
    public function setLocalConfigValues(array $localConfigValues): static
    {
        $this->localConfigValues = $localConfigValues;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getLocalConfigNavigation(): ?array
    {
        return $this->localConfigNavigation;
    }

    /**
     * @param array $localConfigNavigation
     * @return $this
     */
    public function setLocalConfigNavigation(array $localConfigNavigation): static
    {
        $this->localConfigNavigation = $localConfigNavigation;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEmptyLocalConfig(): bool
    {
        foreach ($this->getLocalConfigValues() ?: [] as $optionSection) {
            if (count($optionSection->getData())) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return ValidationErrorWebData[]
     */
    public function getValidationErrorWebDatas(): array
    {
        return $this->validationErrors;
    }

    /**
     * @param ValidationErrorWebData[] $validationErrors
     * @return $this
     */
    public function setValidationErrorWebDatas(array $validationErrors): static
    {
        $this->validationErrors = $validationErrors;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getConfigUpdatedAlertClass(): ?string
    {
        return $this->configUpdatedAlertClass;
    }

    /**
     * @param string $configUpdatedAlertClass
     * @return $this
     */
    public function setConfigUpdatedAlertClass(string $configUpdatedAlertClass): static
    {
        $this->configUpdatedAlertClass = $configUpdatedAlertClass;
        return $this;
    }
}
