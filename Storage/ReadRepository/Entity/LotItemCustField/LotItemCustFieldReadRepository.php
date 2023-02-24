<?php
/**
 * General repository for LotItemCustField entity
 *
 * SAM-3686 : Custom field related repositories https://bidpath.atlassian.net/browse/SAM-3686
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           23 April, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of LotItemCustField filtered by criteria
 * $lotItemCustFieldRepository = \Sam\Storage\ReadRepository\Entity\LotItemCustField\lotItemCustFieldRepository::new()
 *     ->filterName($mainAccountId)          // single value passed as argument
 *     ->filterActive($active)      // array passed as argument
 *     ->skipId([$myId]);                          // search avoiding these user ids
 * $isFound = $lotItemCustFieldRepository->exist();
 * $count = $lotItemCustFieldRepository->count();
 * $lotItemCustFields = $lotItemCustFieldRepository->loadEntities();
 *
 * // Sample2. Load single LotItemCustField
 * $lotItemCustFieldRepository = \Sam\Storage\ReadRepository\Entity\LotItemCustField\LotItemCustFieldReadRepository::new()
 *     ->filterId(1);
 * $lotItemCustField = $lotItemCustFieldRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\LotItemCustField;

/**
 * Class LotItemCustFieldReadRepository
 * @package Sam\Storage\ReadRepository\Entity\LotItemCustField
 */
class LotItemCustFieldReadRepository extends AbstractLotItemCustFieldReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'lot_item_cust_data' => 'JOIN lot_item_cust_data AS licd ON licf.id = licd.lot_item_cust_field_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join `lot_item_cust_data` table
     * @return static
     */
    public function joinLotItemCustData(): static
    {
        $this->join('lot_item_cust_data');
        return $this;
    }

    /**
     * Define filtering by licd.active
     * @param bool|bool[] $active
     * @return static
     */
    public function joinLotItemCustDataFilterActive(bool|array|null $active): static
    {
        $this->joinLotItemCustData();
        $this->filterArray('licd.active', $active);
        return $this;
    }

    /**
     * Define filtering by licd.lot_item_id
     * @param int|int[] $lotItemIds
     * @return static
     */
    public function joinLotItemCustDataFilterLotItemId(int|array|null $lotItemIds): static
    {
        $this->joinLotItemCustData();
        $this->filterArray('licd.lot_item_id', $lotItemIds);
        return $this;
    }

    /**
     * Define filtering by licd.text
     * @param string|string[] $text
     * @return static
     */
    public function joinLotItemCustDataFilterText(string|array|null $text): static
    {
        $this->joinLotItemCustData();
        $this->filterArray('licd.text', $text);
        return $this;
    }
}

