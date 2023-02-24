<?php
/**
 * Query clauses for calculating bid related values
 *
 * SAM-6431: Refactor Auction_Lots_DbCacheManager for 2020 year version
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Jun 1, 2012
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package com.swb.sam2.api
 */

namespace Sam\AuctionLot\Cache\Query;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class Lot_BidQuery
 */
class LotBidQueryBuildPartner extends CustomizableClass
{
    /** @var string */
    protected string $bidTransactionTableAlias = 'bt';
    /** @var string */
    protected string $startingBidAlias = 'starting_bid_normalized';
    /** @var string */
    protected string $askingBidAlias = 'asking_bid';

    /**
     * Class instantiation method
     * @return static or customized class extending Lot_BidQuery
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return select clause for asking and starting bid according to quantization.
     * We return starting bid too, because it is necessary to determine asking bid.
     * @return string
     */
    public function getStartingAndAskingBidSelectClause(): string
    {
        $selectExpr = $this->getStartingBidNormalizedSelectClause() . ", ";
        // @account_id and @{$strStartingBidAlias} mysql variables are defined by self::getStartingBidNormalizedSelectClause()
        $bt = $this->bidTransactionTableAlias;
        // @formatter:off
        $selectExpr .= <<<END

            @is_lot_inc := (SELECT COUNT(1) FROM bid_increment WHERE account_id=@account_id AND bid_increment.lot_item_id=ali.lot_item_id) > 0 AS is_lot_inc,
            @is_auc_inc := (SELECT COUNT(1) FROM bid_increment WHERE account_id=@account_id AND auction_id=a.id) > 0 AS is_auc_inc,

            @increment :=
                IF(a.reverse,
                    IF (
                        @is_lot_inc,
                        (SELECT increment FROM bid_increment WHERE account_id=@account_id AND bid_increment.lot_item_id=ali.lot_item_id AND {$bt}.bid>amount ORDER BY amount DESC LIMIT 1),
                        IF(
                            @is_auc_inc,
                            (SELECT increment FROM bid_increment WHERE account_id=@account_id AND auction_id=a.id AND {$bt}.bid>amount ORDER BY amount DESC LIMIT 1),
                            (SELECT increment FROM bid_increment WHERE account_id=@account_id AND auction_type=a.auction_type AND {$bt}.bid>amount ORDER BY amount DESC LIMIT 1)
                        )
                    ),
                    IF (
                        @is_lot_inc,
                        (SELECT increment FROM bid_increment WHERE account_id=@account_id AND bid_increment.lot_item_id=ali.lot_item_id AND {$bt}.bid>=amount ORDER BY amount DESC LIMIT 1),
                        IF(
                            @is_auc_inc,
                            (SELECT increment FROM bid_increment WHERE account_id=@account_id AND auction_id=a.id AND {$bt}.bid>=amount ORDER BY amount DESC LIMIT 1),
                            (SELECT increment FROM bid_increment WHERE account_id=@account_id AND auction_type=a.auction_type AND {$bt}.bid>=amount ORDER BY amount DESC LIMIT 1)
                        )
                    )
                ) AS increment,

            @inc_range_start :=
                IF(a.reverse,
                    IF (
                        @is_lot_inc,
                        (SELECT amount FROM bid_increment WHERE account_id=@account_id AND bid_increment.lot_item_id=ali.lot_item_id AND {$bt}.bid>amount ORDER BY amount DESC LIMIT 1),
                        IF(
                            @is_auc_inc,
                            (SELECT amount FROM bid_increment WHERE account_id=@account_id AND auction_id=a.id AND {$bt}.bid>amount ORDER BY amount DESC LIMIT 1),
                            (SELECT amount FROM bid_increment WHERE account_id=@account_id AND auction_type=a.auction_type AND {$bt}.bid>amount ORDER BY amount DESC LIMIT 1)
                        )
                    ),
                    IF (
                        @is_lot_inc,
                        (SELECT amount FROM bid_increment WHERE account_id=@account_id AND bid_increment.lot_item_id=ali.lot_item_id AND {$bt}.bid>=amount ORDER BY amount DESC LIMIT 1),
                        IF(
                            @is_auc_inc,
                            (SELECT amount FROM bid_increment WHERE account_id=@account_id AND auction_id=a.id AND {$bt}.bid>=amount ORDER BY amount DESC LIMIT 1),
                            (SELECT amount FROM bid_increment WHERE account_id=@account_id AND auction_type=a.auction_type AND {$bt}.bid>=amount ORDER BY amount DESC LIMIT 1)
                        )
                    )
                ) AS inc_range_start,

            @inc_next_range_start :=
                IF(a.reverse,
                    @inc_range_start,
                    IF (
                        @is_lot_inc,
                        (SELECT amount FROM bid_increment WHERE account_id=@account_id AND bid_increment.lot_item_id=ali.lot_item_id AND {$bt}.bid < amount ORDER BY amount ASC LIMIT 1),
                        IF(
                            @is_auc_inc,
                            (SELECT amount FROM bid_increment WHERE account_id=@account_id AND auction_id=a.id AND {$bt}.bid < amount ORDER BY amount ASC LIMIT 1),
                            (SELECT amount FROM bid_increment WHERE account_id=@account_id AND auction_type=a.auction_type AND {$bt}.bid < amount ORDER BY amount ASC LIMIT 1)
                        )
                    )
                ) AS inc_next_range_start,

            @next_bid :=
                CAST(
                    IF(a.reverse,
                        {$bt}.bid - @increment,
                        {$bt}.bid + @increment
                    )
                 AS DECIMAL(20,10)) AS `next_bid`,

            @quantized_next_bid :=
                IF(a.reverse,
                    (SELECT CEIL(CAST(((@next_bid + @inc_range_start) / @increment) AS DECIMAL(20,10))) * @increment - @inc_range_start),
                    (SELECT FLOOR(CAST(((@next_bid - @inc_range_start) / @increment) AS DECIMAL(20,10))) * @increment + @inc_range_start)
                ) AS quantized_next_bid,

            @asking_bid_if_current_bid_exist :=
                IF(a.reverse,
                    (IF (CAST(@quantized_next_bid AS DECIMAL(20,10)) < CAST(@inc_next_range_start AS DECIMAL(20,10)), CAST(@inc_next_range_start AS DECIMAL(20,10)), CAST(@quantized_next_bid AS DECIMAL(20,10)))),
                    (IF (CAST(@quantized_next_bid AS DECIMAL(20,10)) > CAST(@inc_next_range_start AS DECIMAL(20,10)), CAST(@inc_next_range_start AS DECIMAL(20,10)), CAST(@quantized_next_bid AS DECIMAL(20,10))))
                ) AS asking_bid_if_current_bid_exist,

            @{$this->askingBidAlias} := IF({$bt}.bid IS NULL,
                @{$this->startingBidAlias},
                @asking_bid_if_current_bid_exist) AS {$this->askingBidAlias}
END;
        // @formatter:on
        return $selectExpr;
    }

