<?php
/**
 * SAM-4824: Encapsulate $_SERVER access
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           05-18, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\RequestParam\Route;

use Sam\Application\Application;
use Sam\Application\HttpRequest\ServerRequestReader;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Url\UrlParserAwareTrait;
use Zend_Controller_Front;

/**
 * Class RequestRouteParser
 * @package Sam\Application\RequestParam\Route
 */
class RequestRouteParser extends CustomizableClass
{
    use OptionalsTrait;
    use UrlParserAwareTrait;

    public const OP_UI = OptionalKeyConstants::KEY_UI; // Ui
    public const OP_REQUEST_URI = OptionalKeyConstants::KEY_REQUEST_URI; // string

    protected ?string $controllerName = null;
    protected ?string $actionName = null;
    protected ?array $routeParams = null;

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
     *     self::OP_UI => (Ui),
     *     self::OP_REQUEST_URI => (string),
     * ]
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Get request controller name
     * @return string
     */
    public function detectControllerName(): string
    {
        if ($this->controllerName === null) {
            $this->parse();
        }
        return $this->controllerName;
    }

    /**
     * Get request action name
     * @return string|null
     */
    public function detectActionName(): ?string
    {
        if ($this->actionName === null) {
            $this->parse();
        }
        return $this->actionName;
    }

    /**
     * Get request route parameters
     * @return array
     */
    public function detectParams(): array
    {
        if ($this->routeParams === null) {
            $this->parse();
        }
        return $this->routeParams;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_UI] = $optionals[self::OP_UI]
            ?? Application::getInstance()->ui();
        $optionals[self::OP_REQUEST_URI] = $optionals[self::OP_REQUEST_URI]
            ?? ServerRequestReader::new()->requestUri();
        $this->setOptionals($optionals);
    }

    protected function parse(): void
    {
        /** @var Ui $ui */
        $ui = $this->fetchOptional(self::OP_UI);
        $requestUri = $this->fetchOptional(self::OP_REQUEST_URI);
        $requestUri = $this->getUrlParser()->removeQueryString($requestUri);
        $requestUri = trim($requestUri, '/');
        if ($ui->isWebAdmin()) {
            $basePath = '/' . $ui->dir();
            $requestUri = substr($requestUri, strlen($basePath));
        }
        $parts = explode('/', $requestUri);
        $this->controllerName = $parts[0] ?: Constants\ResponsiveRoute::C_INDEX;
        $this->actionName = $parts[1] ?? Constants\ResponsiveRoute::AIND_INDEX;
        $this->routeParams = [];
        for ($i = 2, $cnt = count($parts); $i < $cnt; $i += 2) {
            $this->routeParams[$parts[$i]] = isset($parts[$i + 1]) ? urldecode($parts[$i + 1]) : '';
        }

        // TODO: this is ZF dependency should be adjusted after migration to next framework
        $this->adjustForForwarding();
    }

    /**
     * This is ZF related logic, we need to call it to track forwarding to another controller and action.
     */
    protected function adjustForForwarding(): void
    {
        /** @var Zend_Controller_Front|null $front */
        $front = Zend_Controller_Front::getInstance();
        $request = $front?->getRequest();
        if ($request) {
            $this->controllerName = $request->getControllerName();
            $this->actionName = $request->getActionName();
        }
    }
}
