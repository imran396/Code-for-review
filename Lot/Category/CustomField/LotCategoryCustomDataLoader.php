<?php
/**
 * Help methods for loading Lot Category Custom Fields
 * SAM-4040: Lot Category modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 12, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\CustomField;


use LotCategoryCustData;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotCategoryCustData\LotCategoryCustDataReadRepositoryCreateTrait;

/**
 * Class DataLoader
 * @package Sam\Lot\Category\CustomField
 */
class LotCategoryCustomDataLoader extends CustomizableClass
{
    use LotCategoryCustDataReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $lotCategoryId
     * @param int $lotCustomFieldId
     * @param bool $isReadOnlyDb
     * @return LotCategoryCustData|null
     */
    public function load(int $lotCategoryId, int $lotCustomFieldId, bool $isReadOnlyDb = false): ?LotCategoryCustData
    {
        $lotCategoryCustData = $this->createLotCategoryCustDataReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotCategoryId($lotCategoryId)
            ->filterLotItemCustFieldId($lotCustomFieldId)
            ->filterActive(true)
            ->loadEntity();
        return $lotCategoryCustData;
    }
}
