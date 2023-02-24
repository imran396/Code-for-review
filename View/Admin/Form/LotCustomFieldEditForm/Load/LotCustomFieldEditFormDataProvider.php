<?php
/**
 * SAM-6308: Refactor custom field management to separate modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotCustomFieldEditForm\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\CustomField\Lot\OrderNo\LotCustomFieldOrderNoAdviserCreateTrait;
use Sam\CustomField\Lot\Validate\LotCustomFieldExistenceCheckerCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Validate\LotCategoryExistenceCheckerAwareTrait;

/**
 * Class LotCustomFieldEditFormDataProvider
 * @package Sam\View\Admin\Form\LotCustomFieldEditForm\Load
 */
class LotCustomFieldEditFormDataProvider extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use LotCategoryExistenceCheckerAwareTrait;
    use LotCustomFieldExistenceCheckerCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotCustomFieldOrderNoAdviserCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function suggestCustomFieldOrderValue(): int
    {
        return $this->createLotCustomFieldOrderNoAdviser()->suggest();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isExistLotCategory(int $id): bool
    {
        return $this->getLotCategoryExistenceChecker()->existById($id);
    }

    /**
     * @param string $name
     * @param int|null $skipCustomFieldId
     * @return bool
     */
    public function hasCustomFieldsWithName(string $name, ?int $skipCustomFieldId): bool
    {
        return $this->createLotCustomFieldExistenceChecker()->existByName(
            $name,
            $skipCustomFieldId ? [$skipCustomFieldId] : []
        );
    }

    /**
     * @param int $order
     * @param int|null $skipCustomFieldId
     * @return bool
     */
    public function hasCustomFieldsWithOrderNumber(int $order, ?int $skipCustomFieldId): bool
    {
        return $this->createLotCustomFieldExistenceChecker()->existByOrder(
            $order,
            $skipCustomFieldId ? [$skipCustomFieldId] : []
        );
    }

    /**
     * @return array
     */
    public function getAvailableTypes(): array
    {
        $types = [
            Constants\CustomField::TYPE_INTEGER,
            Constants\CustomField::TYPE_DECIMAL,
            Constants\CustomField::TYPE_TEXT,
            Constants\CustomField::TYPE_SELECT,
            Constants\CustomField::TYPE_DATE,
            Constants\CustomField::TYPE_FULLTEXT,
            Constants\CustomField::TYPE_FILE,
            Constants\CustomField::TYPE_POSTALCODE,
        ];
        if ($this->cfg()->get('core->lot->video->enabled')) {
            $types[] = Constants\CustomField::TYPE_YOUTUBELINK;
        }
        return $types;
    }
}
