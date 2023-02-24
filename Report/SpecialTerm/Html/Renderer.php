<?php
/**
 * SAM-4634:Refactor special terms report
 * https://bidpath.atlassian.net/browse/SAM-4634
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/9/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\SpecialTerm\Html;

use Sam\Application\Url\Build\Config\AuctionLot\AdminLotEditUrlConfig;
use Sam\Application\Url\Build\Config\User\AdminUserEditUrlConfig;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Application\Url\UrlAdvisorAwareTrait;
use Sam\Core\Constants;
use Sam\Date\DateHelperAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Transform\Number\NumberFormatter;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Transform\Number\NumberFormatterInterface;

/**
 * Class Renderer
 * @package Sam\Report\SpecialTerm\Html
 */
class Renderer extends CustomizableClass
{
    use DateHelperAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use SystemAccountAwareTrait;
    use UrlAdvisorAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Overwrite for NumberFormatterAwareTrait::getNumberFormatter()
     * @return NumberFormatterInterface
     */
    public function getNumberFormatter(): NumberFormatterInterface
    {
        if ($this->numberFormatter === null) {
            $this->numberFormatter = NumberFormatter::new()->construct($this->getSystemAccountId());
        }
        return $this->numberFormatter;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderLotNo(array $row): string
    {
        $url = $this->getUrlBuilder()->build(
            AdminLotEditUrlConfig::new()->forWeb((int)$row['li_id'], (int)$row['auction_id'])
        );
        $url = $this->getUrlAdvisor()->addBackUrl($url);
        $lotNo = $this->getLotRenderer()->makeLotNo($row['lot_number'], $row['lot_number_ext'], $row['lot_number_prefix']);
        return '<a href="' . $url . '">' . $lotNo . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderItemNo(array $row): string
    {
        $url = $this->getUrlBuilder()->build(
            AdminLotEditUrlConfig::new()->forWeb((int)$row['li_id'], (int)$row['auction_id'])
        );
        $url = $this->getUrlAdvisor()->addBackUrl($url);
        $itemNo = $this->getLotRenderer()->makeItemNo($row['item_num'], $row['item_num_ext']);
        return '<a href="' . $url . '">' . $itemNo . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderLotName(array $row): string
    {
        $lotEditUrl = $this->getUrlBuilder()->build(
            AdminLotEditUrlConfig::new()->forWeb((int)$row['li_id'], (int)$row['auction_id'])
        );
        $lotEditUrl = $this->getUrlAdvisor()->addBackUrl($lotEditUrl);
        return '<a href="' . $lotEditUrl . '">' . ee($row['name']) . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderTerms(array $row): string
    {
        return $row['terms_and_conditions'];
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderBidderNum(array $row): string
    {
        $url = $this->getUrlBuilder()->build(
            AdminUserEditUrlConfig::new()->forWeb((int)$row['bidder_id'])
        );
        $url = $this->getUrlAdvisor()->addBackUrl($url);
        return '<a href="' . $url . '">' . ltrim($row['bidder_num'], "0") . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderBidderFirstName(array $row): string
    {
        $url = $this->getUrlBuilder()->build(
            AdminUserEditUrlConfig::new()->forWeb((int)$row['bidder_id'])
        );
        $url = $this->getUrlAdvisor()->addBackUrl($url);
        return '<a href="' . $url . '">' . ee($row['first_name']) . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderBidderLastName(array $row): string
    {
        $url = $this->getUrlBuilder()->build(
            AdminUserEditUrlConfig::new()->forWeb((int)$row['bidder_id'])
        );
        $url = $this->getUrlAdvisor()->addBackUrl($url);
        return '<a href="' . $url . '">' . ee($row['last_name']) . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderCustomerNo(array $row): string
    {
        return $this->getNumberFormatter()->format($row['customer_no']);
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderBidderUsername(array $row): string
    {
        $url = $this->getUrlBuilder()->build(
            AdminUserEditUrlConfig::new()->forWeb((int)$row['bidder_id'])
        );
        $url = $this->getUrlAdvisor()->addBackUrl($url);
        return '<a href="' . $url . '">' . ee($row['username']) . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderBidderEmail(array $row): string
    {
        $email = ee($row['email']);
        return $email;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderAgreedOn(array $row): string
    {
        $date = $this->getDateHelper()->convertUtcToSysByDateIso($row['agreed_on']);
        $output = $date ? $date->format(Constants\Date::ISO) : '';
        return $output;
    }

}
