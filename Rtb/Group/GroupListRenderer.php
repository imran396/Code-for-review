<?php
/**
 * SAM-4199: Optimize data loading for Grouped Lot List
 *
 * @author        Oleg Kovalyov
 * @version       SVN: $Id: $
 * @since         May 9, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Group;

use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Image\ImageHelper;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Render\LotRenderer;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class GroupListRenderer
 * @package Sam\Rtb\Group
 */
class GroupListRenderer extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use ConfigRepositoryAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @return string
     */
    public function render(int $auctionId): string
    {
        if (!$auctionId) {
            return '';
        }

        $rows = $this->loadGroupedLotRowsForList($auctionId);
        if (!$rows) {
            return '';
        }

        $outputTr = '';
        $bidderClientSize = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->bidderClient'));
        foreach ($rows as $row) {
            $lotImageId = (int)$row['lot_image_id'];
            $accountId = (int)$row['account_id'];
            $lotItemId = (int)$row['lot_item_id'];
            $lotImageUrl = $this->getUrlBuilder()->build(
                LotImageUrlConfig::new()->construct($lotImageId, $bidderClientSize, $accountId)
            );

            $lotDetailsUrl = $this->getUrlBuilder()->build(
                ResponsiveLotDetailsUrlConfig::new()->forWeb(
                    $lotItemId,
                    (int)$row['auction_id'],
                    $row['lot_seo_url'],
                    [UrlConfigConstants::OP_ACCOUNT_ID => $accountId]
                )
            );
            $isTestAuction = (bool)$row['test_auction'];

            $lotName = LotRenderer::new()->makeName($row['name'], $isTestAuction);
            $lotNameEscaped = ee($lotName);

            $lotNo = LotRenderer::new()->makeLotNo($row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix']);
            $outputTr .= <<<HTML
<tr>              
    <td class="check">         
        <input type="checkbox" class="parcel-lot" value="{$lotItemId}" />
    </td>
    <td class="lot">
        <a href="{$lotDetailsUrl}" target="_blank">Lot {$lotNo} - {$lotNameEscaped}</a>
    </td>      
                    
    <td class="icon">
        <a href="{$lotImageUrl}" 
             class="preview" 
             target="_blank" 
             onclick="return false;" 
             data-lotno="{$lotNo}"
             data-lotname="{$lotNameEscaped}"
             data-imageurl="{$lotImageUrl}">
        </a>
    </td>
</tr>
HTML;
        }
        $output = <<<HTML
<table class="group-lot">
    {$outputTr}
</table>
HTML;
        return $output;
    }

    /**
     * @param int $auctionId
     * @return array
     */
    protected function loadGroupedLotRowsForList(int $auctionId): array
    {
        $select = [
            'a.test_auction',
            'ali.account_id',
            'ali.auction_id',
            'ali.id',
            'ali.lot_item_id',
            'ali.lot_num',
            'ali.lot_num_ext',
            'ali.lot_num_prefix',
            'alic.seo_url AS lot_seo_url',
            'li.name',
            '(SELECT limg.id FROM lot_image limg WHERE limg.lot_item_id = li.id ORDER BY limg.`order`, limg.id LIMIT 1) AS lot_image_id',
        ];
        $auctionLotRows = $this->createAuctionLotItemReadRepository()
            ->select($select)
            ->innerJoinRtbCurrentGroup()
            ->joinAuction()
            ->joinLotItem()
            ->joinAuctionLotItemCache()
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->filterAuctionId($auctionId)
            ->orderByOrder()
            ->orderByLotNum()
            ->orderByLotNumExt()
            ->orderByLotNumPrefix()
            ->loadRows();
        return $auctionLotRows;
    }
}
