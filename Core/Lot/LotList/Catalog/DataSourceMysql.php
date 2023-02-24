<?php

namespace Sam\Core\Lot\LotList\Catalog;

/**
 * Class DataSourceMysql
 * @package Sam\Core\Lot\LotList\Catalog
 */
class DataSourceMysql extends \Sam\Core\Lot\LotList\DataSourceMysql
{
    protected bool $isEnabledConsiderOptionHideUnsoldLots = true;

    /**
     * Return instance of self
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check initialization settings
     * @return bool
     */
    protected function checkInit(): bool
    {
        if (!array_key_exists('ali.auction_id', $this->filter)) {
            throw new \InvalidArgumentException('Auction id not defined');
        }
        return true;
    }

    /**
     * Define mapping for result fields
     */
    protected function initResultFieldsMapping(): void
    {
        parent::initResultFieldsMapping();
        // @formatter:off
        $this->resultFieldsMapping['max_bid_live'] = [
            'select' => 'ab.max_bid',
            'join' => [
                'LEFT JOIN absentee_bid ab ' .
                    'ON ab.auction_id = ali.auction_id ' .
                    'AND ab.user_id = @UserId ' .
                    'AND ab.lot_item_id = ali.lot_item_id'
            ]
        ];
        $this->resultFieldsMapping['max_bid_timed'] = [
            'select' =>
                '(SELECT max_bid FROM bid_transaction ' .
                'WHERE auction_id = ali.auction_id ' .
                    'AND lot_item_id = ali.lot_item_id ' .
                    'AND (deleted IS NULL OR deleted = false) ' .
                    'AND failed = 0 ' .
                    'AND user_id = @UserId ' .
                    'AND user_id > 0 ' .
                'ORDER BY id DESC LIMIT 1)',
        ];
        // @formatter:on
    }

