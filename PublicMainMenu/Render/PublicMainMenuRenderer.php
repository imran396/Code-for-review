<?php
/**
 * Render main menu
 *
 * SAM-6767: Responsive Main Menu rendering module adjustments for v3.5
 * SAM-4400: Refactor zf view helpers to customized classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 21, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\PublicMainMenu\Render;

use Sam\Core\Application\Ui\Ui;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\PublicMainMenu\Render\Internal\MenuItem\MenuItemsBuilder;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\View\Base\HtmlRenderer;
use Sam\PublicMainMenu\Item\Order\PublicMainMenuItemOrdererCreateTrait;
use User;
use UserInfo;

/**
 * Class MainMenu
 * @package Sam\PublicMainMenu\Render
 */
class PublicMainMenuRenderer extends CustomizableClass
{
    use EditorUserAwareTrait;
    use ParamFetcherForRouteAwareTrait;
    use PublicMainMenuItemOrdererCreateTrait;
    use SystemAccountAwareTrait;
    use TranslatorAwareTrait;

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
        $menuItems = MenuItemsBuilder::new()->build(
            $this->getSystemAccountId(),
            $this->getEditorUserId(),
            $this->getParamFetcherForRoute()->getControllerName()
        );
        $menuItems = $this->createPublicMainMenuItemOrderer()->sort($menuItems, $this->getSystemAccountId());

        return HtmlRenderer::new()->getTemplate(
            'index/main-menu.tpl.php',
            [
                'menuItems' => $menuItems,
                'welcomeText' => $this->createWelcomeText(),
            ],
            Ui::new()->constructWebResponsive()
        );
    }

    /**
     * Create welcome text
     * @return string
     */
    protected function createWelcomeText(): string
    {
        $name = $this->renderNameForLoginButton($this->getEditorUserInfo(), $this->getEditorUser());
        return HtmlRenderer::new()->p(
            $this->getTranslator()->translate('GENERAL_WELCOME', 'general') . ', ' . ee($name),
            ['class' => 'welcome-text']
        );
    }

    /**
     * Render name for login button
     * @param UserInfo|null $userInfo
     * @param User|null $user
     * @return string
     */
    protected function renderNameForLoginButton(?UserInfo $userInfo = null, ?User $user = null): string
    {
        $output = '';
        if ($userInfo) {
            $output = $userInfo->CompanyName
                ? "$userInfo->FirstName ({$userInfo->CompanyName})"
                : $userInfo->FirstName;
        }
        if (!$output && $user) {
            $output = $user->Username;
        }
        return trim($output);
    }
}
