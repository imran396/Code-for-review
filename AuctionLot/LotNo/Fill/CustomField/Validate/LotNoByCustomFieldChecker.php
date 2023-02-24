<?php
/**
 * SAM-5651: Refactor Lot No auto filling service
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\LotNo\Fill\CustomField\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\AuctionLot\LotNo\Fill\CustomField\Load\LotNoByCustomFieldLoaderCreateTrait;
use Sam\Core\Constants;

/**
 * Class LotNoByCustomFieldChecker
 * @package Sam\AuctionLot\LotNo\Fill\CustomField\Validate
 */
class LotNoByCustomFieldChecker extends CustomizableClass
{
    use LotNoByCustomFieldLoaderCreateTrait;

    /**
     * @var array Custom field types, which may be used for lot# auto fill
     */
    public const APPLICABLE_CUSTOM_FIELD_TYPES = [Constants\CustomField::TYPE_INTEGER, Constants\CustomField::TYPE_TEXT];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if custom field with passed values could be used for auto filling
     *
     * @param int|null $lotCustomFieldId - can be null if it is new custom field
     * @param int|null $customFieldType - can be null if it is new custom field
     * @return bool
     */
    public function isApplicableAutoFilling(?int $lotCustomFieldId, ?int $customFieldType): bool
    {
        return $this->isProperType($customFieldType)
            && $this->isApplicableLotCustomField($lotCustomFieldId);
    }

    /**
     * Check if type could be intended for lot# auto filling
     *
     * @param int|null $type lot_item_cust_field.type isProperType
     * @return bool
     */
    private function isProperType(?int $type): bool
    {
        return in_array($type, static::APPLICABLE_CUSTOM_FIELD_TYPES, true);
    }

    /**
     * @param int|null $expectedLotCustomFieldId - can be null if it is new custom field
     * @return bool
     */
    private function isApplicableLotCustomField(?int $expectedLotCustomFieldId): bool
    {
        $lotCustomField = $this->createLotNoByCustomFieldLoader()->loadCustomField();
        return $lotCustomField === null || $lotCustomField->Id === $expectedLotCustomFieldId;
    }
}
