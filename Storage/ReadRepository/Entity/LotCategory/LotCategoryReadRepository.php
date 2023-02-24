<?php
/**
 * Repository for LotCategory
 *
 * SAM-3692 : Lot Category related repositories  https://bidpath.atlassian.net/browse/SAM-3692
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           20 May, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\LotCategory;

/**
 * Class LotCategoryReadRepository
 * @package Sam\Storage\Repository
 */
class LotCategoryReadRepository extends AbstractLotCategoryReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'lot_item_category' => 'JOIN lot_item_category lic ON lic.lot_category_id = lc.id',
        'auction_lot_item' => 'JOIN auction_lot_item ali ON ali.lot_item_id = lic.lot_item_id',
        'lot_item' => 'JOIN lot_item li ON li.id = lic.lot_item_id',
        'lot_item_cust_field_lot_category' => 'JOIN lot_item_cust_field_lot_category licflc ON licflc.lot_category_id = lc.id',
        'tax_schema_lot_category' => 'JOIN tax_schema_lot_category tslc ON tslc.lot_category_id = lc.id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * join `lot_item_category` table
     * @return static
     */
    public function joinLotItemCategory(): static
    {
        $this->join('lot_item_category');
        return $this;
    }

    /**
     * Define filtering by lic.lot_category_id
     * @param int|int[] $lotCategoryIds
     * @return static
     */
    public function joinLotItemCategoryFilterLotItemId(int|array|null $lotCategoryIds): static
    {
        $this->joinLotItemCategory();
        $this->filterArray('lic.lot_item_id', $lotCategoryIds);
        return $this;
    }

    /**
     * Join `lot_item` table
     * @return static
     */
    public function joinLotItem(): static
    {
        $this->joinLotItemCategory();
        $this->join('lot_item');
        return $this;
    }

    /**
     * Define filtering by li.active
     * @param bool|array $status
     * @return static
     */
    public function joinLotItemFilterActive(bool|array|null $status): static
    {
        $this->joinLotItem();
        $this->filterArray('li.active', $status);
        return $this;
    }

    /**
     * Define filtering by li.account_id
     * @param int|int[]|null $accountId
     * @return static
     */
    public function joinLotItemFilterAccountId(int|array|null $accountId): static
    {
        $this->joinLotItem();
        $this->filterArray('li.account_id', $accountId);
        return $this;
    }

    /**
     * Join `auction_lot_item` table
     * @return static
     */
    public function joinAuctionLotItem(): static
    {
        $this->joinLotItemCategory();
        $this->join('auction_lot_item');
        return $this;
    }

    /**
     * @param int|int[]|null $auctionId
     * @return static
     */
    public function joinAuctionLotItemFilterAuctionId(int|array|null $auctionId): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('ali.auction_id', $auctionId);
        return $this;
    }

    /**
     * Define filtering by ali.lot_status_id
     * @param int|int[] $lotStatusIds
     * @return static
     */
    public function joinAuctionLotItemFilterLotStatusIds(int|array|null $lotStatusIds): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('ali.lot_status_id', $lotStatusIds);
        return $this;
    }

    /**
     * join `lot_item_cust_field_lot_category` table
     * @return static
     */
    public function joinLotItemCustFieldLotCategory(): static
    {
        $this->join('lot_item_cust_field_lot_category');
        return $this;
    }

    /**
     * Define filtering by licflc.lot_item_cust_field_id
     * @param int|array|null $lotCustomFieldId
     * @return static
     */
    public function joinLotItemCustFieldLotCategoryFilterLotItemCustFieldId(int|array|null $lotCustomFieldId): static
    {
        $this->joinLotItemCustFieldLotCategory();
        $this->filterArray('licflc.lot_item_cust_field_id', $lotCustomFieldId);
        return $this;
    }

    /**
     * Define ordering by lic.id
     * @param bool $ascending
     * @return static
     */
    public function orderByLotItemCategoryId(bool $ascending = true): static
    {
        $this->joinLotItem();
        $this->order('lic.id', $ascending);
        return $this;
    }

    /**
     * join `tax_schema_lot_category` table
     * @return static
     */
    public function joinTaxSchemaLotCategory(): static
    {
        $this->join('tax_schema_lot_category');
        return $this;
    }

    /**
     * Define filtering by tslc.tax_schema_id
     * @param int|int[] $taxSchemaIds
     * @return static
     */
    public function joinTaxSchemaLotCategoryFilterTaxSchemaId(int|array|null $taxSchemaIds): static
    {
        $this->joinTaxSchemaLotCategory();
        $this->filterArray('tslc.tax_schema_id', $taxSchemaIds);
        return $this;
    }

    public function joinTaxSchemaLotCategoryFilterActive(bool|array|null $isActive): static
    {
        $this->joinTaxSchemaLotCategory();
        $this->filterArray('tslc.active', $isActive);
        return $this;
    }

}