    /**
     * Define mappings for complex ORDER BY expressions
     */
    protected function initOrderFieldsMapping(): void
    {
        // @formatter:off
        parent::initOrderFieldsMapping();
        $orderByPrice =
            'CASE ' .
                'WHEN alic.current_bid > 0 then alic.current_bid ' .
                'ELSE alic.asking_bid ' .
            'END ';
        $lotOrderClauseAsc = $this->createAuctionLotOrderMysqlQueryBuilder()->buildLotOrderClause();
        $lotOrderClauseDesc = $this->createAuctionLotOrderMysqlQueryBuilder()->buildLotOrderClause(false);
        $orderFieldsMapping = [
            'bids' => [
                'asc' => [
                    'order' => 'alic.bid_count ASC, ' .
                        $orderByPrice . ' ASC, ' . $lotOrderClauseAsc,
                    'join' => ['auction_lot_item_cache'],
                ],
                'desc' => [
                    'order' => 'alic.bid_count DESC, ' .
                        $orderByPrice . ' DESC, ' . $lotOrderClauseDesc,
                    'join' => ['auction_lot_item_cache'],
                ],
            ],
            'estimate' => [
                'asc' => [
                    'order' =>
                        'case '.
                            'when li.high_estimate IS NOT NULL AND li.low_estimate IS NOT NULL then li.high_estimate ' .
                            'when li.high_estimate IS NOT NULL then li.high_estimate ' .
                            'when li.low_estimate IS NOT NULL then li.low_estimate ' .
                            'else 0 ' .
                        'end ASC',
                ],
                'desc' => [
                    'order' =>
                        'case '.
                            'when li.high_estimate IS NOT NULL AND li.low_estimate IS NOT NULL then li.high_estimate ' .
                            'when li.high_estimate IS NOT NULL then li.high_estimate ' .
                            'when li.low_estimate IS NOT NULL then li.low_estimate ' .
                            'else 0 ' .
                        'end DESC',
                ],
            ],
            'price' => [
                'asc' => [
                    'order' => $orderByPrice . ' ASC, ' . $lotOrderClauseAsc,
                    'join' => ['auction_lot_item_cache'],
                ],
                'desc' => [
                    'order' => $orderByPrice . ' DESC, ' . $lotOrderClauseDesc,
                    'join' => ['auction_lot_item_cache'],
                ],
            ],
            'price_live' => [
                'asc' => [
                    'order' =>
                        'case ' .
                            'when li.hammer_price IS NOT NULL then li.hammer_price ' .
                            'else alic.starting_bid_normalized ' .
                        'end ASC, ' .
                        $lotOrderClauseAsc,
                    'join' => ['auction_lot_item_cache'],
                ],
                'desc' => [
                    'order' =>
                        'case ' .
                            'when li.hammer_price IS NOT NULL then li.hammer_price ' .
                            'else alic.starting_bid_normalized ' .
                        'end DESC, ' .
                        $lotOrderClauseDesc,
                    'join' => ['auction_lot_item_cache'],
                ],
            ],
            'price_timed' => [
                'asc' => [
                    'order' =>
                        'case ali.lot_status_id ' .
                            'when 2 then 1 ' .
                            'when 3 then 2 ' .
                            'when 1 then 3 ' .
                            'else 4 ' .
                        'end ASC, ' .
                        'ali.buy_now_amount ASC, ' .
                        'IFNULL(alic.current_bid, IFNULL(li.starting_bid, 0)) ASC, ' .
                        $lotOrderClauseAsc,
                    'join' => ['auction_lot_item_cache'],
                ],
                'desc' => [
                    'order' =>
                        'case ali.lot_status_id ' .
                            'when 1 then 1 ' .
                            'when 3 then 2 ' .
                            'when 2 then 3 ' .
                            'else 4 ' .
                        'end, ' .
                        'ali.buy_now_amount DESC, ' .
                        'IFNULL(alic.current_bid, IFNULL(li.starting_bid, 0)) DESC, ' .
                        $lotOrderClauseDesc,
                    'join' => ['auction_lot_item_cache'],
                ],
            ],
            'time_left' => [
                'asc' => [
                    'order' =>
                        'ali.lot_status_id ASC, ' .
                        $this->queryBuilderHelper->getLotEndDateExpr() . ' ASC, ' .
                        $lotOrderClauseAsc,
                    'join' => [
                        'auction',
                        'auction_dynamic',
                        'auction_lot_item_cache'
                    ],
                ],
                'desc' => [
                    'order' =>
                        'ali.lot_status_id DESC, ' .
                        $this->queryBuilderHelper->getLotEndDateExpr() . ' DESC, ' .
                        $lotOrderClauseDesc,
                    'join' => [
                        'auction',
                        'auction_dynamic',
                        'auction_lot_item_cache'
                    ],
                ],
            ],
            'views' => [
                'asc' => [
                    'order' => 'alic.view_count ASC, ' .
                        $orderByPrice . ' ASC, ' .
                        $lotOrderClauseAsc,
                    'join' => ['auction_lot_item_cache'],
                ],
                'desc' => [
                    'order' => 'alic.view_count DESC, ' .
                        $orderByPrice . ' DESC, ' .
                        $lotOrderClauseDesc,
                    'join' => ['auction_lot_item_cache'],
                ],
            ],
        ];
        $this->setOrderFieldsMapping(array_merge($this->getOrderFieldsMapping(), $orderFieldsMapping));
        // @formatter:on
    }

    /**
     * Initialize parts of filtering query
     * @param array $queryParts
     * @return array
     */
    protected function initializeFilterQueryParts(array $queryParts = []): array
    {
        $queryParts['from'] = [
            'FROM auction_lot_item ali',
            'INNER JOIN lot_item li ON li.id = ali.lot_item_id',
        ];
        $queryParts['join'] = $this->getBaseJoins();
        $queryParts['where'] = $this->getBaseConditions();

        $queryParts = parent::initializeFilterQueryParts($queryParts);
        return $queryParts;
    }
}
