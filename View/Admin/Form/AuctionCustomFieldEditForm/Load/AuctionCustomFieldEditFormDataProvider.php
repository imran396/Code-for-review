<?php
/**
 * SAM-6585: Refactor auction custom field management to separate module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Ivan Zgoniaiko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionCustomFieldEditForm\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\OrderNo\AuctionCustomFieldOrderNoAdviserAwareTrait;
use Sam\CustomField\Auction\Validate\AuctionCustomFieldExistenceCheckerAwareTrait;

/**
 * Class AuctionCustomFieldEditFormDataProvider
 * @package Sam\View\Admin\Form\AuctionCustomFieldEditForm\Load
 */
class AuctionCustomFieldEditFormDataProvider extends CustomizableClass
{
    use AuctionCustomFieldExistenceCheckerAwareTrait;
    use AuctionCustomFieldOrderNoAdviserAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int
     */
    public function suggestCustomFieldOrderValue(): int
    {
        return $this->getAuctionCustomFieldOrderNoAdviser()->suggest();
    }

    /**
     * @param string $name
     * @param int|null $skipCustomFieldId
     * @return bool
     */
    public function hasCustomFieldsWithName(string $name, ?int $skipCustomFieldId): bool
    {
        return $this->getAuctionCustomFieldExistenceChecker()->existByName(
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
        return $this->getAuctionCustomFieldExistenceChecker()->existByOrder(
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
            Constants\CustomField::TYPE_CHECKBOX,
            Constants\CustomField::TYPE_PASSWORD,
            Constants\CustomField::TYPE_FILE,
            Constants\CustomField::TYPE_LABEL,
        ];
        return $types;
    }
}
