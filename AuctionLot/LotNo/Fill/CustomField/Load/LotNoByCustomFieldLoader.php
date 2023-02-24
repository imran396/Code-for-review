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

namespace Sam\AuctionLot\LotNo\Fill\CustomField\Load;

use LotItemCustField;
use Sam\AuctionLot\LotNo\Fill\CustomField\Validate\LotNoByCustomFieldChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCustField\LotItemCustFieldReadRepositoryCreateTrait;

/**
 * Custom field used for lot# auto filling loader
 *
 * Class LotNoByCustomFieldLoader
 * @package Sam\AuctionLot\LotNo\Fill\CustomField\Load
 */
class LotNoByCustomFieldLoader extends CustomizableClass
{
    use LotCustomDataLoaderCreateTrait;
    use LotItemCustFieldReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return custom field used for lot# auto filling
     *
     * @param bool $isReadOnlyDb
     * @return LotItemCustField|null
     */
    public function loadCustomField(bool $isReadOnlyDb = false): ?LotItemCustField
    {
        return $this->createLotItemCustFieldReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotNumAutoFill(true)
            ->filterActive(true)
            ->filterType(LotNoByCustomFieldChecker::APPLICABLE_CUSTOM_FIELD_TYPES)
            ->loadEntity();
    }

    /**
     * Return value of custom field used for auto filling
     *
     * @param int $lotItemId lot_item.id
     * @param bool $isReadOnlyDb
     * @return string|int|null
     */
    public function loadCustomFieldValue(int $lotItemId, bool $isReadOnlyDb = false): int|string|null
    {
        $value = null;
        $lotCustomField = $this->loadCustomField($isReadOnlyDb);
        if ($lotCustomField) {
            $lotCustomData = $this->createLotCustomDataLoader()->load($lotCustomField->Id, $lotItemId, $isReadOnlyDb);
            if ($lotCustomData) {
                $value = $lotCustomField->isNumeric()
                    ? $lotCustomData->Numeric : $lotCustomData->Text;
            }
        }
        return $value;
    }
}
