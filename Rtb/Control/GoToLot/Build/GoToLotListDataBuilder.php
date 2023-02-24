<?php
/**
 * SAM-6433: Refactor logic for Go to lot list of rtb clerk console
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Control\GoToLot\Build;

use Auction;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Control\GoToLot\Load\GoToLotListDataLoader;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Core\Constants;

/**
 * Class GoToLotListRenderer
 * @package ${NAMESPACE}
 */
class GoToLotListDataBuilder extends CustomizableClass
{
    use LotRendererAwareTrait;
    use SettingsManagerAwareTrait;

    protected ?GoToLotListDataLoader $goToLotListDataLoader = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return lot list for Go To function. Lots are separated by "|"
     * @param Auction $auction
     * @return string
     */
    public function build(Auction $auction): string
    {
        $lotList = 'Skip to Lot:|';
        $isEnableConsignorCompanyClerking = $this->getSettingsManager()
            ->getForMain(Constants\Setting::ENABLE_CONSIGNOR_COMPANY_CLERKING);
        $rows = $this->getGoToLotListDataLoader()->load($auction->Id, $isEnableConsignorCompanyClerking);
        $isTestAuction = $auction->TestAuction;
        foreach ($rows as $row) {
            // truncate lot name using mb_substr.
            // Values from DB are supposed to be UTF-8, so no need to check on encoding
            // If values are non UTF-8 the problem should be solved somewhere else! eg CSV import
            $lotName = trim((string)$row['lot_name']);
            $lotName = TextTransformer::new()->cut($lotName, 128);
            $lotName = $this->getLotRenderer()->makeName($lotName, $isTestAuction);
            $consignorName = '';
            if ($isEnableConsignorCompanyClerking) {
                if (!empty($row['consignor_company_name'])) {
                    $consignorName = ee($row['consignor_company_name']) . ' - ';
                } elseif (isset($row['consignor_username'])) {
                    $consignorName = ee($row['consignor_username']) . ' - ';
                }
            }
            $lotNo = $this->getLotRenderer()->makeLotNo($row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix']);
            $lotList .= $consignorName . $lotNo . ' '
                . str_replace(":", "&#58;", ee($lotName)) . ':'
                . (int)$row['lot_item_id'] . '|';
        }
        $lotList = rtrim($lotList, "|");
        return $lotList;
    }

    /**
     * @return GoToLotListDataLoader
     */
    protected function getGoToLotListDataLoader(): GoToLotListDataLoader
    {
        if ($this->goToLotListDataLoader === null) {
            $this->goToLotListDataLoader = GoToLotListDataLoader::new();
        }
        return $this->goToLotListDataLoader;
    }

    /**
     * @param GoToLotListDataLoader $goToLotListDataLoader
     * @return static
     * @internal
     */
    public function setGoToLotListDataLoader(GoToLotListDataLoader $goToLotListDataLoader): static
    {
        $this->goToLotListDataLoader = $goToLotListDataLoader;
        return $this;
    }
}
