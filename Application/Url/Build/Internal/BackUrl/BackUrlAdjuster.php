<?php
/**
 * This service is intended to adjust passed url to view that we expect for url of "back-page url" kind.
 * This service decides, if we want to keep back-page url full or cut domain part to make it relative.
 * This is application layer service and it calls dependencies from application layer.
 *
 * SAM-5140: Url Builder class
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           10-13, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\BackUrl;

use Sam\Application\Application;
use Sam\Application\HttpRequest\ServerRequestReader;
use Sam\Application\Url\Build\Internal\BackUrl\Core\BackUrlPureAdjuster;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;

/**
 * Class BackUrlAdjuster
 * @package Sam\Application\Url\Build
 */
class BackUrlAdjuster extends CustomizableClass
{
    use OptionalsTrait;

    /** @var string  current application UI type (web or cli) */
    public const OP_UI = OptionalKeyConstants::KEY_UI;
    /** @var string schema-domain part of url from running web session */
    public const OP_HOST_URL = OptionalKeyConstants::KEY_HOST_URL;

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
     *     self::OP_UI => int,
     *     self::OP_DOMAIN_NAME => string,
     * ]
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param string $url
     * @param Ui $ui current application UI type (web or cli)
     * @param array $optionals = [
     *     self::OP_DOMAIN_NAME => string,
     * ]
     * @return string
     */
    public function adjustForUi(string $url, Ui $ui, array $optionals = []): string
    {
        $optionals[self::OP_UI] = $ui;
        $this->initOptionals($optionals);
        return $this->adjust($url);
    }

    /**
     * Expects a full url (with scheme and host) and transform it to relative view.
     *
     * @param string $url
     * @return string
     */
    public function adjust(string $url): string
    {
        return BackUrlPureAdjuster::new()->adjust(
            $url,
            $this->fetchOptional(self::OP_UI),
            $this->fetchOptional(self::OP_HOST_URL)
        );
    }

    /**
     * @param array $optionals
     * @return void
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_UI] = $optionals[self::OP_UI]
            ?? static function (): Ui {
                return Application::getInstance()->ui();
            };
        $optionals[self::OP_HOST_URL] = $optionals[self::OP_HOST_URL]
            ?? static function (): string {
                return ServerRequestReader::new()->hostUrl();
            };
        $this->setOptionals($optionals);
    }
}
