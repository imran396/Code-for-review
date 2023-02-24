<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\AuctionSeoUrl;

use Sam\Application\Url\Build\Config\Auction\AbstractResponsiveSingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionInfoUrlConfig;
use Sam\Auction\Load\AuctionDetailsCacheLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * Class AuctionSeoUrlAppender
 */
class AuctionSeoUrlAppender extends CustomizableClass
{
    use AuctionDetailsCacheLoaderAwareTrait;
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
     * Additional processing of links with seo url.
     * We add auction seo_url, if feature is enabled.
     * @param string $url
     * @param AbstractResponsiveSingleAuctionUrlConfig $urlConfig
     * @return string
     */
    public function append(string $url, AbstractResponsiveSingleAuctionUrlConfig $urlConfig): string
    {
        /**
         * Don't append auction seo url, when "auction info link" is defined for "auction info" page url.
         */
        if (
            $urlConfig instanceof ResponsiveAuctionInfoUrlConfig
            && $urlConfig->hasAuctionInfoLink()
        ) {
            return $url;
        }

        /**
         * Don't append auction seo url, when "seo friendly url" installation setting is disabled.
         */
        if (!$this->fetchOptional(self::OP_IS_SEO_FRIENDLY_URL)) {
            return $url;
        }

        $seoUrl = $urlConfig->seoUrl();
        if (
            $seoUrl === null
            && !$urlConfig->isTemplateView()
        ) {
            $seoUrl = $this->getAuctionDetailsCacheLoader()
                ->loadValue($urlConfig->auctionId(), Constants\AuctionDetailsCache::SEO_URL, true);
        }
        if ($seoUrl) {
            $url .= '/' . $seoUrl;
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
            };  // TODO: php7.4: ??=
        $this->setOptionals($optionals);
    }
}
