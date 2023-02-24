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

namespace Sam\Installation\Config\Edit\Render\EditBlock\EditComponent;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\InstallationSettingEditConstants;
use Sam\Installation\Config\Edit\Render\EditBlock\EditBlockRendererHelperCreateTrait;
use Sam\Installation\Config\Edit\Render\EditBlock\EditComponent\EditData\EditComponentData;
use Sam\Installation\Config\Edit\Render\EditBlock\EditComponent\HtmlRenderers\HtmlInputRendererForArray;
use Sam\Installation\Config\Edit\Render\EditBlock\EditComponent\HtmlRenderers\HtmlInputRendererForBoolean;
use Sam\Installation\Config\Edit\Render\EditBlock\EditComponent\HtmlRenderers\HtmlInputRendererForGeneral;
use Sam\Installation\Config\Edit\Render\EditBlock\EditComponent\HtmlRenderers\HtmlInputRendererForRadio;
use Sam\Installation\Config\Edit\Render\EditBlock\EditComponent\HtmlRenderers\HtmlInputRendererForSelect;
use Sam\Installation\Config\Edit\Render\EditBlock\EditComponent\HtmlRenderers\HtmlInputRendererForStructArray;

/**
 * Class EditComponentBuilder
 * @package Sam\Installation\Config
 */
class EditComponentInputRenderer extends CustomizableClass
{
    use EditBlockRendererHelperCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Main builder for Html, Edit component id and Edit component data type.
     * @param EditComponentData $ecData
     * @return string
     */
    public function render(EditComponentData $ecData): string
    {
        $output = '';
        if ($ecData->getBuildType()) {
            $inputData = $ecData->getInputData();
            $rendererHelper = $this->createEditBlockRendererHelper();
            $editComponentTypeFromDescriptor = $rendererHelper->fetchEditComponentTypeFromDescriptor($inputData);
            $useMetaKnownSet = $rendererHelper->fetchMetaKnownSetForEditComponent($inputData);
            $editComponentBuildType = $rendererHelper->fetchEditComponentBuildType($editComponentTypeFromDescriptor, count($useMetaKnownSet));

            if ($editComponentBuildType === InstallationSettingEditConstants::ECOM_BUILD_TYPE_UNKNOWN) {
                return 'Unknown edit component type';
            }

            if ($editComponentBuildType === InstallationSettingEditConstants::ECOM_BUILD_TYPE_GENERAL) {
                return $this->renderEditComponentInputGeneral($ecData);
            }

            if ($editComponentBuildType === InstallationSettingEditConstants::ECOM_BUILD_TYPE_BOOLEAN) {
                return $this->renderEditComponentInputBoolean($ecData);
            }

            if ($editComponentBuildType === InstallationSettingEditConstants::ECOM_BUILD_TYPE_ARRAY) {
                return $this->renderEditComponentInputArray($ecData);
            }

            if ($editComponentBuildType === InstallationSettingEditConstants::ECOM_BUILD_TYPE_STRUCT_ARRAY) {
                return $this->renderEditComponentInputStructArray($ecData);
            }

            if ($editComponentBuildType === InstallationSettingEditConstants::ECOM_BUILD_TYPE_SELECT_SINGLE) {
                return $this->renderEditComponentInputSelect($ecData, Constants\Installation::ECOM_SELECT_TYPE_SINGLE);
            }

            if ($editComponentBuildType === InstallationSettingEditConstants::ECOM_BUILD_TYPE_SELECT_MULTIPLE) {
                return $this->renderEditComponentInputSelect($ecData, Constants\Installation::ECOM_SELECT_TYPE_MULTIPLE);
            }

            if ($editComponentBuildType === InstallationSettingEditConstants::ECOM_BUILD_TYPE_RADIO) {
                return $this->renderEditComponentInputRadio($ecData);
            }
        }

        return $output;
    }

    /**
     * @param EditComponentData $ecData
     * @return string
     */
    protected function renderEditComponentInputGeneral(EditComponentData $ecData): string
    {
        $buildType = $ecData->getBuildType();
        if ($buildType === Constants\Installation::ECOM_BUILDER_TYPE_HTML) {
            return HtmlInputRendererForGeneral::new()->render($ecData);
        }
        if ($buildType === Constants\Installation::ECOM_BUILDER_TYPE_ID) {
            return sprintf(InstallationSettingEditConstants::CID_EB_TXT_TPL, $ecData->getInputId());
        }
        return '';
    }

    /**
     * @param EditComponentData $ecData
     * @return string
     */
    protected function renderEditComponentInputBoolean(EditComponentData $ecData): string
    {
        $buildType = $ecData->getBuildType();
        if ($buildType === Constants\Installation::ECOM_BUILDER_TYPE_HTML) {
            return HtmlInputRendererForBoolean::new()->render($ecData);
        }
        if ($buildType === Constants\Installation::ECOM_BUILDER_TYPE_ID) {
            return sprintf(InstallationSettingEditConstants::CID_EB_RAD_TPL, $ecData->getInputId());
        }
        return '';
    }

    /**
     * @param EditComponentData $ecData
     * @return string
     */
    protected function renderEditComponentInputArray(EditComponentData $ecData): string
    {
        $buildType = $ecData->getBuildType();
        if ($buildType === Constants\Installation::ECOM_BUILDER_TYPE_HTML) {
            return HtmlInputRendererForArray::new()->render($ecData);
        }
        if ($buildType === Constants\Installation::ECOM_BUILDER_TYPE_ID) {
            return sprintf(InstallationSettingEditConstants::CID_EB_TEA_TPL, $ecData->getInputId());
        }
        return '';
    }

    /**
     * @param EditComponentData $ecData
     * @return string
     */
    protected function renderEditComponentInputStructArray(EditComponentData $ecData): string
    {
        $buildType = $ecData->getBuildType();
        if ($buildType === Constants\Installation::ECOM_BUILDER_TYPE_HTML) {
            return HtmlInputRendererForStructArray::new()->render($ecData);
        }
        if ($buildType === Constants\Installation::ECOM_BUILDER_TYPE_ID) {
            return sprintf(InstallationSettingEditConstants::CID_EB_STRUCT_TEA_TPL, $ecData->getInputId());
        }
        return '';
    }

    /**
     * @param EditComponentData $ecData
     * @param string $selectBoxType
     * @return string
     */
    protected function renderEditComponentInputSelect(EditComponentData $ecData, string $selectBoxType): string
    {
        $buildType = $ecData->getBuildType();
        if ($buildType === Constants\Installation::ECOM_BUILDER_TYPE_HTML) {
            return HtmlInputRendererForSelect::new()->render($ecData, $selectBoxType);
        }
        if ($buildType === Constants\Installation::ECOM_BUILDER_TYPE_ID) {
            return sprintf(InstallationSettingEditConstants::CID_EB_LST_TPL, $ecData->getInputId());
        }
        return '';
    }

    /**
     * @param EditComponentData $ecData
     * @return string
     */
    protected function renderEditComponentInputRadio(EditComponentData $ecData): string
    {
        $buildType = $ecData->getBuildType();
        if ($buildType === Constants\Installation::ECOM_BUILDER_TYPE_HTML) {
            return HtmlInputRendererForRadio::new()->render($ecData);
        }
        if ($buildType === Constants\Installation::ECOM_BUILDER_TYPE_ID) {
            return sprintf(InstallationSettingEditConstants::CID_EB_RAD_TPL, $ecData->getInputId());
        }
        return '';
    }
}
