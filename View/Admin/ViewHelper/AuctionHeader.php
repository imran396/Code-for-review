<?php
/**
 * Render auction header
 *
 * SAM-4400: Refactor zf view helpers to customized classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 27, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper;

use Sam\Core\Service\CustomizableClass;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

/**
 * Class AuctionHeader
 * @package Sam\View\Admin\ViewHelper
 */
class AuctionHeader extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use ParamFetcherForRouteAwareTrait;
    use TimezoneLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $auctionId = $this->getParamFetcherForRoute()->getIntPositive(Constants\UrlParam::R_ID);
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            return '';
        }
        $auctionRenderer = $this->getAuctionRenderer();
        return sprintf(
            'Sale %s - %s - %s',
            $auctionRenderer->renderSaleNo($auction),
            $auctionRenderer->renderName($auction),
            $auctionRenderer->renderDates($auction)
        );
    }
}
