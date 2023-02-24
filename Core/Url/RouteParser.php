<?php
/**
 * Pure from volatile dependencies and side effects
 *
 * SAM-6768: Pure route parser
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Url;

use Sam\Core\Application\Ui\Ui;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;

/**
 * Class RouteParser
 * @package Sam\Core\Url
 */
class RouteParser extends CustomizableClass
{
    use OptionalsTrait;

    public const OP_ADMIN_CONTROLLERS = 'adminControllers';
    public const OP_RESPONSIVE_CONTROLLERS = 'responsiveControllers';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals = [
     *     self::OP_ADMIN_CONTROLLERS => string[],
     *     self::OP_RESPONSIVE_CONTROLLERS => string[],
     * ]
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Extract controller name from url route. It should be available controller.
     * This logic doesn't do any assumtion regarding default controller.
     * It means, we don't consider '/' route as 'index' controller for responsive side,
     * and don't consider '/admin/' route as 'index' controller for admin side.
     *
     * @param string $url
     * @return string '' empty string when not found
     */
    public function extractControllerName(string $url): string
    {
        $urlPath = UrlParser::new()->extractPath($url);
        $urlPathParts = array_filter(explode('/', $urlPath));
        if (!count($urlPathParts)) {
            return '';
        }

        $first = array_shift($urlPathParts);
        $uiAdmin = Ui::new()->constructWebAdmin();
        if ($first === $uiAdmin->dir()) {
            $second = array_shift($urlPathParts);
            $controllerName = $second ?? '';
            $availableAdminControllers = $this->fetchOptional(self::OP_ADMIN_CONTROLLERS);
            if (!in_array($controllerName, $availableAdminControllers, true)) {
                $controllerName = '';
            }
        } else { // responsive side
            $controllerName = $first;
            $availableResponsiveControllers = $this->fetchOptional(self::OP_RESPONSIVE_CONTROLLERS);
            if (!in_array($controllerName, $availableResponsiveControllers, true)) {
                $controllerName = '';
            }
        }
        return $controllerName;
    }

    /**
     * First complete url to zend default controller (if it is incompleted), then extract controller general way
     * @param string $url
     * @return string
     */
    public function extractControllerNameConsideringZendDefault(string $url): string
    {
        $url = $this->completeUrlWithZendDefaultRouteParts($url);
        $controllerName = $this->extractControllerName($url);
        return $controllerName;
    }

    public function extractActionName(string $url): string
    {
        // TODO:
        $actionName = '';
        return $actionName;
    }

    public function extractActionNameConsideringZendDefault(string $rul): string
    {
        // TODO
        $actionName = '';
        return $actionName;
    }

    /**
     * It adds default routes, e.g. '/' becomes '/index/index', '/admin' becomes '/admin/index/index'
     * @param string $url
     * @return string
     */
    protected function completeUrlWithZendDefaultRouteParts(string $url): string
    {
        $parts = parse_url($url);
        $scheme = empty($parts['scheme']) ? '' : $parts['scheme'] . '://';
        $host = empty($parts['host']) ? '' : $parts['host'];
        $port = empty($parts['port']) ? '' : ':' . $parts['port'];
        $path = empty($parts['path']) ? '' : $parts['path'];
        $query = empty($parts['query']) ? '' : '?' . $parts['query'];
        $fragment = empty($parts['fragment']) ? '' : '#' . $parts['fragment'];

        $urlPathParts = array_filter(explode('/', $path));
        if (!$urlPathParts) {
            $path = '/' . Constants\ResponsiveRoute::DEFAULT_CONTROLLER
                . '/' . Constants\ResponsiveRoute::DEFAULT_ACTION;
        } else {
            $first = array_shift($urlPathParts);
            $uiAdmin = Ui::new()->constructWebAdmin();
            $adminDir = $uiAdmin->dir();
            if ($first === $adminDir) {
                $second = array_shift($urlPathParts);
                if (!$second) {
                    $path = '/' . $adminDir
                        . '/' . Constants\AdminRoute::DEFAULT_CONTROLLER
                        . '/' . Constants\AdminRoute::DEFAULT_ACTION;
                }
            }
        }
        $url = $scheme . $host . $port . $path . $query . $fragment;
        return $url;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_ADMIN_CONTROLLERS] = (array)($optionals[self::OP_ADMIN_CONTROLLERS]
            ?? Constants\AdminRoute::CONTROLLERS);
        $optionals[self::OP_RESPONSIVE_CONTROLLERS] = (array)($optionals[self::OP_RESPONSIVE_CONTROLLERS]
            ?? Constants\ResponsiveRoute::CONTROLLERS);
        $this->setOptionals($optionals);
    }
}
