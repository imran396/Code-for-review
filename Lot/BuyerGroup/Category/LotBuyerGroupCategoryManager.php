<?php
/**
 * SAM-4439 : Move lot's buyer group logic to Sam\Lot\BuyerGroup namespace
 * https://bidpath.atlassian.net/browse/SAM-4439
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/6/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\BuyerGroup\Category;

use LotCategoryBuyerGroup;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Lot\BuyerGroup\Validate\LotBuyerGroupExistenceCheckerAwareTrait;
use Sam\Storage\ReadRepository\Entity\LotCategoryBuyerGroup\LotCategoryBuyerGroupReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotCategoryBuyerGroup\LotCategoryBuyerGroupWriteRepositoryAwareTrait;

/**
 * Class LotBuyerGroupCategoryManager
 * @package Sam\Lot\BuyerGroup\Category
 */
class LotBuyerGroupCategoryManager extends CustomizableClass
{
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use LotBuyerGroupExistenceCheckerAwareTrait;
    use LotCategoryBuyerGroupReadRepositoryCreateTrait;
    use LotCategoryBuyerGroupWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Creating new lot category buyer group if not exist.
     *
     * @param int $buyerGroupId
     * @param int $lotCategoryId
     * @param int $editorUserId
     * @return LotCategoryBuyerGroup|null
     *
     */
    public function add(int $buyerGroupId, int $lotCategoryId, int $editorUserId): ?LotCategoryBuyerGroup
    {
        if ($this->getLotBuyerGroupExistenceChecker()->existGroupCategory($buyerGroupId, $lotCategoryId)) {
            return null;
        }

        $lotCategoryBuyerGroup = $this->createEntityFactory()->lotCategoryBuyerGroup();
        $lotCategoryBuyerGroup->Active = true;
        $lotCategoryBuyerGroup->BuyerGroupId = $buyerGroupId;
        $lotCategoryBuyerGroup->LotCategoryId = $lotCategoryId;
        $this->getLotCategoryBuyerGroupWriteRepository()->saveWithModifier($lotCategoryBuyerGroup, $editorUserId);
        return $lotCategoryBuyerGroup;
    }

    /**
     * Removes lot category buyer group by group id and category id also saving user id who has removed it.
     *
     * @param int $buyerGroupId
     * @param int $lotCategoryId
     * @param int $editorUserId
     * @return LotCategoryBuyerGroup|null
     */
    public function remove(int $buyerGroupId, int $lotCategoryId, int $editorUserId): ?LotCategoryBuyerGroup
    {
        $lotCategoryBuyerGroup = $this->load($buyerGroupId, $lotCategoryId);
        if (!$lotCategoryBuyerGroup) {
            return null;
        }

        $lotCategoryBuyerGroup->Active = false;
        $this->getLotCategoryBuyerGroupWriteRepository()->saveWithModifier($lotCategoryBuyerGroup, $editorUserId);
        return $lotCategoryBuyerGroup;
    }

    /**
     * Returns lot category buyer group by filtering active, group id and category id.
     *
     * @param int $buyerGroupId
     * @param int $lotCategoryId
     * @param bool $isReadOnlyDb
     * @return LotCategoryBuyerGroup|null
     */
    protected function load(int $buyerGroupId, int $lotCategoryId, bool $isReadOnlyDb = false): ?LotCategoryBuyerGroup
    {
        return $this->createLotCategoryBuyerGroupReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterBuyerGroupId($buyerGroupId)
            ->filterLotCategoryId($lotCategoryId)
            ->loadEntity();
    }
}
