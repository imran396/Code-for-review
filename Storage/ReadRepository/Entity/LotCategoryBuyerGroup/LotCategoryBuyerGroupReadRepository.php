<?php
/**
 * General repository for LotCategoryBuyerGroup entity
 *
 * SAM-3691: Buyer Group related repositories https://bidpath.atlassian.net/projects/SAM/issues/SAM-3691
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           30 Mar, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of buyer group user filtered by criteria
 * $lotCategoryBuyerGroupRepository = \Sam\Storage\ReadRepository\Entity\LotCategoryBuyerGroup\LotCategoryBuyerGroupReadRepository::new()
 *     ->filterId($ids)      // array passed as argument
 *     ->filterActive(true)
 * $isFound = $lotCategoryBuyerGroupRepository->exist();
 * $count = $lotCategoryBuyerGroupRepository->count();
 * $users = $lotCategoryBuyerGroupRepository->loadEntities();
 *
 */

namespace Sam\Storage\ReadRepository\Entity\LotCategoryBuyerGroup;

/**
 * Class LotCategoryBuyerGroupReadRepository
 */
class LotCategoryBuyerGroupReadRepository extends AbstractLotCategoryBuyerGroupReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'buyer_group' => 'JOIN buyer_group bg ON bg.id = lcbg.buyer_group_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * join `buyer_group` table
     * @return static
     */
    public function joinBuyerGroup(): static
    {
        $this->join('buyer_group');
        return $this;
    }

    /**
     * join buyer_group table and filter bg.active
     * Define filtering by bg.active
     * @param bool|bool[] $active
     * @return static
     */
    public function joinBuyerGroupFilterActive(bool|array|null $active): static
    {
        $this->joinBuyerGroup();
        $this->filterArray('bg.active', $active);
        return $this;
    }
}

