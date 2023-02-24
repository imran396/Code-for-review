<?php
/**
 * Render admin main menu
 *
 * SAM-4400: Refactor zf view helpers to customized classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 27, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\MainMenu;

use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\View\Admin\ViewHelper\MainMenu\Internal\MenuItem\MenuItemsBuilder;
use Sam\View\Base\HtmlRenderer;

/**
 * Class AdminMainMenu
 * @package Sam\View\Admin\ViewHelper
 */
class AdminMainMenu extends CustomizableClass
{
    use EditorUserAwareTrait;
    use UserLoaderAwareTrait;
    use ParamFetcherForRouteAwareTrait;

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
        if (!$this->hasEditorUserAdminRole()) {
            return '';
        }
        $controllerName = $this->getParamFetcherForRoute()->getControllerName();
        $menuItemsBuilder = MenuItemsBuilder::new();
        return HtmlRenderer::new()->getTemplate(
            'index/admin-tab.tpl.php',
            [
                'menuItems' => $menuItemsBuilder->build($this->getEditorUserId(), $controllerName),
            ],
            Ui::new()->constructWebAdmin()
        );
    }
}