    /**
     * Return select clause for normalized starting bid
     * @return string
     */
    public function getStartingBidNormalizedSelectClause(): string
    {
        $alias = $this->startingBidAlias;
        // @formatter:off
        $selectExpr = "
            @account_id := ali.account_id AS account_id,
            @" . $alias . " := IF(a.auction_type IN ('" . Constants\Auction::LIVE . "', '" . Constants\Auction::HYBRID . "')," .
                "li.starting_bid, " .
                "IF(
                    li.starting_bid>0,
                    li.starting_bid,
                    IF(a.reverse=false,
                        IF(
                            (SELECT COUNT(1) FROM bid_increment WHERE account_id=@account_id AND bid_increment.lot_item_id=ali.lot_item_id) > 0,
                            (SELECT increment FROM bid_increment WHERE account_id=@account_id AND bid_increment.lot_item_id=ali.lot_item_id ORDER BY amount LIMIT 1),
                            IF(
                                (SELECT COUNT(1) FROM bid_increment WHERE account_id=@account_id AND auction_id=ali.auction_id) > 0,
                                (SELECT increment FROM bid_increment WHERE account_id=@account_id AND auction_id=ali.auction_id ORDER BY amount LIMIT 1),
                                (SELECT increment FROM bid_increment WHERE account_id=@account_id AND auction_type=a.auction_type ORDER BY amount LIMIT 1)
                            )
                        ),
                        NULL
                    )
                )
            ) AS " . $alias;
        // @formatter:on
        return $selectExpr;
    }

    /**
     * @param string $bidTransactionTableAlias
     * @return static
     */
    public function setBidTransactionTableAlias(string $bidTransactionTableAlias): static
    {
        $this->bidTransactionTableAlias = $bidTransactionTableAlias;
        return $this;
    }

    /**
     * @param string $startingBidAlias
     * @return static
     */
    public function setStartingBidAlias(string $startingBidAlias): static
    {
        $this->startingBidAlias = $startingBidAlias;
        return $this;
    }

    /**
     * @param string $askingBidAlias
     * @return static
     */
    public function setAskingBidAlias(string $askingBidAlias): static
    {
        $this->askingBidAlias = $askingBidAlias;
        return $this;
    }
}
