<?php
/**
 * SAM-7717: Refactor admin menu tabs rendering module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\MainMenu\Internal\MenuItem;

use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\ViewHelper\MainMenu\AdminMainMenuItem;

/**
 * Class MenuItemsBuilder
 * @package Sam\View\Admin\ViewHelper\MainMenu\Internal\MenuItem
 * @internal
 */
class MenuItemsBuilder extends CustomizableClass
{
    use AdminMenuItemTranslatorCreateTrait;
    use MenuItemPrivilegeCheckerCreateTrait;
    use MenuItemsConfigProviderCreateTrait;
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
     * @param int $userId
     * @param string $controller
     * @return array
     */
    public function build(int $userId, string $controller): array
    {
        $menuItemsConfig = $this->createMenuItemsConfigProvider()
            ->construct()
            ->getMenuItemsConfig();
        $translator = $this->createAdminMenuItemTranslator();
        $privilegeChecker = $this->createMenuItemPrivilegeChecker()->construct($userId);
        $menuItems = [];
        foreach ($menuItemsConfig as $menuItemName => $menuItemConfig) {
            if ($privilegeChecker->hasPrivilege($menuItemConfig['privilege'])) {
                $menuItems[] = AdminMainMenuItem::new()->construct(
                    $menuItemName,
                    $translator->translateName($menuItemName),
                    $translator->translateTitle($menuItemName),
                    $this->buildUrl($menuItemConfig['url']),
                    in_array($controller, $menuItemConfig['controllers'], true)
                );
            }
        }
        return $menuItems;
    }

    /**
     * @param int $urlType
     * @return string
     */
    protected function buildUrl(int $urlType): string
    {
        return $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forWeb($urlType)
        );
    }
}
