<?php
/**
 * SAM-1537: Walmart - Bulk Barcode/Image Import
 * SAM-1700: Walmart - Bulk image upload enhancements
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

namespace Sam\Lot\Image\BucketImport\Associate\Strategy;

use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Image\BucketImport\Associate\Option\AssociationOption;
use Sam\Lot\Image\BucketImport\Associate\Strategy\Barcode\BarcodeStrategy;
use Sam\Lot\Image\BucketImport\Associate\Strategy\Filename\CustomFieldStrategy;
use Sam\Lot\Image\BucketImport\Associate\Strategy\Filename\ItemNumberStrategy;
use Sam\Lot\Image\BucketImport\Associate\Strategy\Filename\LotImageFilenameStrategy;
use Sam\Lot\Image\BucketImport\Associate\Strategy\Filename\LotNumberStrategy;

/**
 * Class ImageAssociationStrategyFactory
 * @package Sam\Lot\Image\BucketImport\Associate\Strategy
 */
class ImageAssociationStrategyFactory extends CustomizableClass
{
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
     * @return StrategyInterface
     */
    public function create(AssociationOption $option): StrategyInterface
    {
        $strategy = match ($option->associationType) {
            Constants\LotImageImport::ASSOCIATE_BY_LOT_NUMBER => LotNumberStrategy::new(),
            Constants\LotImageImport::ASSOCIATE_BY_ITEM_NUMBER => ItemNumberStrategy::new(),
            Constants\LotImageImport::ASSOCIATE_BY_CUSTOM_FIELD => CustomFieldStrategy::new()->construct($option->customField),
            Constants\LotImageImport::ASSOCIATE_BY_FILENAMES_IN_CSV => LotImageFilenameStrategy::new(),
            Constants\LotImageImport::ASSOCIATE_BY_BARCODE => BarcodeStrategy::new()->construct($option->customField),
            default => throw new InvalidArgumentException("Association type '{$option->associationType}' not supported"),
        };
        return $strategy;
    }
}
