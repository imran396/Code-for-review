<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 * SAM-6767: Fix feature defined by "Stay on account domain" system parameter
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\ForceMain;

use Sam\Application\Url\UrlAdvisor;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Url\UrlParser;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * Class MainDomainApplier
 * @package Sam\Application\Url\Build\Internal\ForceMain
 */
class MainDomainApplier extends CustomizableClass
{
    use OptionalsTrait;

    // Main account host
    public const OP_HTTP_HOST = OptionalKeyConstants::KEY_HTTP_HOST;
    public const OP_SCHEME = OptionalKeyConstants::KEY_SCHEME;

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
     *     self::OP_HTTP_HOST => string,
     *     self::OP_SCHEME => string,
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
     * @return string
     */
    public function apply(string $url): string
    {
        $urlParser = UrlParser::new();
        $host = $this->fetchOptional(self::OP_HTTP_HOST);
        $url = $urlParser->replaceHost($url, $host);
        $url = $urlParser->replaceScheme($url, $this->fetchOptional(self::OP_SCHEME));
        return $url;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_HTTP_HOST] = $optionals[self::OP_HTTP_HOST]
            ?? static function (): string {
                return ConfigRepository::getInstance()->get('core->app->httpHost');
            };
        $optionals[self::OP_SCHEME] = $optionals[self::OP_SCHEME]
            ?? static function () {
                return UrlAdvisor::new()->detectScheme();
            };

        $this->setOptionals($optionals);
    }
}
