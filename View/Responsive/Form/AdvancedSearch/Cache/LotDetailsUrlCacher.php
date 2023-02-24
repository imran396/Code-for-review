<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 15, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\Cache;

use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Load\AdvancedSearchLotDto;

/**
 * Class LotDetailsUrlCacher
 */
class LotDetailsUrlCacher extends CustomizableClass
{
    use UrlBuilderAwareTrait;

    private array $lotDetailsUrls = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return url to lot details page
     * @param AdvancedSearchLotDto $dto ['auction_id' => <a.id>, 'id' => <li.id>] optional. Pass null to get url with %s parameter replacers
     * @return string
     */
    public function getUrl(AdvancedSearchLotDto $dto): string
    {
        // can be null for unassigned lot
        $auctionId = $dto->auctionId ?: '%s';
        $lotItemId = $dto->lotItemId ?: '%s';
        $key = $auctionId . '_' . $lotItemId;
        if (!isset($this->lotDetailsUrls[$key])) {
            $seoUrl = $dto->lotSeoUrl;
            $url = $this->getUrlBuilder()->build(
                ResponsiveLotDetailsUrlConfig::new()->forWeb(
                    $lotItemId,
                    $auctionId,
                    $seoUrl,
                    [UrlConfigConstants::OP_ACCOUNT_ID => $dto->lotAccountId]
                )
            );
            $this->lotDetailsUrls[$key] = $url;
        }
        return $this->lotDetailsUrls[$key];
    }
}
