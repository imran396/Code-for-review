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

use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class LotImageBucketImportOptionNameProvider
 * @package Sam\Lot\Image\BucketImport\Associate\Option
 */
class AssociationOptionNameProvider extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const ASSOCIATION_TYPE_MAP = [
        Constants\LotImageImport::ASSOCIATE_BY_BARCODE => 'barcode',
        Constants\LotImageImport::ASSOCIATE_MANUALLY => 'manual',
        Constants\LotImageImport::ASSOCIATE_BY_LOT_NUMBER => 'lot_number',
        Constants\LotImageImport::ASSOCIATE_BY_ITEM_NUMBER => 'item_number',
        Constants\LotImageImport::ASSOCIATE_BY_CUSTOM_FIELD => 'custom_field',
        Constants\LotImageImport::ASSOCIATE_BY_FILENAMES_IN_CSV => 'filename_in_csv',
    ];

    protected const TRANSLATION_KEY = 'lot.image.bucket_import.associate.type.%s.label';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AssociationOption $option
     * @return string
     */
    public function getOptionName(AssociationOption $option): string
    {
        $translationKey = $this->detectTranslationKey($option->associationType);
        $translationParameters = [];
        if ($option->customField) {
            $translationParameters['customFieldName'] = $option->customField->Name;
        }
        $name = $this->getAdminTranslator()->trans($translationKey, $translationParameters);
        return $name;
    }

    /**
     * @param int $associationType
     * @return string
     */
    protected function detectTranslationKey(int $associationType): string
    {
        if (!array_key_exists($associationType, self::ASSOCIATION_TYPE_MAP)) {
            throw new InvalidArgumentException("Invalid association type '{$associationType}'");
        }
        $key = sprintf(
            self::TRANSLATION_KEY,
            self::ASSOCIATION_TYPE_MAP[$associationType]
        );
        return $key;
    }
}
