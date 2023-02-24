<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\LotSeoUrl;

use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\AuctionLot\Cache\SeoUrl\Load\AuctionLotCacheSeoUrlLoaderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * Class LotSeoUrlAdjuster
 */
class LotSeoUrlAppender extends CustomizableClass
{
    use AuctionLotCacheSeoUrlLoaderCreateTrait;
    use OptionalsTrait;

    public const OP_IS_SEO_FRIENDLY_URL = OptionalKeyConstants::KEY_IS_SEO_FRIENDLY_URL;

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
     *     self::OP_IS_SEO_FRIENDLY_URL => bool,
     * ]
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param string $url
     * @param ResponsiveLotDetailsUrlConfig $urlConfig
     * @return string
     */
    public function append(string $url, ResponsiveLotDetailsUrlConfig $urlConfig): string
    {
        if ($this->fetchOptional(self::OP_IS_SEO_FRIENDLY_URL)) {
            // we have passed seoUrl argument (it can be null - means dropped, '' - means empty)
            $seoUrl = $urlConfig->seoUrl();
            if (
                $seoUrl === null
                && !$urlConfig->isTemplateView()
            ) {
                $seoUrl = $this->createAuctionLotCacheSeoUrlLoader()
                    ->load($urlConfig->lotItemId(), $urlConfig->auctionId(), true);
            }
            if ($seoUrl) {
                $url .= '/' . $seoUrl;
            }
        }
        return $url;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IS_SEO_FRIENDLY_URL] = $optionals[self::OP_IS_SEO_FRIENDLY_URL]
            ?? static function () {
                return ConfigRepository::getInstance()->get('core->app->seoFriendlyUrlEnabled');
            };
        $this->setOptionals($optionals);
    }
}
