<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           02/06/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render;

use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\Protect\Csrf\SynchronizerToken\Store\SynchronizerTokenProviderCreateTrait;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\InstallationSettingEditConstants;
use Sam\Installation\Config\Edit\Meta\ConfigNameAwareTrait;
use Sam\Installation\Config\Edit\Render\EditBlock\OptionKeyAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class DeleteBlockRenderer
 * @package Sam\Installation\Config
 * @author: Yura Vakulenko
 */
class DeleteBlockRenderer extends CustomizableClass
{
    use ConfigNameAwareTrait;
    use ConfigRepositoryAwareTrait;
    use OptionKeyAwareTrait;
    use SynchronizerTokenProviderCreateTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Build delete button form html for local config values
     * @param string $configName
     * @param string $optionKey
     * @return string
     */
    public function renderDeleteButton(string $configName, string $optionKey): string
    {
        $formActionUrl = $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_INSTALLATION_SETTING_DELETE)
        );
        $inputNameOptionKey = InstallationSettingEditConstants::HID_OPTION_KEY;
        $inputNameConfigName = InstallationSettingEditConstants::HID_CONFIG_NAME;
        $csrfToken = ee($this->createSynchronizerTokenProvider()->getSessionTokenOrCreate());
        $csrfTokenName = $this->cfg()->get('core->app->csrf->synchronizerToken->hiddenFieldName');

        $output = <<<HTML
<form action="$formActionUrl" method="post" class="actionForm ml-2 d-lg-inline-block">
    <input type="hidden" id="{$csrfTokenName}" name="{$csrfTokenName}" value="{$csrfToken}" />
    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
    <input type="hidden" value="{$optionKey}" name="{$inputNameOptionKey}">
    <input type="hidden" value="{$configName}" name="{$inputNameConfigName}">    
</form>
HTML;

        return $output;
    }

    /**
     * @param string $optionKey
     * @return string
     */
    public function renderDeleteCheckbox(string $optionKey): string
    {
        $multiDeleteClass = InstallationSettingEditConstants::CSS_CHKBOX_MULTIDELETE;
        $output = <<<HTML
<div class="form-check d-lg-inline-block mb-0 mr-2 {$multiDeleteClass}">
    <input class="form-check-input position-static"
           type="checkbox"
           value="{$optionKey}" aria-label="...">
</div>
HTML;
        return $output;
    }

    /**
     * @param string $configName
     * @return string
     */
    public function renderMultiDeleteForm(string $configName): string
    {
        $formActionUrl = $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_INSTALLATION_SETTING_DELETE)
        );
        $csrfToken = ee($this->createSynchronizerTokenProvider()->getSessionTokenOrCreate());
        $csrfTokenName = $this->cfg()->get('core->app->csrf->synchronizerToken->hiddenFieldName');
        $inputNameConfigName = InstallationSettingEditConstants::HID_CONFIG_NAME;
        $inputNameMultiDelete = Constants\Installation::MULTI_DELETE_POST_KEY;
        $multiDeleteInputId = Constants\Installation::MULTI_DELETE_INPUT_ID;


        $output = <<<HTML
<div class="multiDeleteFormWrap mt-3">
    <form action="{$formActionUrl}" method="post">
        <input type="hidden" id="{$csrfTokenName}" name="{$csrfTokenName}" value="{$csrfToken}" />
        <div class="text-center">
            <button type="submit" class="btn btn-success">Delete</button>
        </div>
        <input type="hidden" id="{$multiDeleteInputId}" name="{$inputNameMultiDelete}" value="">
        <input type="hidden" name="{$inputNameConfigName}" value="{$configName}">

    </form>
</div>
HTML;
        return $output;
    }
}
