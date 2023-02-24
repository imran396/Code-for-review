<?php
/**
 * SAM-9677: Refactor \Feed\CategoryGet
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Feed\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotCategory\LotCategoryReadRepositoryCreateTrait;

/**
 * Class DataProvider
 * @package Sam\Lot\Category\Feed\Internal
 * @internal
 */
class DataProvider extends CustomizableClass
{
    use LotCategoryReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $parentCategoryId
     * @return array
     */
    public function loadLotCategories(?int $parentCategoryId = null): array
    {
        $repo = $this->createLotCategoryReadRepository()
            ->select(['id', 'name'])
            ->filterActive(true)
            ->filterParentId($parentCategoryId)
            ->orderBySiblingOrder();
        return $repo->loadRows();
    }
}
