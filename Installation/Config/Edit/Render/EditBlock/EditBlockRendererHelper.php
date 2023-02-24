<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           09-13, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render\EditBlock;

use Sam\Core\Constants;
use Sam\Core\Constants\Admin\InstallationSettingEditConstants;
use Sam\Core\Php\Prettier\PhpPrettier;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Render\StringDecorator;
use Sam\Installation\Config\Edit\Render\WebData\OptionInput\InputData\InputDataWeb;

/**
 * Class EditBlockRendererHelper
 * @package Sam\Installation\Config
 */
class EditBlockRendererHelper extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return Descriptor::getType() value (if Descriptor::getKnownSet() returns null)
     * or Descriptor::getEditComponent() value
     * for Edit component
     * @param InputDataWeb $inputData
     * @return array
     */
    public function fetchEditComponentTypeFromDescriptor(InputDataWeb $inputData): array
    {
        $descriptor = $inputData->getMetaDescriptor();
        $output = [];
        if (
            empty($descriptor->getKnownSetNames())
            && empty($descriptor->getKnownSet())
        ) {
            $output[Constants\Installation::META_ATTR_TYPE] = $descriptor->getType();
        } else {
            $output[Constants\Installation::META_ATTR_EDIT_COMPONENT] = $descriptor->getEditComponent();
        }

        return $output;
    }

    public function fetchEditComponentBuildType(array $editComponentTypeFromDescriptor, int $countMetaKnownSetItems): string
    {
        if (
            !$countMetaKnownSetItems
            && isset($editComponentTypeFromDescriptor[Constants\Installation::META_ATTR_TYPE])
        ) {
            $generalTypes = [
                Constants\Type::T_STRING,
                Constants\Type::T_INTEGER,
                Constants\Type::T_FLOAT,
                Constants\Type::T_NULL,
            ];
            if (in_array($editComponentTypeFromDescriptor[Constants\Installation::META_ATTR_TYPE], $generalTypes, true)) {
                return InstallationSettingEditConstants::ECOM_BUILD_TYPE_GENERAL;
            }
            if ($editComponentTypeFromDescriptor[Constants\Installation::META_ATTR_TYPE] === Constants\Type::T_BOOL) {
                return InstallationSettingEditConstants::ECOM_BUILD_TYPE_BOOLEAN; // build for Constants\Type::T_BOOL
            }
            if ($editComponentTypeFromDescriptor[Constants\Installation::META_ATTR_TYPE] === Constants\Type::T_ARRAY) {
                return InstallationSettingEditConstants::ECOM_BUILD_TYPE_ARRAY;
            }
            if ($editComponentTypeFromDescriptor[Constants\Installation::META_ATTR_TYPE] === Constants\Installation::T_STRUCT_ARRAY) {
                return InstallationSettingEditConstants::ECOM_BUILD_TYPE_STRUCT_ARRAY;
            }

            return InstallationSettingEditConstants::ECOM_BUILD_TYPE_UNKNOWN;
        }

        if (
            $countMetaKnownSetItems
            && isset($editComponentTypeFromDescriptor[Constants\Installation::META_ATTR_EDIT_COMPONENT])
        ) {
            // if 'metaKnownSet' or 'metaKnownSetNames' and Descriptor::ATTR_EDIT_COMPONENT exists in meta file for current option
            $editComponent = $editComponentTypeFromDescriptor[Constants\Installation::META_ATTR_EDIT_COMPONENT];
            if (in_array($editComponent, [Constants\Installation::ECOM_RADIO, Constants\Installation::ECOM_LINE], true)) {
                // if we have no attr Descriptor::ATTR_EDIT_COMPONENT in our meta file or it is Constants\Installation::ECOM_DEFAULT.
                // In this case we automatically build radio-buttons or (multi)select 'editComponent'
                return $this->fetchEditComponentBuildTypeForKnownSet($countMetaKnownSetItems);
            }

            if (in_array($editComponent, [Constants\Installation::ECOM_SELECT, Constants\Installation::ECOM_MULTISELECT], true)) {
                if ($editComponent === Constants\Installation::ECOM_MULTISELECT) {
                    return InstallationSettingEditConstants::ECOM_BUILD_TYPE_SELECT_MULTIPLE;
                }
                return $this->fetchEditComponentBuildTypeForKnownSet($countMetaKnownSetItems);
            }
        }

        return InstallationSettingEditConstants::ECOM_BUILD_TYPE_UNKNOWN;
    }

    public function fetchMetaKnownSetForEditComponent(InputDataWeb $inputData): array
    {
        $descriptor = $inputData->getMetaDescriptor();
        $metaKnownSet = $descriptor->getKnownSet();
        $metaKnownSetNames = $descriptor->getKnownSetNames();
        if (
            count($metaKnownSet)
            || count($metaKnownSetNames)
        ) {
            return count($metaKnownSet) ? $metaKnownSet : $metaKnownSetNames;
        }
        return [];
    }

    /**
     * Get web form control type for config value input area.
     * @param InputDataWeb $inputData
     * @return string
     */
    public function getInputControlType(InputDataWeb $inputData): string
    {
        $output = Constants\Installation::ECOM_DEFAULT;
        $control = $inputData->getMetaDescriptor()->getEditComponent();
        if (in_array($control, Constants\Installation::AVAILABLE_EDIT_COMPONENTS, true)) {
            $output = $control;
        }

        return $output;
    }

    /**
     * @param InputDataWeb $inputData
     * @return mixed
     */
    public function getValidationPostValue(InputDataWeb $inputData): mixed
    {
        $output = $inputData->getValidation()->getPostValue();
        return $output;
    }

    /**
     * @param InputDataWeb $inputData
     * @param bool $outputAsJson
     * @return string
     */
    public function buildWebFormReadyInputValueFromArray(InputDataWeb $inputData, bool $outputAsJson = false): string
    {
        if ($inputData->getMetaDescriptor()->getType() !== Constants\Type::T_ARRAY) {
            return '';
        }

        $decoratedValue = $outputAsJson
            ? $inputData->getValue()
            : StringDecorator::new()->decorateQuotesInArrayValues($inputData->getValue());
        if (empty($decoratedValue)) {
            return '';
        }

        $output = $outputAsJson
            ? $this->buildJsonString($decoratedValue, $inputData->getMetaDescriptor()->getOptionKey())
            : implode(',', $decoratedValue);
        return $output;
    }

    /**
     * @param InputDataWeb $inputData
     * @param bool $outputAsJson
     * @return string
     */
    public function buildWebFormReadyInputValueFromStructuredArray(InputDataWeb $inputData, bool $outputAsJson = false): string
    {
        $inputDataWebValueStructured = $inputData->getValueStructured();
        if (
            $inputDataWebValueStructured
            && $inputData->getMetaDescriptor()->getType() === Constants\Installation::T_STRUCT_ARRAY
        ) {
            $value = $inputDataWebValueStructured->getStructures();
            if ($outputAsJson) {
                return $this->buildJsonString($value, $inputData->getMetaDescriptor()->getOptionKey());
            }
            $structures = var_export($value, true);
            $phpPrettier = new PhpPrettier();
            return $phpPrettier->transform($structures);
        }
        return '';
    }

    protected function fetchEditComponentBuildTypeForKnownSet(int $countMetaKnownSetItems): string
    {
        if ($countMetaKnownSetItems > Constants\Installation::KNOWN_SET_CTRL_RADIOBUTTON_MAX_ITEMS) {
            return InstallationSettingEditConstants::ECOM_BUILD_TYPE_SELECT_SINGLE;
        }
        return InstallationSettingEditConstants::ECOM_BUILD_TYPE_RADIO;
    }

    protected function buildJsonString(mixed $value, string $optionKey): string
    {
        $output = '';
        try {
            $output = json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (\JsonException $e) {
            log_error(
                "Unexpected JSON exception occurred on building Web-ready config option value"
                . composeSuffix(
                    [
                        'optionKey' => $optionKey,
                        'value' => $value,
                        'message' => $e->getMessage()
                    ]
                )
            );
        }
        return $output;
    }
}
