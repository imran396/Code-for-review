<?php
/**
 *  SAM-6349: Adjust the labels for bulk vs piecemeal lots.
 *  https://bidpath.atlassian.net/browse/SAM-6349
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           August 13, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\BulkGroup\Render;

use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\AuctionLot\BulkGroup\Load\LotBulkGroupLoaderAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;

/**
 * Class CompeteLabelRenderer
 */
class LotBulkGroupCompeteLabelRenderer extends CustomizableClass
{
    use LotBulkGroupLoaderAwareTrait;
    use LotRendererAwareTrait;
    use TranslatorAwareTrait;
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
     * @param int $masterAuctionLotId
     * @return string
     */
    public function renderBulkGroupMasterCompeteLabel(int $masterAuctionLotId): string
    {
        $piecemealLotRows = $this->getLotBulkGroupLoader()->loadPiecemealLotRows($masterAuctionLotId);
        $output = '';
        $urlBuilder = $this->getUrlBuilder();
        foreach ($piecemealLotRows as $row) {
            $url = $urlBuilder->build(
                ResponsiveLotDetailsUrlConfig::new()->forWeb(
                    (int)$row['lot_item_id'],
                    (int)$row['auction_id'],
                    null,
                    [UrlConfigConstants::OP_ACCOUNT_ID => (int)$row['account_id']]
                )
            );
            $lotName = ee($this->getLotRenderer()->makeName($row['lot_name']));
            $lotNo = $this->getLotRenderer()->makeLotNo($row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix']);
            $output .= <<<HTML
    <a href="{$url}" target="_blank" title="{$lotName}">{$lotNo}</a>,
HTML;
        }
        if ($output) {
            $output = rtrim($output, ',');
            $output = sprintf($this->getTranslator()->translate('GENERAL_BULK_GROUP_MASTER_COMPETE_ITEM', 'general'), $output);
        }
        return $output;
    }

    /**
     * @param int $auctionId
     * @param int $bulkMasterId
     * @return string
     */
    public function renderBulkGroupPiecemealCompeteLabel(int $auctionId, int $bulkMasterId): string
    {
        $row = $this->getLotBulkGroupLoader()->loadBulkMasterById($auctionId, $bulkMasterId);
        if (!$row) {
            return '';
        }
        $lotName = ee($this->getLotRenderer()->makeName($row['lot_name']));
        $url = $this->getUrlBuilder()->build(
            ResponsiveLotDetailsUrlConfig::new()->forWeb(
                (int)$row['lot_item_id'],
                $auctionId,
                null,
                [UrlConfigConstants::OP_ACCOUNT_ID => (int)$row['account_id']]
            )
        );
        $lotNo = $this->getLotRenderer()->makeLotNo($row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix']);
        $output = <<<HTML
       <a href="{$url}" target="_blank" title="{$lotName}">{$lotNo}</a>
HTML;
        return sprintf($this->getTranslator()->translate('GENERAL_BULK_GROUP_PIECEMEAL_COMPETE_ITEM', 'general'), $output);
    }
}
