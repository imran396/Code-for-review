<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\InvoiceItem;

use InvoiceItem;
use Sam\Core\Constants\Db;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;

/**
 * Abstract class AbstractInvoiceItemReadRepository
 * @method InvoiceItem[] loadEntities()
 * @method InvoiceItem|null loadEntity()
 */
abstract class AbstractInvoiceItemReadRepository extends ReadRepositoryBase
{
    protected string $table = Db::TBL_INVOICE_ITEM;
    protected string $alias = Db::A_INVOICE_ITEM;

    /**
     * Filter by invoice_item.id
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterId(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.id from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipId(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.id
     * @return static
     */
    public function groupById(): static
    {
        $this->group($this->alias . '.id');
        return $this;
    }

    /**
     * Order by invoice_item.id
     * @param bool $ascending
     * @return static
     */
    public function orderById(bool $ascending = true): static
    {
        $this->order($this->alias . '.id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.id
     * @param int $filterValue
     * @return static
     */
    public function filterIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.invoice_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterInvoiceId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.invoice_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.invoice_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipInvoiceId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.invoice_id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.invoice_id
     * @return static
     */
    public function groupByInvoiceId(): static
    {
        $this->group($this->alias . '.invoice_id');
        return $this;
    }

    /**
     * Order by invoice_item.invoice_id
     * @param bool $ascending
     * @return static
     */
    public function orderByInvoiceId(bool $ascending = true): static
    {
        $this->order($this->alias . '.invoice_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.invoice_id
     * @param int $filterValue
     * @return static
     */
    public function filterInvoiceIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.invoice_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.auction_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterAuctionId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.auction_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.auction_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipAuctionId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.auction_id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.auction_id
     * @return static
     */
    public function groupByAuctionId(): static
    {
        $this->group($this->alias . '.auction_id');
        return $this;
    }

    /**
     * Order by invoice_item.auction_id
     * @param bool $ascending
     * @return static
     */
    public function orderByAuctionId(bool $ascending = true): static
    {
        $this->order($this->alias . '.auction_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.auction_id
     * @param int $filterValue
     * @return static
     */
    public function filterAuctionIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.auction_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.lot_item_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterLotItemId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_item_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.lot_item_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipLotItemId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_item_id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.lot_item_id
     * @return static
     */
    public function groupByLotItemId(): static
    {
        $this->group($this->alias . '.lot_item_id');
        return $this;
    }

    /**
     * Order by invoice_item.lot_item_id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotItemId(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_item_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.lot_item_id
     * @param int $filterValue
     * @return static
     */
    public function filterLotItemIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.lot_item_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.hammer_price
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterHammerPrice(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.hammer_price', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.hammer_price from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipHammerPrice(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.hammer_price', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.hammer_price
     * @return static
     */
    public function groupByHammerPrice(): static
    {
        $this->group($this->alias . '.hammer_price');
        return $this;
    }

    /**
     * Order by invoice_item.hammer_price
     * @param bool $ascending
     * @return static
     */
    public function orderByHammerPrice(bool $ascending = true): static
    {
        $this->order($this->alias . '.hammer_price', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterHammerPriceGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hammer_price', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterHammerPriceGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hammer_price', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterHammerPriceLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hammer_price', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.hammer_price
     * @param float $filterValue
     * @return static
     */
    public function filterHammerPriceLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hammer_price', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.hp_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterHpTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.hp_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipHpTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.hp_tax_amount
     * @return static
     */
    public function groupByHpTaxAmount(): static
    {
        $this->group($this->alias . '.hp_tax_amount');
        return $this;
    }

    /**
     * Order by invoice_item.hp_tax_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByHpTaxAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.hp_tax_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.hp_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpTaxAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.hp_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpTaxAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.hp_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpTaxAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.hp_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpTaxAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.hp_country_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterHpCountryTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_country_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.hp_country_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipHpCountryTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_country_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.hp_country_tax_amount
     * @return static
     */
    public function groupByHpCountryTaxAmount(): static
    {
        $this->group($this->alias . '.hp_country_tax_amount');
        return $this;
    }

    /**
     * Order by invoice_item.hp_country_tax_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByHpCountryTaxAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.hp_country_tax_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.hp_country_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpCountryTaxAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_country_tax_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.hp_country_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpCountryTaxAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_country_tax_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.hp_country_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpCountryTaxAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_country_tax_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.hp_country_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpCountryTaxAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_country_tax_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.hp_state_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterHpStateTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_state_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.hp_state_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipHpStateTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_state_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.hp_state_tax_amount
     * @return static
     */
    public function groupByHpStateTaxAmount(): static
    {
        $this->group($this->alias . '.hp_state_tax_amount');
        return $this;
    }

    /**
     * Order by invoice_item.hp_state_tax_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByHpStateTaxAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.hp_state_tax_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.hp_state_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpStateTaxAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_state_tax_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.hp_state_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpStateTaxAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_state_tax_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.hp_state_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpStateTaxAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_state_tax_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.hp_state_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpStateTaxAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_state_tax_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.hp_county_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterHpCountyTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_county_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.hp_county_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipHpCountyTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_county_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.hp_county_tax_amount
     * @return static
     */
    public function groupByHpCountyTaxAmount(): static
    {
        $this->group($this->alias . '.hp_county_tax_amount');
        return $this;
    }

    /**
     * Order by invoice_item.hp_county_tax_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByHpCountyTaxAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.hp_county_tax_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.hp_county_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpCountyTaxAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_county_tax_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.hp_county_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpCountyTaxAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_county_tax_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.hp_county_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpCountyTaxAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_county_tax_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.hp_county_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpCountyTaxAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_county_tax_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.hp_city_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterHpCityTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_city_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.hp_city_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipHpCityTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_city_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.hp_city_tax_amount
     * @return static
     */
    public function groupByHpCityTaxAmount(): static
    {
        $this->group($this->alias . '.hp_city_tax_amount');
        return $this;
    }

    /**
     * Order by invoice_item.hp_city_tax_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByHpCityTaxAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.hp_city_tax_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.hp_city_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpCityTaxAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_city_tax_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.hp_city_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpCityTaxAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_city_tax_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.hp_city_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpCityTaxAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_city_tax_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.hp_city_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterHpCityTaxAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_city_tax_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.winning_bidder_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterWinningBidderId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.winning_bidder_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.winning_bidder_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipWinningBidderId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.winning_bidder_id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.winning_bidder_id
     * @return static
     */
    public function groupByWinningBidderId(): static
    {
        $this->group($this->alias . '.winning_bidder_id');
        return $this;
    }

    /**
     * Order by invoice_item.winning_bidder_id
     * @param bool $ascending
     * @return static
     */
    public function orderByWinningBidderId(bool $ascending = true): static
    {
        $this->order($this->alias . '.winning_bidder_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.winning_bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterWinningBidderIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.winning_bidder_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.winning_bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterWinningBidderIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.winning_bidder_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.winning_bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterWinningBidderIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.winning_bidder_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.winning_bidder_id
     * @param int $filterValue
     * @return static
     */
    public function filterWinningBidderIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.winning_bidder_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.buyers_premium
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterBuyersPremium(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.buyers_premium', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.buyers_premium from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipBuyersPremium(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.buyers_premium', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.buyers_premium
     * @return static
     */
    public function groupByBuyersPremium(): static
    {
        $this->group($this->alias . '.buyers_premium');
        return $this;
    }

    /**
     * Order by invoice_item.buyers_premium
     * @param bool $ascending
     * @return static
     */
    public function orderByBuyersPremium(bool $ascending = true): static
    {
        $this->order($this->alias . '.buyers_premium', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.buyers_premium
     * @param float $filterValue
     * @return static
     */
    public function filterBuyersPremiumGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.buyers_premium
     * @param float $filterValue
     * @return static
     */
    public function filterBuyersPremiumGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.buyers_premium
     * @param float $filterValue
     * @return static
     */
    public function filterBuyersPremiumLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.buyers_premium
     * @param float $filterValue
     * @return static
     */
    public function filterBuyersPremiumLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.buyers_premium', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.bp_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBpTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.bp_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBpTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.bp_tax_amount
     * @return static
     */
    public function groupByBpTaxAmount(): static
    {
        $this->group($this->alias . '.bp_tax_amount');
        return $this;
    }

    /**
     * Order by invoice_item.bp_tax_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByBpTaxAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_tax_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.bp_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpTaxAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.bp_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpTaxAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.bp_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpTaxAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.bp_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpTaxAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.bp_country_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBpCountryTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_country_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.bp_country_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBpCountryTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_country_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.bp_country_tax_amount
     * @return static
     */
    public function groupByBpCountryTaxAmount(): static
    {
        $this->group($this->alias . '.bp_country_tax_amount');
        return $this;
    }

    /**
     * Order by invoice_item.bp_country_tax_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByBpCountryTaxAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_country_tax_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.bp_country_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpCountryTaxAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_country_tax_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.bp_country_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpCountryTaxAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_country_tax_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.bp_country_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpCountryTaxAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_country_tax_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.bp_country_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpCountryTaxAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_country_tax_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.bp_state_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBpStateTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_state_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.bp_state_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBpStateTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_state_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.bp_state_tax_amount
     * @return static
     */
    public function groupByBpStateTaxAmount(): static
    {
        $this->group($this->alias . '.bp_state_tax_amount');
        return $this;
    }

    /**
     * Order by invoice_item.bp_state_tax_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByBpStateTaxAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_state_tax_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.bp_state_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpStateTaxAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_state_tax_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.bp_state_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpStateTaxAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_state_tax_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.bp_state_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpStateTaxAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_state_tax_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.bp_state_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpStateTaxAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_state_tax_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.bp_county_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBpCountyTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_county_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.bp_county_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBpCountyTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_county_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.bp_county_tax_amount
     * @return static
     */
    public function groupByBpCountyTaxAmount(): static
    {
        $this->group($this->alias . '.bp_county_tax_amount');
        return $this;
    }

    /**
     * Order by invoice_item.bp_county_tax_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByBpCountyTaxAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_county_tax_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.bp_county_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpCountyTaxAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_county_tax_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.bp_county_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpCountyTaxAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_county_tax_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.bp_county_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpCountyTaxAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_county_tax_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.bp_county_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpCountyTaxAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_county_tax_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.bp_city_tax_amount
     * @param float|float[] $filterValue
     * @return static
     */
    public function filterBpCityTaxAmount(float|array $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_city_tax_amount', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.bp_city_tax_amount from result
     * @param float|float[] $skipValue
     * @return static
     */
    public function skipBpCityTaxAmount(float|array $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_city_tax_amount', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.bp_city_tax_amount
     * @return static
     */
    public function groupByBpCityTaxAmount(): static
    {
        $this->group($this->alias . '.bp_city_tax_amount');
        return $this;
    }

    /**
     * Order by invoice_item.bp_city_tax_amount
     * @param bool $ascending
     * @return static
     */
    public function orderByBpCityTaxAmount(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_city_tax_amount', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.bp_city_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpCityTaxAmountGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_city_tax_amount', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.bp_city_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpCityTaxAmountGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_city_tax_amount', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.bp_city_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpCityTaxAmountLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_city_tax_amount', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.bp_city_tax_amount
     * @param float $filterValue
     * @return static
     */
    public function filterBpCityTaxAmountLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_city_tax_amount', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.sales_tax
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterSalesTax(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.sales_tax', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.sales_tax from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipSalesTax(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.sales_tax', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.sales_tax
     * @return static
     */
    public function groupBySalesTax(): static
    {
        $this->group($this->alias . '.sales_tax');
        return $this;
    }

    /**
     * Order by invoice_item.sales_tax
     * @param bool $ascending
     * @return static
     */
    public function orderBySalesTax(bool $ascending = true): static
    {
        $this->order($this->alias . '.sales_tax', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.sales_tax
     * @param float $filterValue
     * @return static
     */
    public function filterSalesTaxLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.sales_tax', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.tax_application
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterTaxApplication(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.tax_application', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.tax_application from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipTaxApplication(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.tax_application', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.tax_application
     * @return static
     */
    public function groupByTaxApplication(): static
    {
        $this->group($this->alias . '.tax_application');
        return $this;
    }

    /**
     * Order by invoice_item.tax_application
     * @param bool $ascending
     * @return static
     */
    public function orderByTaxApplication(bool $ascending = true): static
    {
        $this->order($this->alias . '.tax_application', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.tax_application
     * @param int $filterValue
     * @return static
     */
    public function filterTaxApplicationGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_application', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.tax_application
     * @param int $filterValue
     * @return static
     */
    public function filterTaxApplicationGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_application', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.tax_application
     * @param int $filterValue
     * @return static
     */
    public function filterTaxApplicationLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_application', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.tax_application
     * @param int $filterValue
     * @return static
     */
    public function filterTaxApplicationLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.tax_application', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.subtotal
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterSubtotal(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.subtotal', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.subtotal from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipSubtotal(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.subtotal', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.subtotal
     * @return static
     */
    public function groupBySubtotal(): static
    {
        $this->group($this->alias . '.subtotal');
        return $this;
    }

    /**
     * Order by invoice_item.subtotal
     * @param bool $ascending
     * @return static
     */
    public function orderBySubtotal(bool $ascending = true): static
    {
        $this->order($this->alias . '.subtotal', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.subtotal
     * @param float $filterValue
     * @return static
     */
    public function filterSubtotalGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.subtotal', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.subtotal
     * @param float $filterValue
     * @return static
     */
    public function filterSubtotalGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.subtotal', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.subtotal
     * @param float $filterValue
     * @return static
     */
    public function filterSubtotalLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.subtotal', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.subtotal
     * @param float $filterValue
     * @return static
     */
    public function filterSubtotalLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.subtotal', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.active
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterActive(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.active', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.active from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipActive(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.active', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.active
     * @return static
     */
    public function groupByActive(): static
    {
        $this->group($this->alias . '.active');
        return $this;
    }

    /**
     * Order by invoice_item.active
     * @param bool $ascending
     * @return static
     */
    public function orderByActive(bool $ascending = true): static
    {
        $this->order($this->alias . '.active', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.active
     * @param bool $filterValue
     * @return static
     */
    public function filterActiveLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.active', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.lot_name
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotName(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_name', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.lot_name from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotName(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_name', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.lot_name
     * @return static
     */
    public function groupByLotName(): static
    {
        $this->group($this->alias . '.lot_name');
        return $this;
    }

    /**
     * Order by invoice_item.lot_name
     * @param bool $ascending
     * @return static
     */
    public function orderByLotName(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_name', $ascending);
        return $this;
    }

    /**
     * Filter invoice_item.lot_name by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLotName(string $filterValue): static
    {
        $this->like($this->alias . '.lot_name', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_item.item_no
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterItemNo(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.item_no', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.item_no from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipItemNo(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.item_no', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.item_no
     * @return static
     */
    public function groupByItemNo(): static
    {
        $this->group($this->alias . '.item_no');
        return $this;
    }

    /**
     * Order by invoice_item.item_no
     * @param bool $ascending
     * @return static
     */
    public function orderByItemNo(bool $ascending = true): static
    {
        $this->order($this->alias . '.item_no', $ascending);
        return $this;
    }

    /**
     * Filter invoice_item.item_no by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeItemNo(string $filterValue): static
    {
        $this->like($this->alias . '.item_no', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_item.lot_no
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterLotNo(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.lot_no', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.lot_no from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipLotNo(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.lot_no', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.lot_no
     * @return static
     */
    public function groupByLotNo(): static
    {
        $this->group($this->alias . '.lot_no');
        return $this;
    }

    /**
     * Order by invoice_item.lot_no
     * @param bool $ascending
     * @return static
     */
    public function orderByLotNo(bool $ascending = true): static
    {
        $this->order($this->alias . '.lot_no', $ascending);
        return $this;
    }

    /**
     * Filter invoice_item.lot_no by LIKE statement
     * @param string $filterValue
     * @return static
     */
    public function likeLotNo(string $filterValue): static
    {
        $this->like($this->alias . '.lot_no', "%{$filterValue}%");
        return $this;
    }

    /**
     * Filter by invoice_item.quantity
     * @param float|float[]|null $filterValue
     * @return static
     */
    public function filterQuantity(float|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.quantity from result
     * @param float|float[]|null $skipValue
     * @return static
     */
    public function skipQuantity(float|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.quantity
     * @return static
     */
    public function groupByQuantity(): static
    {
        $this->group($this->alias . '.quantity');
        return $this;
    }

    /**
     * Order by invoice_item.quantity
     * @param bool $ascending
     * @return static
     */
    public function orderByQuantity(bool $ascending = true): static
    {
        $this->order($this->alias . '.quantity', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.quantity
     * @param float $filterValue
     * @return static
     */
    public function filterQuantityGreater(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.quantity
     * @param float $filterValue
     * @return static
     */
    public function filterQuantityGreaterOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.quantity
     * @param float $filterValue
     * @return static
     */
    public function filterQuantityLess(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.quantity
     * @param float $filterValue
     * @return static
     */
    public function filterQuantityLessOrEqual(float $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.quantity_digits
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterQuantityDigits(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.quantity_digits', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.quantity_digits from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipQuantityDigits(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.quantity_digits', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.quantity_digits
     * @return static
     */
    public function groupByQuantityDigits(): static
    {
        $this->group($this->alias . '.quantity_digits');
        return $this;
    }

    /**
     * Order by invoice_item.quantity_digits
     * @param bool $ascending
     * @return static
     */
    public function orderByQuantityDigits(bool $ascending = true): static
    {
        $this->order($this->alias . '.quantity_digits', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.quantity_digits
     * @param int $filterValue
     * @return static
     */
    public function filterQuantityDigitsLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.quantity_digits', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.hp_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterHpTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.hp_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.hp_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipHpTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.hp_tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.hp_tax_schema_id
     * @return static
     */
    public function groupByHpTaxSchemaId(): static
    {
        $this->group($this->alias . '.hp_tax_schema_id');
        return $this;
    }

    /**
     * Order by invoice_item.hp_tax_schema_id
     * @param bool $ascending
     * @return static
     */
    public function orderByHpTaxSchemaId(bool $ascending = true): static
    {
        $this->order($this->alias . '.hp_tax_schema_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterHpTaxSchemaIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_schema_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterHpTaxSchemaIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_schema_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterHpTaxSchemaIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_schema_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.hp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterHpTaxSchemaIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.hp_tax_schema_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.bp_tax_schema_id
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterBpTaxSchemaId(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.bp_tax_schema_id', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.bp_tax_schema_id from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipBpTaxSchemaId(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.bp_tax_schema_id', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.bp_tax_schema_id
     * @return static
     */
    public function groupByBpTaxSchemaId(): static
    {
        $this->group($this->alias . '.bp_tax_schema_id');
        return $this;
    }

    /**
     * Order by invoice_item.bp_tax_schema_id
     * @param bool $ascending
     * @return static
     */
    public function orderByBpTaxSchemaId(bool $ascending = true): static
    {
        $this->order($this->alias . '.bp_tax_schema_id', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterBpTaxSchemaIdGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_schema_id', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterBpTaxSchemaIdGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_schema_id', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterBpTaxSchemaIdLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_schema_id', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.bp_tax_schema_id
     * @param int $filterValue
     * @return static
     */
    public function filterBpTaxSchemaIdLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.bp_tax_schema_id', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.release
     * @param bool|bool[] $filterValue
     * @return static
     */
    public function filterRelease(bool|array $filterValue): static
    {
        $this->filterArray($this->alias . '.release', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.release from result
     * @param bool|bool[] $skipValue
     * @return static
     */
    public function skipRelease(bool|array $skipValue): static
    {
        $this->skipArray($this->alias . '.release', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.release
     * @return static
     */
    public function groupByRelease(): static
    {
        $this->group($this->alias . '.release');
        return $this;
    }

    /**
     * Order by invoice_item.release
     * @param bool $ascending
     * @return static
     */
    public function orderByRelease(bool $ascending = true): static
    {
        $this->order($this->alias . '.release', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.release
     * @param bool $filterValue
     * @return static
     */
    public function filterReleaseGreater(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.release', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.release
     * @param bool $filterValue
     * @return static
     */
    public function filterReleaseGreaterOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.release', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.release
     * @param bool $filterValue
     * @return static
     */
    public function filterReleaseLess(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.release', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.release
     * @param bool $filterValue
     * @return static
     */
    public function filterReleaseLessOrEqual(bool $filterValue): static
    {
        $this->filterInequality($this->alias . '.release', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.row_version
     * @param int|int[] $filterValue
     * @return static
     */
    public function filterRowVersion(int|array $filterValue): static
    {
        $this->filterArray($this->alias . '.row_version', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.row_version from result
     * @param int|int[] $skipValue
     * @return static
     */
    public function skipRowVersion(int|array $skipValue): static
    {
        $this->skipArray($this->alias . '.row_version', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.row_version
     * @return static
     */
    public function groupByRowVersion(): static
    {
        $this->group($this->alias . '.row_version');
        return $this;
    }

    /**
     * Order by invoice_item.row_version
     * @param bool $ascending
     * @return static
     */
    public function orderByRowVersion(bool $ascending = true): static
    {
        $this->order($this->alias . '.row_version', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.row_version
     * @param int $filterValue
     * @return static
     */
    public function filterRowVersionLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.row_version', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.modified_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterModifiedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.modified_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipModifiedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_on', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.modified_on
     * @return static
     */
    public function groupByModifiedOn(): static
    {
        $this->group($this->alias . '.modified_on');
        return $this;
    }

    /**
     * Order by invoice_item.modified_on
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.modified_on
     * @param string $filterValue
     * @return static
     */
    public function filterModifiedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_on', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.modified_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterModifiedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.modified_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.modified_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipModifiedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.modified_by', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.modified_by
     * @return static
     */
    public function groupByModifiedBy(): static
    {
        $this->group($this->alias . '.modified_by');
        return $this;
    }

    /**
     * Order by invoice_item.modified_by
     * @param bool $ascending
     * @return static
     */
    public function orderByModifiedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.modified_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.modified_by
     * @param int $filterValue
     * @return static
     */
    public function filterModifiedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.modified_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.created_by
     * @param int|int[]|null $filterValue
     * @return static
     */
    public function filterCreatedBy(int|array|null $filterValue): static
    {
        $this->filterArray($this->alias . '.created_by', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.created_by from result
     * @param int|int[]|null $skipValue
     * @return static
     */
    public function skipCreatedBy(int|array|null $skipValue): static
    {
        $this->skipArray($this->alias . '.created_by', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.created_by
     * @return static
     */
    public function groupByCreatedBy(): static
    {
        $this->group($this->alias . '.created_by');
        return $this;
    }

    /**
     * Order by invoice_item.created_by
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedBy(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_by', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreater(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByGreaterOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLess(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.created_by
     * @param int $filterValue
     * @return static
     */
    public function filterCreatedByLessOrEqual(int $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_by', $filterValue, '<=');
        return $this;
    }

    /**
     * Filter by invoice_item.created_on
     * @param string|string[] $filterValue
     * @return static
     */
    public function filterCreatedOn(string|array $filterValue): static
    {
        $this->filterArray($this->alias . '.created_on', $filterValue);
        return $this;
    }

    /**
     * Filter out invoice_item.created_on from result
     * @param string|string[] $skipValue
     * @return static
     */
    public function skipCreatedOn(string|array $skipValue): static
    {
        $this->skipArray($this->alias . '.created_on', $skipValue);
        return $this;
    }

    /**
     * Group by invoice_item.created_on
     * @return static
     */
    public function groupByCreatedOn(): static
    {
        $this->group($this->alias . '.created_on');
        return $this;
    }

    /**
     * Order by invoice_item.created_on
     * @param bool $ascending
     * @return static
     */
    public function orderByCreatedOn(bool $ascending = true): static
    {
        $this->order($this->alias . '.created_on', $ascending);
        return $this;
    }

    /**
     * Filter by greater than invoice_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreater(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>');
        return $this;
    }

    /**
     * Filter by equal or greater than invoice_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnGreaterOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '>=');
        return $this;
    }

    /**
     * Filter by less than invoice_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLess(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<');
        return $this;
    }

    /**
     * Filter by equal or less than invoice_item.created_on
     * @param string $filterValue
     * @return static
     */
    public function filterCreatedOnLessOrEqual(string $filterValue): static
    {
        $this->filterInequality($this->alias . '.created_on', $filterValue, '<=');
        return $this;
    }
}
