<?php
/**
 * SAM-5654 Auction lot reorderer
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 25, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Order\Reorder\Load;

use Auction;
use AuctionLotItem;
use Generator;
use LotItemCustField;
use Sam\AuctionLot\Order\Reorder\Load\Storage\AuctionLotReorderingRepositoryCreateTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;

/**
 * Loader of auction lots in actual order
 *
 * Class AuctionLotReorderingLoader
 */
class AuctionLotReorderingLoader extends CustomizableClass
{
    use AuctionLotReorderingRepositoryCreateTrait;
    use DbConnectionTrait;
    use LotCustomFieldLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get an ordered list of lots for an auction.
     * Lots are in actual order, sorted by auction defined fields (not by ali.order)
     *
     * @param Auction $auction
     * @param int|null $chunkSize if NULL chunk is disabled
     * @return Generator|AuctionLotItem[]
     */
    public function yieldAuctionLots(Auction $auction, ?int $chunkSize = null): Generator
    {
        $repo = $this->prepareAuctionLotRepository($auction);
        $repo->setChunkSize($chunkSize);
        return $repo->yieldEntities();
    }

    /**
     * Prepare repository for loading an ordered list of lots for an auction.
     * Lots are in actual order, sorted by auction defined fields (not by ali.order)
     *
     * @param Auction $auction
     * @return AuctionLotItemReadRepository
     */
    protected function prepareAuctionLotRepository(Auction $auction): AuctionLotItemReadRepository
    {
        $repository = $this->createAuctionLotReorderingRepository();
        $repository
            ->select(['ali.*'])
            ->filterAuctionId($auction->Id)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses);

        $orderPriorities = ['Primary', 'Secondary', 'Tertiary', 'Quaternary'];
        foreach ($orderPriorities as $orderPriority) {
            $orderType = $this->getAuctionLotOrderType($auction, $orderPriority);
            if ($orderType === Constants\Auction::LOT_ORDER_BY_LOT_NUMBER) {
                $repository
                    ->orderByLotNumPrefix()
                    ->orderByLotNum()
                    ->orderByLotNumExt();
            } elseif ($orderType === Constants\Auction::LOT_ORDER_BY_ITEM_NUMBER) {
                $repository
                    ->joinLotItemOrderByNum()
                    ->joinLotItemOrderByNumExt();
            } elseif ($orderType === Constants\Auction::LOT_ORDER_BY_CATEGORY) {
                $repository->subqueryMainCategoryForOrderingByGlobalOrder();
            } elseif ($orderType === Constants\Auction::LOT_ORDER_BY_CUST_FIELD) {
                $lotCustomField = $this->getLotOrderCustomField($auction, $orderPriority);
                $isLotOrderIgnoreStopWords = $this->isLotOrderIgnoreStopWords($auction, $orderPriority);
                $repository->orderByCustomField($lotCustomField, $isLotOrderIgnoreStopWords);
            } elseif ($orderType === Constants\Auction::LOT_ORDER_BY_NAME) {
                $isLotOrderIgnoreStopWords = $this->isLotOrderIgnoreStopWords($auction, $orderPriority);
                if ($isLotOrderIgnoreStopWords) {
                    $repository->joinLotItemOrderByFilteredName();
                } else {
                    $repository->joinLotItemOrderByName();
                }
            }
        }

        $queryBuilder = $repository->getQueryBuilder();
        if (
            $auction->ConcatenateLotOrderColumns
            && !empty($queryBuilder->getOrderComponents())
        ) {
            $queryBuilder->buildOrderByConcatExpr();
        }

        return $repository;
    }

    /**
     * @param Auction $auction
     * @param string $orderPriority
     * @return LotItemCustField|null
     */
    private function getLotOrderCustomField(Auction $auction, string $orderPriority): ?LotItemCustField
    {
        $map = [
            'Primary' => $auction->LotOrderPrimaryCustFieldId,
            'Secondary' => $auction->LotOrderSecondaryCustFieldId,
            'Tertiary' => $auction->LotOrderTertiaryCustFieldId,
            'Quaternary' => $auction->LotOrderQuaternaryCustFieldId
        ];
        return $this->createLotCustomFieldLoader()->load($map[$orderPriority]);
    }

    /**
     * @param Auction $auction
     * @param string $orderPriority
     * @return bool
     */
    private function isLotOrderIgnoreStopWords(Auction $auction, string $orderPriority): bool
    {
        $map = [
            'Primary' => $auction->LotOrderPrimaryIgnoreStopWords,
            'Secondary' => $auction->LotOrderSecondaryIgnoreStopWords,
            'Tertiary' => $auction->LotOrderTertiaryIgnoreStopWords,
            'Quaternary' => $auction->LotOrderQuaternaryIgnoreStopWords
        ];
        return $map[$orderPriority];
    }

    /**
     * @param Auction $auction
     * @param string $orderPriority
     * @return int
     */
    private function getAuctionLotOrderType(Auction $auction, string $orderPriority): int
    {
        $map = [
            'Primary' => $auction->LotOrderPrimaryType,
            'Secondary' => $auction->LotOrderSecondaryType,
            'Tertiary' => $auction->LotOrderTertiaryType,
            'Quaternary' => $auction->LotOrderQuaternaryType
        ];
        return $map[$orderPriority];
    }
}
