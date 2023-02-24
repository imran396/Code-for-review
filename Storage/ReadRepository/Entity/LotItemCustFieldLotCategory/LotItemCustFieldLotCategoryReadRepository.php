<?php
/**
 * Repository for LotItemCustFieldLot
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

namespace Sam\Storage\ReadRepository\Entity\LotItemCustFieldLotCategory;

/**
 * Class LotItemCustFieldLotCategoryReadRepository
 * @package Sam\Storage\ReadRepository\Entity\LotItemCustFieldLotCategory
 */
class LotItemCustFieldLotCategoryReadRepository extends AbstractLotItemCustFieldLotCategoryReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'lot_category' => 'JOIN lot_category lc ON lc.id = licflc.lot_category_id ',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * join `lot_category` table
     * @return static
     */
    public function joinLotCategory(): static
    {
        $this->join('lot_category');
        return $this;
    }

    /**
     * Define filtering by lc.active
     * @param bool|bool[] $active
     * @return static
     */
    public function joinLotCategoryFilterActive(bool|array|null $active): static
    {
        $this->joinLotCategory();
        $this->filterArray('lc.active', $active);
        return $this;
    }
}
