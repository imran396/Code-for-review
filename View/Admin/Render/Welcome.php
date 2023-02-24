<?php
/**
 * Render user welcome menu
 *
 * SAM-4400: Refactor zf view helpers to customized classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 3, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Render;

use Sam\Application\LookAndFeel\Customization\Switch\LookAndFeelCustomizationTumblerCreateTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\Url\Build\Config\Base\OneBoolParamUrlConfig;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Application\Url\UrlAdvisorAwareTrait;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;
use Sam\View\Base\HtmlRenderer;

/**
 * Class Welcome
 * @package Sam\View\Admin\Render
 */
class Welcome extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use AuthIdentityManagerCreateTrait;
    use ConfigRepositoryAwareTrait;
    use EditorUserAwareTrait;
    use LookAndFeelCustomizationTumblerCreateTrait;
    use ParamFetcherForGetAwareTrait;
    use UrlAdvisorAwareTrait;
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
     * @return string
     */
    public function render(): string
    {
        if (
            !$this->createAuthIdentityManager()->isAuthorized()
            || !$this->getEditorUserAdminPrivilegeChecker()->isAdmin()
        ) {
            return '';
        }
        $isOn = $this->createLookAndFeelCustomizationTumbler()->isOn();
        if ($isOn) {
            $customizationMenu = $this->getAdminTranslator()->trans('welcome.menu.disable_customizations.label');
            $customizationUrlConfig = OneBoolParamUrlConfig::new()->forWeb(
                Constants\Url::A_ADMIN_HOME_ENABLE_CUSTOM_LOOK_AND_FEEL,
                false
            );
        } else {
            $customizationMenu = $this->getAdminTranslator()->trans('welcome.menu.re_enable_customizations.label');
            $customizationUrlConfig = OneBoolParamUrlConfig::new()->forWeb(
                Constants\Url::A_ADMIN_HOME_ENABLE_CUSTOM_LOOK_AND_FEEL,
                true
            );
        }

        $urlBuilder = $this->getUrlBuilder();
        $templateData = [
            'auctionsUrl' => $urlBuilder->build(
                ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_LIST)
            ),
            'homeUrl' => $urlBuilder->build(
                ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_ADMIN_HOME)
            ),
            'inventoryUrl' => $urlBuilder->build(
                ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_INVENTORY_ITEMS)
            ),
            'invoicesUrl' => $urlBuilder->build(
                ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_INVOICES_LIST)
            ),
            'logoutUrl' => $urlBuilder->build(
                ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_ADMIN_LOGOUT)
            ),
            'reportsUrl' => $urlBuilder->build(
                ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_REPORTS_LIST)
            ),
            'settingsUrl' => $urlBuilder->build(
                ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_SYSTEM_PARAMETER_DEFAULT)
            ),
            'settlementUrl' => $urlBuilder->build(
                ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_SETTLEMENTS_LIST)
            ),
            'username' => $this->getEditorUser()->Username,
            'usersUrl' => $urlBuilder->build(
                ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_USERS_LIST)
            ),
            'customizationMenu' => $customizationMenu,
            'customizationUrl' => $urlBuilder->build($customizationUrlConfig)
        ];
        return HtmlRenderer::new()->getTemplate(
            'index/welcome.tpl.php',
            $templateData,
            Ui::new()->constructWebAdmin()
        );
    }
}
