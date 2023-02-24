<?php
/**
 * AuctionLotDataIntegrityChecker prepares AuctionLots duplicates
 *
 * SAM-5077: Avoid lot item be active in more than one auction
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           19 Jul, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Validate;

use Sam\Application\Url\Build\Config\AuctionLot\AdminLotEditUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\View\Base\HtmlRenderer;

/**
 * Class AuctionLotDataIntegrityChecker
 * @package Sam\AuctionLot\Validate
 */
class AuctionLotDataIntegrityChecker extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotItemReadRepositoryCreateTrait;
    use AuctionRendererAwareTrait;
    use FilterAccountAwareTrait;
    use UrlBuilderAwareTrait;

    /** @var int|null */
    protected ?int $activeAuctionId = null;
    /** @var int|null */
    protected ?int $activeLotItemId = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return AuctionLotItemReadRepository
     */
    public function prepareAuctionLotsWithBuyNowAndBulkGroupSearch(): AuctionLotItemReadRepository
    {
        $repo = $this->createAuctionLotItemReadRepository()
            ->select(
                [
                    'ali.id',
                    'lot_item_id',
                    'auction_id',
                    'account_id',
                    'acc.name as account_name',
                ]
            )
            ->joinAccount()
            ->inlineCondition("ali.buy_now_amount AND (ali.is_bulk_master OR ali.bulk_master_id)")
            ->skipLotStatusId(Constants\Lot::LS_DELETED);
        if ($this->getFilterAccountId()) {
            $repo->filterAccountId($this->getFilterAccountId());
        }
        return $repo;
    }

    /**
     * @return AuctionLotItemReadRepository
     */
    public function prepareDuplicateSearch(): AuctionLotItemReadRepository
    {
        $repo = $this->createAuctionLotItemReadRepository()
            ->select(
                [
                    'ali.lot_item_id',
                    'COUNT(1) as count_records',
                    'GROUP_CONCAT(ali.auction_id) as auction_id_list',
                    'ali.account_id',
                    'acc.name as account_name'
                ]
            )
            ->joinLotItem()
            ->joinAccount()
            ->filterLotStatusId(Constants\Lot::LS_ACTIVE)
            ->joinLotItemGroupById()
            ->having('COUNT(1) > 1')
            ->orderByAccountId()
            ->orderByLotItemId()
            ->setChunkSize(200);

        if ($this->getFilterAccountId()) {
            $repo->filterAccountId($this->getFilterAccountId());
        }

        return $repo;
    }

    /**
     * Check if lot is already active in another auction
     * @param int $lotItemId
     * @param int|null $skipAuctionId
     * @return bool
     */
    public function isAlreadyActive(int $lotItemId, ?int $skipAuctionId): bool
    {
        $repo = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb(true)
            ->filterLotItemId($lotItemId)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->filterLotStatusId(Constants\Lot::LS_ACTIVE)
            ->select(['ali.auction_id', 'ali.lot_item_id']);
        if ($skipAuctionId) {
            $repo->skipAuctionId($skipAuctionId);
        }

        $row = $repo->loadRow();
        if ($row) {
            $this->activeAuctionId = (int)$row['auction_id'];
            $this->activeLotItemId = (int)$row['lot_item_id'];
        }

        return (bool)$row;
    }

    /**
     * @return string
     */
    public function produceErrorMessage(): string
    {
        $saleNo = $this->getAuctionRenderer()->renderSaleNo(
            $this->getAuctionLoader()->load($this->activeAuctionId)
        );
        $url = $this->getUrlBuilder()->build(
            AdminLotEditUrlConfig::new()->forWeb($this->activeLotItemId, $this->activeAuctionId)
        );
        return 'Lot item already active in ('
            . HtmlRenderer::new()->link($url, $saleNo)
            . ')';
    }
}
