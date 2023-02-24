<?php
/**
 * SAM-9610: Response with "Bad Request" when detected unexpected request parameter value,
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           09-08, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\LotList\Barcode\Url;

use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\AdminLotEditUrlConfig;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\Config\LotItem\AnySingleLotItemUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\AuctionLot\Validate\AuctionLotExistenceCheckerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;

/**
 * Class LotUrlBuilderByBarcode
 * @package Sam\Qform\LotList
 */
class LotUrlBuilderByBarcode extends CustomizableClass
{
    use AuctionLotExistenceCheckerAwareTrait;
    use LotCustomDataLoaderCreateTrait;
    use ParamFetcherForRouteAwareTrait;
    use UrlBuilderAwareTrait;
    use UrlParserAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function buildUrl(string $barcode, int $lotCustomFieldId): string
    {
        $isManageAuctionsController = $this->isManageAuctionsControllerInRequestRoute();
        $auctionId = $isManageAuctionsController
            ? $this->getParamFetcherForRoute()->getIntPositive(Constants\UrlParam::R_ID)
            : null;
        $lotItemId = $this->createLotCustomDataLoader()->detectLotItemIdByBarcode($lotCustomFieldId, $barcode);

        if (!$lotItemId) {
            return $this->buildLotCreateUrl($isManageAuctionsController, $auctionId, $barcode);
        }

        return $this->buildLotEditUrl($isManageAuctionsController, $auctionId, $lotItemId, $lotCustomFieldId);
    }

    protected function buildLotCreateUrl(bool $isManageAuctionsController, ?int $auctionId, string $barcode): string
    {
        $params = [];
        if ($isManageAuctionsController) {
            $url = $this->getUrlBuilder()->build(
                AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::A_AUCTIONS_LOT_CREATE, $auctionId)
            );
        } else {
            $url = $this->getUrlBuilder()->build(
                ZeroParamUrlConfig::new()->forRedirect(Constants\Url::A_INVENTORY_LOT_CREATE)
            );
        }
        $params[Constants\UrlParam::BARCODE] = $barcode;

        $url = $this->getUrlParser()->replaceParams($url, $params);
        return $url;
    }

    protected function buildLotEditUrl($isManageAuctionsController, ?int $auctionId, int $lotItemId, int $lotCustomFieldId): string
    {
        $params = [];
        $url = $this->getUrlBuilder()->build(
            AnySingleLotItemUrlConfig::new()->forRedirect(Constants\Url::A_INVENTORY_LOT_EDIT, $lotItemId)
        );
        if (
            $isManageAuctionsController
            && $this->getAuctionLotExistenceChecker()->exist($lotItemId, $auctionId, true)
        ) {
            $url = $this->getUrlBuilder()->build(
                AdminLotEditUrlConfig::new()->forRedirect($auctionId, $auctionId)
            );
        }
        $params[Constants\UrlParam::FIELD] = $lotCustomFieldId;

        $url = $this->getUrlParser()->replaceParams($url, $params);
        return $url;
    }

    protected function isManageAuctionsControllerInRequestRoute(): bool
    {
        $paramFetcherForRoute = $this->getParamFetcherForRoute();
        $controllerName = $paramFetcherForRoute->getControllerName();
        return $controllerName === Constants\AdminRoute::C_MANAGE_AUCTIONS;
    }
}
