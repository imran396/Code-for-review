<?php
/**
 * SAM-3695 : Settlement related repositories  https://bidpath.atlassian.net/browse/SAM-3695
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           13 May, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of Settlement filtered by criteria
 * $settlementRepository = \Sam\Storage\ReadRepository\Entity\Settlement\SettlementReadRepository::new()
 *     ->filterActive($active)          // single value passed as argument
 *     ->filterId($ids);   // array passed as argument
 *
 * $isFound = $settlementRepository->exist();
 * $count = $settlementRepository->count();
 * $settlement = $settlementRepository->loadEntities();
 *
 * // Sample2. Load single Settlement
 * $settlementRepository = \Sam\Storage\ReadRepository\Entity\Settlement\SettlementReadRepository::new()
 *     ->filterId(1);
 * $settlement = $settlementRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\Settlement;

/**
 * Class SettlementReadRepository
 * @package Sam\Storage\ReadRepository\Entity\Settlement
 */
class SettlementReadRepository extends AbstractSettlementReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'settlement_check' => 'JOIN settlement_check sch ON sch.settlement_id = s.id',
        'settlement_item' => 'JOIN settlement_item si ON si.settlement_id = s.id',
        'user' => 'JOIN `user` u ON s.consignor_id = u.id',
        'account' => 'JOIN account AS acc ON s.account_id = acc.id',
        'user_info' => 'JOIN user_info ui ON ui.user_id = s.consignor_id',
        'user_billing' => 'JOIN user_billing AS ub ON ub.user_id = s.consignor_id',
        'user_shipping' => 'JOIN user_shipping AS us ON us.user_id = s.consignor_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $ascending
     * @return static
     */
    public function orderByFeesCommissionTotal(bool $ascending = true): static
    {
        $this->order("IFNULL(s.comm_total, 0) + IFNULL(s.extra_charges, 0)", $ascending);
        return $this;
    }

    /**
     * Join 'settlement_check' table
     * @return $this
     */
    public function joinSettlementCheck(): static
    {
        $this->join('settlement_check');
        return $this;
    }

    /**
     * @param array $settlementCheckIds
     * @return $this
     */
    public function joinSettlementCheckFilterId(array $settlementCheckIds): static
    {
        $this->joinSettlementCheck();
        $this->filterArray('sch.id', $settlementCheckIds);
        return $this;
    }

    /**
     * Join `settlement_item` table
     * @return static
     */
    public function joinSettlementItem(): static
    {
        $this->join('settlement_item');
        return $this;
    }

    /**
     * Define filtering by si.active
     * @param bool|bool[] $active
     * @return static
     */
    public function joinSettlementItemFilterActive(bool|array|null $active): static
    {
        $this->joinSettlementItem();
        $this->filterArray('si.active', $active);
        return $this;
    }

    /**
     * join settlement_item table
     * Define filtering by si.lot_item_id
     * @param int|int[] $lotItemId
     * @return static
     */
    public function joinSettlementItemFilterLotItemId(int|array|null $lotItemId): static
    {
        $this->joinSettlementItem();
        $this->filterArray('si.lot_item_id', $lotItemId);
        return $this;
    }

    /**
     * Left join user table
     * @return static
     */
    public function joinUser(): static
    {
        $this->join('user');
        return $this;
    }

    /**
     * Define ordering by u.username
     * @param bool $ascending
     * @return static
     */
    public function joinUserOrderByUsername(bool $ascending = true): static
    {
        $this->order('u.username', $ascending);
        return $this;
    }

    /**
     * Left join Account
     * @return static
     */
    public function joinAccount(): static
    {
        $this->join('account');
        return $this;
    }

    /**
     * filtering by acc.active
     * @param bool $isActive
     * @return static
     */
    public function joinAccountFilterActive(bool|array|null $isActive): static
    {
        $this->joinAccount();
        $this->filterArray('acc.active', $isActive);
        return $this;
    }

    /**
     * join user info
     * @return static
     */
    public function joinUserInfo(): static
    {
        $this->join('user_info');
        return $this;
    }

    /**
     * join user billing
     * @return static
     */
    public function joinUserBilling(): static
    {
        $this->join('user_billing');
        return $this;
    }

    /**
     * join user shipping
     * @return static
     */
    public function joinUserShipping(): static
    {
        $this->join('user_shipping');
        return $this;
    }
}
