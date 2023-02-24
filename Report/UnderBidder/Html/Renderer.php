<?php
/**
 * SAM-4636: Refactor under bidders report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-04-19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\UnderBidder\Html;

use Sam\Application\Url\Build\Config\AuctionLot\AdminLotEditUrlConfig;
use Sam\Application\Url\Build\Config\User\AdminUserEditUrlConfig;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Application\Url\UrlAdvisorAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class Renderer
 */
class Renderer extends CustomizableClass
{
    use BidderNumPaddingAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use UrlAdvisorAwareTrait;
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
    public function renderAuctionLot(array $row): string
    {
        $lotEditUrl = $this->getUrlBuilder()->build(
            AdminLotEditUrlConfig::new()->forWeb((int)$row['li_id'], (int)$row['a_id'])
        );
        $lotEditUrl = $this->getUrlAdvisor()->addBackUrl($lotEditUrl);
        return '<a href="' . $lotEditUrl . '">' . $this->getLotRenderer()->makeLotNo($row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix']) . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderAuctionLotName(array $row): string
    {
        $lotEditUrl = $this->getUrlBuilder()->build(
            AdminLotEditUrlConfig::new()->forWeb((int)$row['li_id'], (int)$row['a_id'])
        );
        $lotEditUrl = $this->getUrlAdvisor()->addBackUrl($lotEditUrl);
        return '<a href="' . $lotEditUrl . '">' . ee($row['li_name']) . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderHammerPrice(array $row): string
    {
        $hammerPrice = Cast::toFloat($row['li_hp']);
        return LotSellInfoPureChecker::new()->isHammerPrice($hammerPrice)
            ? $this->getNumberFormatter()->formatMoney($hammerPrice)
            : '';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderBidderNum(array $row): string
    {
        $url = $this->getUrlBuilder()->build(
            AdminUserEditUrlConfig::new()->forWeb((int)$row['wb_id'])
        );
        $url = $this->getUrlAdvisor()->addBackUrl($url);
        $bidderNum = $this->getBidderNumberPadding()->clearForUnderBidder((string)$row['wb_bidder_num']);
        return '<a href="' . $url . '">' . $bidderNum . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderUsername(array $row): string
    {
        $url = $this->getUrlBuilder()->build(
            AdminUserEditUrlConfig::new()->forWeb((int)$row['wb_id'])
        );
        $url = $this->getUrlAdvisor()->addBackUrl($url);
        return '<a href="' . $url . '">' . ee($row['wb_username']) . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderAuctionBidderNum(array $row): string
    {
        $url = $this->getUrlBuilder()->build(
            AdminUserEditUrlConfig::new()->forWeb((int)$row['ub_id'])
        );
        $url = $this->getUrlAdvisor()->addBackUrl($url);
        $bidderNum = $this->getBidderNumberPadding()->clearForUnderBidder((string)$row['ub_bidder_num']);
        return '<a href="' . $url . '">' . $bidderNum . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderUnderBidderName(array $row): string
    {
        $url = $this->getUrlBuilder()->build(
            AdminUserEditUrlConfig::new()->forWeb((int)$row['ub_id'])
        );
        $url = $this->getUrlAdvisor()->addBackUrl($url);
        return '<a href="' . $url . '">' . ee($row['ub_username']) . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderAmount(array $row): string
    {
        return $this->getNumberFormatter()->formatMoney($row['amount']);
    }
}
