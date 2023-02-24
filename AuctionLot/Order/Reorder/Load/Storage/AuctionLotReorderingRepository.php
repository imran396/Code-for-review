<?php
/**
 * SAM-5654 Auction lot reorderer
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 27, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * This repository helps to create query like next. This is selection of ordered ali records for
 * - enabled [x] "Concatenate lot default order columns";
 * - Primary order by "Name", Secondary order by textual custom field "Author", Tertiary order by numeric custom field "Price inLatvia" and Quaternary order by "Item#";
 * - "Ignore stop words" enabled for every ordering options;
 * Query contains variables with "Name" and custom fields selection, because they are used in concatenation with alias "concatenated_lot_order_columns", we order by it.
 * "Name" and textual custom field selections are filtered for stop words. Numeric custom fields, item#, lot# don't need to filter for stop words.
 *
 * SELECT ali.*,
 * @name_filtered := (TRIM(LEADING 'a ' FROM TRIM(LEADING 'an ' FROM TRIM(LEADING 'the ' FROM TRIM(LEADING '\"' FROM TRIM(LEADING '(' FROM TRIM(LEADING '\'' FROM TRIM(LEADING '[' FROM LOWER(li.name))))))))),
 * @cauthor_filtered := (TRIM(LEADING 'a ' FROM TRIM(LEADING 'an ' FROM TRIM(LEADING 'the ' FROM TRIM(LEADING '\"' FROM TRIM(LEADING '(' FROM TRIM(LEADING '\'' FROM TRIM(LEADING '[' FROM LOWER((SELECT licd.`text` FROM `lot_item_cust_data` licd WHERE licd.active = 1 AND licd.lot_item_cust_field_id = 4 AND (licd.lot_item_id = ali.lot_item_id ))))))))))),
 * @cprice_inlatvia := (SELECT licd.`numeric` FROM `lot_item_cust_data` licd WHERE licd.active = 1 AND licd.lot_item_cust_field_id = 11 AND (licd.lot_item_id = ali.lot_item_id )),
 * CONCAT(IFNULL(@name_filtered, ''), IFNULL(@cauthor_filtered, ''), IFNULL(@cprice_inlatvia, ''), IFNULL(li.item_num, ''), IFNULL(li.item_num_ext, '')) AS concatenated_lot_order_columns
 * FROM `auction_lot_item` ali LEFT JOIN lot_item li ON li.id = ali.lot_item_id
 * WHERE ali.auction_id = 6 AND ali.lot_status_id IN (1,2,3,6)
 * ORDER BY concatenated_lot_order_columns LIMIT 0, 200;
 *
 */

namespace Sam\AuctionLot\Order\Reorder\Load\Storage;

use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\LotItemCustData\LotItemCustDataReadRepositoryCreateTrait;
use Sam\Storage\Sql\QueryBuilder;

/**
 * Ordered auction lot items repository
 * Class AuctionLotReorderingRepository
 * @package Sam\AuctionLot\Order\Reorder\Load\Storage
 * @property AuctionLotReorderingQueryBuilder $queryBuilder
 */
class AuctionLotReorderingRepository extends AuctionLotItemReadRepository
{
    use ConfigRepositoryAwareTrait;
    use LotItemCustDataReadRepositoryCreateTrait;

    private const FILTERED_FIELD_SUFFIX = '_filtered';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return AuctionLotReorderingQueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        if ($this->queryBuilder === null) {
            $this->queryBuilder = AuctionLotReorderingQueryBuilder::new();
        }
        return $this->queryBuilder;
    }

    /**
     * @param bool $ascending
     * @return static
     */
    public function joinLotItemOrderByFilteredName(bool $ascending = true): static
    {
        $exprVariableName = '@name' . self::FILTERED_FIELD_SUFFIX;
        $selectExprFiltered = $this->getSelectExprFiltered('li.name');
        if ($selectExprFiltered !== null) {
            $this->joinLotItem();
            $this->addSelect(sprintf('%s := (%s)', $exprVariableName, $selectExprFiltered));
            $this->order($exprVariableName, $ascending);
        } else {
            $this->joinLotItemOrderByName($ascending);
        }
        return $this;
    }

    /**
     * Define ordering by lc.global_order of the first category assigned to lot - it is main category.
     * We must assign result to "global_order" variable, because it may be used in order fields concatenation expression.
     * @param bool $ascending
     * @return static
     */
    public function subqueryMainCategoryForOrderingByGlobalOrder(bool $ascending = true): static
    {
        $globalOrder = '@global_order := (SELECT lc.global_order FROM lot_category lc'
            . ' LEFT JOIN lot_item_category lic ON lic.lot_category_id = lc.id'
            . ' WHERE lic.lot_item_id = ali.lot_item_id ORDER BY lic.id LIMIT 1) AS global_order';
        $this
            ->addSelect($globalOrder)
            ->order('global_order', $ascending);
        return $this;
    }

    /**
     * Creates custom field selection expression that is assigned to sql-variable.
     * Selection expression is built from select custom field query produced by LotItemCustDataReadRepository.
     * @param $lotCustomField
     * @param $isIgnoreStopWords
     * @return static
     */
    public function orderByCustomField($lotCustomField, $isIgnoreStopWords): static
    {
        $sqlVariable = 'c' . DbTextTransformer::new()->toDbColumn($lotCustomField->Name);
        $type = $lotCustomField->isNumeric() ? 'numeric' : 'text';

        $repo = $this->createLotItemCustDataReadRepository()
            ->addSelect("licd.`{$type}`")
            ->inlineCondition('licd.lot_item_id = ali.lot_item_id ')
            ->filterActive(true)
            ->filterLotItemCustFieldId($lotCustomField->Id);

        $selectExpr = $repo->getResultQuery();

        if ($type === 'text' && $isIgnoreStopWords) {
            $selectExprFiltered = $this->getSelectExprFiltered("({$selectExpr})");
            if ($selectExprFiltered !== null) {
                $selectExpr = $selectExprFiltered;
                $sqlVariable .= self::FILTERED_FIELD_SUFFIX;
            }
        }

        $this->addSelect(sprintf('@%s := (%s) AS %s', $sqlVariable, $selectExpr, $sqlVariable));
        $this->order($sqlVariable);
        return $this;
    }

    /**
     * Return select expression for column with filtered stop words, using TRIM()/LOWER() in querying()
     *
     * @param string $col
     * @return string|null
     */
    private function getSelectExprFiltered(string $col): ?string
    {
        $stopWords = $this->cfg()->get('core->lot->orderIgnoreWords')->toArray();
        $stopWordCount = count($stopWords);
        if (!$stopWordCount) {
            return null;
        }
        $selectExpr = array_reduce(
            $stopWords,
            function ($carry, $stopWord) {
                $stopWord = $this->escape($stopWord);
                return $carry . "TRIM(LEADING {$stopWord} FROM ";
            },
            ''
        );

        $selectExpr .= 'LOWER(' . $col . ')' . str_repeat(')', $stopWordCount);
        return $selectExpr;
    }
}
