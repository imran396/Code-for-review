<?php
/**
 * SAM-4630: Refactor document view report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-05-07
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\DocumentView\Html;

use Sam\Application\Url\Build\Config\User\AdminUserEditUrlConfig;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;

/**
 * Class Renderer
 */
class Renderer extends CustomizableClass
{
    use AuctionRendererAwareTrait;
    use DateHelperAwareTrait;
    use LotRendererAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderViewDate(array $row): string
    {
        $date = $this->getDateHelper()->convertUtcToSysByDateIso($row['date']);
        return $this->getDateHelper()->formattedDate($date);
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderItemNo(array $row): string
    {
        return $this->getLotRenderer()->makeItemNo($row['item_num'], $row['item_num_ext']);
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderAuction(array $row): string
    {
        $output = $this->getAuctionRenderer()->makeName($row['auction_name'], (bool)$row['test_auction']);
        $saleNo = $this->getAuctionRenderer()->makeSaleNo($row['sale_num'], $row['sale_num_ext']);
        if ($saleNo) {
            $output .= " ({$saleNo})";
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderUsername(array $row): string
    {
        $result = '';
        $userId = (int)$row['user_id'];
        $username = (string)$row['username'];
        if ($userId && $username) {
            $userUrl = $this->getUrlBuilder()->build(
                AdminUserEditUrlConfig::new()->forWeb($userId)
            );
            $result = '<a href="' . $userUrl . '">' . ee($username) . '</a>';
        }
        return $result;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderLotNo(array $row): string
    {
        return $this->getLotRenderer()->makeLotNo($row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix']);
    }
}
