<?php
/**
 * General repository for LotItemCustData entity
 *
 * SAM-3686:Custom field related repositories https://bidpath.atlassian.net/browse/SAM-3686
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           25 April, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\LotItemCustData;

/**
 * Class LotItemCustDataReadRepository
 * @package Sam\Storage\ReadRepository\Entity\LotItemCustData
 */
class LotItemCustDataReadRepository extends AbstractLotItemCustDataReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = li.account_id',
        'lot_item' => 'JOIN lot_item li ON licd.lot_item_id = li.id',
        'lot_item_cust_field' => 'JOIN lot_item_cust_field licf ON licd.lot_item_cust_field_id = licf.id'
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join 'account' table
     * @return static
     */
    public function joinAccount(): static
    {
        $this->join('account');
        return $this;
    }

    /**
     * Join `lot_item` table
     * @return static
     */
    public function joinLotItem(): static
    {
        $this->join('lot_item');
        return $this;
    }

    /**
     * Left join lot_item table
     * Define filtering by li.active
     * @param bool|bool[] $active
     * @return static
     */
    public function joinLotItemFilterActive(bool|array|null $active): static
    {
        $this->joinLotItem();
        $this->filterArray('li.active', $active);
        return $this;
    }

    /**
     * Left join lot_item table
     * Define filtering by li.account_id
     * @param int|int[] $accountIds
     * @return static
     */
    public function joinLotItemFilterAccountId(int|array|null $accountIds): static
    {
        $this->joinLotItem();
        $this->filterArray('li.account_id', $accountIds);
        return $this;
    }

    /**
     * Join `lot_item_cust_field` table
     * @return static
     */
    public function joinLotItemCustomField(): static
    {
        $this->join('lot_item_cust_field');
        return $this;
    }

    /**
     * Define filtering by licflc.lot_item_cust_field_id
     * @param int|int[]|null $type
     * @return static
     */
    public function joinLotItemCustFieldFilterType(int|array|null $type): static
    {
        $this->joinLotItemCustomField();
        $this->filterArray('licf.type', $type);
        return $this;
    }

    /**
     * Define ordering by li.account_id
     * @param bool $ascending
     * @return static
     */
    public function joinLotItemOrderByAccountId(bool $ascending = true): static
    {
        $this->joinLotItem();
        $this->order('li.account_id', $ascending);
        return $this;
    }
}
