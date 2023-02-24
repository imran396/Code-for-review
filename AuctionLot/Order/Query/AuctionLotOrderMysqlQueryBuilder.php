<?php
/**
 * SAM-5660: Auction lot ordering mysql query helper
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 31, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Order\Query;

use Closure;
use Sam\Core\Service\CustomizableClass;

/**
 * Contains methods for building a ordering SQL query for a list of auction lots.
 *
 * Class AuctionLotOrderMysqlQueryBuilder
 * @package Sam\AuctionLot\Order\Query
 */
class AuctionLotOrderMysqlQueryBuilder extends CustomizableClass
{
    private const LOT_ORDER_COLUMNS = [
        'order',
        'lot_num_prefix',
        'lot_num',
        'lot_num_ext',
    ];
    private const DIRECTION_NEXT = 0;
    private const DIRECTION_PREV = 1;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Returns order clause for selecting auction lot items
     * Optimized, not to use Auction object in arguments to determine field for ordering
     *
     * @param bool $isAscending - true for ascending order, false for descending
     * @param string $tableAlias - table name alias used in query
     * @return string
     */
    public function buildLotOrderClause(bool $isAscending = true, string $tableAlias = 'ali'): string
    {
        $directionDeclarator = $isAscending ? 'ASC' : 'DESC';

        $preparedColumns = array_map(
            static function ($column) use ($tableAlias, $directionDeclarator) {
                return "`$tableAlias`.`$column` $directionDeclarator";
            },
            self::LOT_ORDER_COLUMNS
        );

        return implode(', ', $preparedColumns);
    }

    /**
     * @param string $targetTableAlias - for lot we want to find
     * @param string $sourceTableAlias - for current lot
     * @return string
     */
    public function buildNextLotsWhereClause(string $targetTableAlias = 'ali', string $sourceTableAlias = 'ali2'): string
    {
        return $this->buildAdjacentLotsWhereClause(self::DIRECTION_NEXT, $targetTableAlias, $sourceTableAlias);
    }

    /**
     * @param string $targetTableAlias - for lot we want to find
     * @param string $sourceTableAlias - for current lot
     * @return string
     */
    public function buildPrevLotsWhereClause(string $targetTableAlias = 'ali', string $sourceTableAlias = 'ali2'): string
    {
        return $this->buildAdjacentLotsWhereClause(self::DIRECTION_PREV, $targetTableAlias, $sourceTableAlias);
    }

    /**
     * Return where clause to get next/previous lots,
     * we include also checking by lot prefix / number / extension when ali.order field is not defined (for back compatibility)
     *
     * @param int $direction
     * @param string $targetLotAlias - for lot we want to find
     * @param string $sourceLotAlias - for current lot
     * @return string
     */
    public function buildAdjacentLotsWhereClause(int $direction, string $targetLotAlias, string $sourceLotAlias): string
    {
        $inequalitySign = $direction === self::DIRECTION_NEXT ? '>' : '<';
        $ineq = $this->getColumnComparisonExprBuilder($targetLotAlias, $sourceLotAlias, $inequalitySign);
        $eq = $this->getColumnComparisonExprBuilder($targetLotAlias, $sourceLotAlias, '=');

        return $this->buildOrderWhereClauseChain($ineq, $eq);
    }

    /**
     * @param Closure $ineq
     * @param Closure $eq
     * @param int $columnIndex
     * @return string
     */
    private function buildOrderWhereClauseChain(Closure $ineq, Closure $eq, int $columnIndex = 0): string
    {
        if ($columnIndex === count(self::LOT_ORDER_COLUMNS) - 1) {
            return $ineq(self::LOT_ORDER_COLUMNS[$columnIndex]);
        }

        return $this->orWhere(
            $ineq(self::LOT_ORDER_COLUMNS[$columnIndex]),
            $this->andWhere(
                $eq(self::LOT_ORDER_COLUMNS[$columnIndex]),
                $this->buildOrderWhereClauseChain($ineq, $eq, $columnIndex + 1)
            )
        );
    }

    /**
     * @param string $aliasLeft
     * @param string $aliasRight
     * @param string $sign
     * @return Closure
     */
    private function getColumnComparisonExprBuilder(string $aliasLeft, string $aliasRight, string $sign): callable
    {
        return static function ($column) use ($aliasLeft, $aliasRight, $sign) {
            $column = "`$column`";
            return "$aliasLeft.$column $sign $aliasRight.$column";
        };
    }

    /**
     * @param string $leftExpr
     * @param string $rightExpr
     * @return string
     */
    private function andWhere(string $leftExpr, string $rightExpr): string
    {
        return '(' . $leftExpr . ' AND ' . $rightExpr . ')';
    }

    /**
     * @param string $leftExpr
     * @param string $rightExpr
     * @return string
     */
    private function orWhere(string $leftExpr, string $rightExpr): string
    {
        return '(' . $leftExpr . ' OR ' . $rightExpr . ')';
    }
}
