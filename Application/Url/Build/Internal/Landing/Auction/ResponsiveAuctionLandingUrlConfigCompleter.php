<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\Landing\Auction;

use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionFirstLotUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionInfoUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionLandingUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveCatalogUrlConfig;
use Sam\Application\Url\Build\Internal\Resolve\AccountFromUrlConfigResolverCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Settings\SettingsManager;

/**
 * Class AuctionLandingUrlConfigCompleter
 * @package Sam\Application\Url
 */
class ResponsiveAuctionLandingUrlConfigCompleter extends CustomizableClass
{
    use AccountFromUrlConfigResolverCreateTrait;
    use OptionalsTrait;

    // seta.auction_links_to
    public const AUCTION_LINKS_TO = 'auctionLinksTo';

    /** @var int[] */
    private const URL_TYPES_PER_AUCTION_LINKS_TO = [
        Constants\SettingAuction::AUCTION_LINK_TO_INFO => Constants\Url::P_AUCTIONS_INFO,
        Constants\SettingAuction::AUCTION_LINK_TO_CATALOG => Constants\Url::P_AUCTIONS_CATALOG,
        Constants\SettingAuction::AUCTION_LINK_TO_FIRST_LOT => Constants\Url::P_AUCTIONS_FIRST_LOT,
    ];

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
     *     self::AUCTION_LINKS_TO => int, // seta.auction_links_to
     * ]
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param ResponsiveAuctionLandingUrlConfig $sourceUrlConfig
     * @return ResponsiveAuctionFirstLotUrlConfig|ResponsiveAuctionInfoUrlConfig|ResponsiveCatalogUrlConfig
     */
    public function complete(
        ResponsiveAuctionLandingUrlConfig $sourceUrlConfig
    ): ResponsiveAuctionFirstLotUrlConfig|ResponsiveCatalogUrlConfig|ResponsiveAuctionInfoUrlConfig {
        $targetUrlType = $this->detectAuctionLinksTo($sourceUrlConfig);
        if ($targetUrlType === Constants\Url::P_AUCTIONS_FIRST_LOT) {
            $targetUrlConfig = ResponsiveAuctionFirstLotUrlConfig::new();
        } elseif ($targetUrlType === Constants\Url::P_AUCTIONS_CATALOG) {
            $targetUrlConfig = ResponsiveCatalogUrlConfig::new();
        } else {
            $targetUrlConfig = ResponsiveAuctionInfoUrlConfig::new();
        }
        $targetUrlConfig->fromConfig($sourceUrlConfig);
        return $targetUrlConfig;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::AUCTION_LINKS_TO] = $optionals[self::AUCTION_LINKS_TO]
            ?? static function (int $accountId) {
                $auctionLinksTo = SettingsManager::new()
                    ->get(Constants\Setting::AUCTION_LINKS_TO, $accountId);
                return trim($auctionLinksTo);
            };
        $this->setOptionals($optionals);
    }

    /**
     * Returns integer Link type depending on system setting
     * @param ResponsiveAuctionLandingUrlConfig $urlConfig
     * @return int
     */
    protected function detectAuctionLinksTo(ResponsiveAuctionLandingUrlConfig $urlConfig): int
    {
        $accountId = $this->detectAccountId($urlConfig);
        $auctionLinksTo = $this->fetchOptional(self::AUCTION_LINKS_TO, [$accountId]);
        $urlType = self::URL_TYPES_PER_AUCTION_LINKS_TO[$auctionLinksTo] ?? Constants\Url::P_AUCTIONS_INFO;
        return $urlType;
    }

    /**
     * Search for account id in url config optionals or try to resolve according to url param related entity
     * @param ResponsiveAuctionLandingUrlConfig $urlConfig
     * @return int|null
     */
    protected function detectAccountId(ResponsiveAuctionLandingUrlConfig $urlConfig): ?int
    {
        $accountId = $this->createAccountFromUrlConfigResolver()->detectAccountId($urlConfig);
        return $accountId;
    }
}
