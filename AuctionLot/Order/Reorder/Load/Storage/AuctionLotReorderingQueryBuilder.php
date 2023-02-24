<?php
/**
 * SAM-5654 Auction lot reorderer
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 26, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Order\Reorder\Load\Storage;

use Sam\Storage\Sql\QueryBuilder;

/**
 * Builder of SQL queries for fetching auction lots in the actual order
 *
 * Class CustomQueryBuilder
 */
class AuctionLotReorderingQueryBuilder extends QueryBuilder
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function getOrderComponents(): array
    {
        return $this->order;
    }

    /**
     * Build concatenated lot order columns expression
     * @return static
     */
    public function buildOrderByConcatExpr(): static
    {
        $alias = 'concatenated_lot_order_columns';

        $orderFields = $this->getOrderFieldsForConcat();
        $concatExpr = sprintf(
            'CONCAT(%s) as %s',
            implode(', ', $orderFields),
            $alias
        );
        $this->addSelect($concatExpr);
        $this->clearOrderComponents();
        $this->order($alias);
        return $this;
    }

    /**
     * @return static
     */
    private function clearOrderComponents(): static
    {
        $this->order = [];
        return $this;
    }

    /**
     * @return array
     */
    private function getOrderFieldsForConcat(): array
    {
        return array_map(
            static function ($orderData) {
                $orderField = $orderData[0];
                if ($orderField === 'global_order') {
                    $expression = "IFNULL(@{$orderField}, '')";
                    $expression = "LPAD({$expression}, 10, '0')";
                } else {
                    $expression = "IFNULL({$orderField}, '')";
                }
                return $expression;
            },
            $this->order
        );
    }
}
