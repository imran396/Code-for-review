<?php
/**
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Associate\Option;

use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;

/**
 * Class AssociationOptionListBuilder
 * @package Sam\Lot\Image\BucketImport\Associate\Option
 */
class AssociationOptionProvider extends CustomizableClass
{
    use LotCustomFieldLoaderCreateTrait;

    protected const AVAILABLE_BARCODE_TYPES = [
        Constants\CustomField::BARCODE_TYPE_CODE_39,
        Constants\CustomField::BARCODE_TYPE_CODE_128,
        Constants\CustomField::BARCODE_TYPE_EAN_13,
        Constants\CustomField::BARCODE_TYPE_UPCA
    ];

    // TODO: add Constants\CustomField::TYPE_INTEGER when "unique" flag will be implemented for it
    protected const AVAILABLE_CUSTOM_FIELD_TYPES = [Constants\CustomField::TYPE_TEXT];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return AssociationOption[]
     */
    public function detectAvailableOptions(): array
    {
        $lotCustomFields = $this->createLotCustomFieldLoader()->loadBarcodeFields(self::AVAILABLE_BARCODE_TYPES, true);
        foreach ($lotCustomFields as $lotCustomField) {
            $options[] = AssociationOption::new()->construct(
                $this->makeKey(Constants\LotImageImport::ASSOCIATE_BY_BARCODE, $lotCustomField),
                Constants\LotImageImport::ASSOCIATE_BY_BARCODE,
                $lotCustomField
            );
        }

        $options[] = AssociationOption::new()->construct(
            $this->makeKey(Constants\LotImageImport::ASSOCIATE_MANUALLY),
            Constants\LotImageImport::ASSOCIATE_MANUALLY
        );
        $options[] = AssociationOption::new()->construct(
            $this->makeKey(Constants\LotImageImport::ASSOCIATE_BY_LOT_NUMBER),
            Constants\LotImageImport::ASSOCIATE_BY_LOT_NUMBER
        );
        $options[] = AssociationOption::new()->construct(
            $this->makeKey(Constants\LotImageImport::ASSOCIATE_BY_ITEM_NUMBER),
            Constants\LotImageImport::ASSOCIATE_BY_ITEM_NUMBER
        );

        $lotCustomFields = $this->createLotCustomFieldLoader()->loadForLotByType(self::AVAILABLE_CUSTOM_FIELD_TYPES, true);
        foreach ($lotCustomFields as $lotCustomField) {
            $options[] = AssociationOption::new()->construct(
                $this->makeKey(Constants\LotImageImport::ASSOCIATE_BY_CUSTOM_FIELD, $lotCustomField),
                Constants\LotImageImport::ASSOCIATE_BY_CUSTOM_FIELD,
                $lotCustomField
            );
        }
        $options[] = AssociationOption::new()->construct(
            $this->makeKey(Constants\LotImageImport::ASSOCIATE_BY_FILENAMES_IN_CSV),
            Constants\LotImageImport::ASSOCIATE_BY_FILENAMES_IN_CSV
        );

        return $options;
    }

    /**
     * @param string $key
     * @return AssociationOption|null
     */
    public function detectByKey(string $key): ?AssociationOption
    {
        $associationOption = null;
        $keyComponents = explode('-', $key);
        $associationType = (int)$keyComponents[0];
        $customFieldId = Cast::toInt($keyComponents[1] ?? null);

        switch ($associationType) {
            case Constants\LotImageImport::ASSOCIATE_MANUALLY:
            case Constants\LotImageImport::ASSOCIATE_BY_LOT_NUMBER:
            case Constants\LotImageImport::ASSOCIATE_BY_ITEM_NUMBER:
            case Constants\LotImageImport::ASSOCIATE_BY_FILENAMES_IN_CSV:
                $associationOption = AssociationOption::new()->construct(
                    $this->makeKey($associationType),
                    $associationType
                );
                break;
            case Constants\LotImageImport::ASSOCIATE_BY_BARCODE:
                $customField = $this->createLotCustomFieldLoader()->load($customFieldId, true);
                if (
                    $customField
                    && $customField->Barcode
                    && in_array($customField->BarcodeType, self::AVAILABLE_BARCODE_TYPES, true)
                ) {
                    $associationOption = AssociationOption::new()->construct(
                        $this->makeKey($associationType, $customField),
                        $associationType,
                        $customField
                    );
                }
                break;
            case Constants\LotImageImport::ASSOCIATE_BY_CUSTOM_FIELD:
                $customField = $this->createLotCustomFieldLoader()->load($customFieldId, true);
                if (
                    $customField
                    && in_array($customField->Type, self::AVAILABLE_CUSTOM_FIELD_TYPES, true)
                ) {
                    $associationOption = AssociationOption::new()->construct(
                        $this->makeKey($associationType, $customField),
                        $associationType,
                        $customField
                    );
                }
                break;
        }
        return $associationOption;
    }

    /**
     * @param int $associationType
     * @param LotItemCustField|null $custField
     * @return string
     */
    protected function makeKey(int $associationType, ?LotItemCustField $custField = null): string
    {
        $key = (string)$associationType;
        if ($custField) {
            $key .= '-' . $custField->Id;
        }
        return $key;
    }
}
