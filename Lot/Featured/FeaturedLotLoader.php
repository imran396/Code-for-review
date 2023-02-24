<?php
/**
 * SAM-5152: Featured lot loader
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           02.06.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Lot\Featured;

use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\Image\ImageHelper;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class FeaturedLoadLoader
 * @package Sam\Lot\Featured
 */
class FeaturedLotLoader extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionLotItemReadRepositoryCreateTrait;
    use ConfigRepositoryAwareTrait;
    use LotImageLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return details of featured lots for the specified auction
     *
     * @return array
     */
    public function loadDetails(): array
    {
        $details = [];
        $select = [
            'li.item_num',
            'li.name',
            'li.account_id',
            'li.description',
            'ali.lot_item_id',
            'ali.lot_num',
            'ali.lot_num_ext',
            'ali.lot_num_prefix',
            'ali.lot_status_id',
            'ali.account_id',
            'alic.seo_url',
        ];
        $thumbSize = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->lotDetailThumb'));
        $rows = $this->loadRows($select);
        foreach ($rows as $row) {
            if (empty($row['item_num'])) {
                log_error(
                    "Available lot item not found for sample lot"
                    . composeSuffix(['li' => $row['lot_item_id']])
                );
                continue;
            }

            $lotImage = $this->getLotImageLoader()->loadDefaultForLot((int)$row['lot_item_id'], true);
            $lotImageId = $lotImage->Id ?? 0;
            $accountId = (int)$row['account_id'];
            $lotImageUrl = $this->getUrlBuilder()->build(
                LotImageUrlConfig::new()->construct($lotImageId, $thumbSize, $accountId)
            );

            $lotDetailsUrl = $this->getUrlBuilder()->build(
                ResponsiveLotDetailsUrlConfig::new()->forWeb(
                    (int)$row['lot_item_id'],
                    $this->getAuctionId(),
                    $row['seo_url'],
                    [UrlConfigConstants::OP_ACCOUNT_ID => $accountId]
                )
            );

            $lotName = $this->getLotRenderer()->makeName($row['name'], $this->getAuction()->TestAuction);

            $details[] = [
                'lotDetailsUrl' => $lotDetailsUrl,
                'lotNo' => $this->getLotRenderer()
                    ->makeLotNo($row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix']),
                'lotName' => ee($lotName),
                'langLotNo' => $this->getTranslator()->translate('AUCTION_LOT_NUMBER', 'auctions'),
                'description' => ee(strip_tags($row['description'])),
                'imageUrl' => $lotImageUrl,
                'imageAlt' => ee(TextTransformer::new()->cut($lotName, 255)),
            ];
        }
        return $details;
    }

    /**
     * @return array
     */
    public function loadNames(): array
    {
        $select = [
            'ali.lot_item_id',
            'li.item_num',
            'li.name',
            'li.description',
        ];
        $rows = $this->loadRows($select);
        $names = [];
        foreach ($rows as $row) {
            if (empty($row['item_num'])) {
                log_error(
                    "Available lot item not found for sample lot"
                    . composeSuffix(['li' => $row['lot_item_id']])
                );
                continue;
            }
            $names[] = $this->getLotRenderer()->makeName($row['name']);
        }

        return $names;
    }

    /**
     * @param string[] $select
     * @return array
     */
    protected function loadRows(array $select = []): array
    {
        $select = $select ?: ['ali.*'];

        $auction = $this->getAuction();
        if (!$auction) {
            log_error(
                "Available auction not found for sample lot loading"
                . composeSuffix(['a' => $this->getAuctionId()])
            );
            return [];
        }

        $repo = $this->createAuctionLotItemReadRepository()
            ->considerOptionHideUnsoldLots()
            ->filterAuctionId($this->getAuctionId())
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->filterSampleLot(true)
            ->joinAuction()
            ->joinAuctionLotItemCache()
            ->joinLotItemFilterActive(true)
            ->orderByOrderAndLotFullNumber()
            ->select($select);

        if (
            $auction->isTimed()
            && $auction->NotShowUpcomingLots
        ) {
            $repo->filterTimedLotStartDateInPast();
        } elseif ($auction->OnlyOngoingLots) {
            $repo->filterLotStatusId(Constants\Lot::LS_ACTIVE);
            if ($auction->isTimed()) {
                // ad required for filterTimedLotEndDateInFuture
                $repo
                    ->joinAuctionDynamic()
                    ->filterTimedLotEndDateInFuture();
            }
        }

        return $repo->loadRows();
    }
}
