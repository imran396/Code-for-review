<?php
/**
 * SAM-11530 : Auction Sales group : Groups are getting unlinked if user creates new group with same name
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 09, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionSettingForm\SaleGroup\Edit\Render;

use Auction;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Auction\SaleGroup\SaleGroupManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Qform\Control\ListBox\ListBoxOption;

/**
 * Class AuctionSettingSaleGroupEditingRenderer
 * @package Sam\View\Admin\Form\AuctionSettingForm\SaleGroup\Edit\Render
 */
class AuctionSettingSaleGroupEditingRenderer extends CustomizableClass
{
    use AuctionRendererAwareTrait;
    use SaleGroupManagerAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function buildAuctionListBoxOptions(Auction $auction): array
    {
        $auctionRows = $this->getSaleGroupManager()->loadAuctionRows($auction->SaleGroup, $auction->AccountId, true);
        $listBoxOptions = [];
        foreach ($auctionRows as $auctionRow) {
            if ($auction->Id === (int)$auctionRow['id']) {
                continue;
            }
            $editUrl = $this->getUrlBuilder()->build(
                AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_EDIT, (int)$auctionRow['id'])
            );
            $saleNo = $this->getAuctionRenderer()->makeSaleNo($auctionRow['sale_num'], $auctionRow['sale_num_ext']);
            $name = $this->getAuctionRenderer()->makeName($auctionRow['name'], (bool)$auctionRow['test_auction']);
            $line = '&nbsp;&nbsp;<a href="' . $editUrl . '">' . $name . ' ' . $saleNo . ' - ' . $auctionRow['created_on'] . '</a>';
            $listBoxOptions[] = new ListBoxOption((int)$auctionRow['id'], $line, $auctionRow['selected']);
        }
        return $listBoxOptions;
    }
}
