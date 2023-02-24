<?php
/**
 * SAM-9573: Refactor admin secondary menu for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 1, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BidIncrementMenu;

use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BidIncrementMenu\Internal\Load\DataProviderCreateTrait;

/**
 * Class BidIncrementMenuItemBuilder
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BidIncrememntMenu
 */
class BidIncrementMenuItemBuilder extends CustomizableClass
{
    use DataProviderCreateTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $systemAccountId
     * @return array
     */
    public function build(int $systemAccountId): array
    {
        return [
            'items' => $this->buildMenuItems($systemAccountId),
        ];
    }

    /**
     * @param int $accountId
     * @return array<array<string, string>>
     */
    protected function buildMenuItems(int $accountId): array
    {
        $menuItems = [];

        $availableTypes = $this->createDataProvider()->getAvailableTypes($accountId);
        foreach ($availableTypes as $type) {
            if (in_array($type, Constants\Auction::AUCTION_TYPES, true)) {
                $url = $this->buildUrlByAuctionType($type);
                $menuItems[] = [
                    'label' => Constants\Auction::$auctionTypeLabels[$type],
                    'url' => $url
                ];
            }
        }
        return $menuItems;
    }

    /**
     * @param string $type
     * @return string
     */
    protected function buildUrlByAuctionType(string $type): string
    {
        // Hybrid auction url
        if ($type === Constants\Auction::HYBRID) {
            $url = $this->getUrlBuilder()->build(
                ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_BID_INCREMENT_HYBRID_AUCTION)
            );
            return $url;
        }

        // Live auction url
        if ($type === Constants\Auction::LIVE) {
            $url = $this->getUrlBuilder()->build(
                ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_BID_INCREMENT_LIVE_AUCTION)
            );
            return $url;
        }

        // Timed auction url
        if ($type === Constants\Auction::TIMED) {
            $url = $this->getUrlBuilder()->build(
                ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_MANAGE_BID_INCREMENT_TIMED_AUCTION)
            );
            return $url;
        }

        return '';
    }
}
