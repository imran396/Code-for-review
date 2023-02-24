<?php
/**
 * SAM-4629: Refactor custom lot report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 09, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\CustomList\Load;

use Generator;
use LotItemCustField;
use QMySqli5DatabaseResult;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * This class contains methods for loading data for a custom lot list report
 *
 * Class DataLoader
 * @package Sam\Report\Auction\AuctionList
 */
class LotCustomListDataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterDatePeriodAwareTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;

    /**
     * QueryBuilder is responsible for mysql query generating with respective LIMIT clause
     * Offset value increased in data loading method
     */
    private LotCustomListQueryBuilder $queryBuilder;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->queryBuilder = LotCustomListQueryBuilder::new();
        return $this;
    }

    /**
     * @param array $returnFields
     * @param int $chunkSize
     * @return Generator
     */
    public function yieldRows(array $returnFields, int $chunkSize = 200): Generator
    {
        $queryBuilder = $this->initQueryBuilder()->setReturnFields($returnFields);
        $chunkNumber = 0;
        do {
            if ($chunkSize) {
                $queryBuilder
                    ->setLimit($chunkSize)
                    ->setOffset($chunkNumber * $chunkSize);
            }
            $query = $queryBuilder->getResultQuery();
            $dbResult = $this->query($query);

            yield from $this->yieldAssocRowFetcher();
            $chunkNumber++;
        } while ($chunkSize && $dbResult->CountRows() === $chunkSize);
    }

    /**
     * @param array $returnFields
     * @return array
     */
    public function loadRows(array $returnFields): array
    {
        $query = $this->initQueryBuilder()->setReturnFields($returnFields)->getResultQuery();
        $this->query($query);
        return $this->fetchAllAssoc();
    }

    /**
     * Count all rows
     * @return int total number of results
     */
    public function count(): int
    {
        $count = 0;
        $query = $this->initQueryBuilder()->getCountQuery('total');
        if ($query) {
            $dbResult = $this->query($query);
            $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
            $count = (int)$row['total'];
        }
        return $count;
    }

    /**
     * @param LotItemCustField $lotCustomField
     * @param array|string $mixValues
     * @return static
     */
    public function filterCustomField(LotItemCustField $lotCustomField, array|string $mixValues): static
    {
        $this->queryBuilder->addCustomFieldFilter($lotCustomField, $mixValues);
        return $this;
    }

    /**
     * Set isIncludeWithoutHammerPrice property value and normalize boolean value
     * @param bool $isIncludeWithoutHammerPrice
     * @return static
     */
    public function enableIncludeWithoutHammerPrice(bool $isIncludeWithoutHammerPrice): static
    {
        $this->queryBuilder->enableIncludeWithoutHammerPrice($isIncludeWithoutHammerPrice);
        return $this;
    }

    /**
     * @param array $lotCategoryIds
     * @param int $categoryMatch
     * @return static
     */
    public function filterLotCategoryIds(
        array $lotCategoryIds,
        int $categoryMatch = Constants\MySearch::CATEGORY_MATCH_ANY
    ): static {
        $this->queryBuilder
            ->filterLotCategoryIds($lotCategoryIds)
            ->setCategoryMatch($categoryMatch);
        return $this;
    }

    /**
     * @param int|null $auctionId NULL means disable filter by auction.
     * Can accept -1 @return static
     * @see Constants\AuctionListAutocomplete::UNASSIGNED_AUCTION_ID
     * if we need to display only lots that unassigned to auctions
     */
    public function filterAuctionId(?int $auctionId = null): static
    {
        $this->queryBuilder->setAuctionId($auctionId);
        return $this;
    }

    /**
     * @return LotCustomListQueryBuilder
     */
    private function initQueryBuilder(): LotCustomListQueryBuilder
    {
        $this->queryBuilder
            ->setAccountId($this->getFilterAccountId())
            ->filterStartDateSysIso($this->getFilterStartDateSysIso())
            ->filterEndDateSysIso($this->getFilterEndDateSysIso())
            ->setSortColumn($this->getSortColumn())
            ->enableAscendingOrder($this->isAscendingOrder())
            ->setLimit($this->getLimit())
            ->setOffset($this->getOffset());
        return $this->queryBuilder;
    }
}
