<?php
/**
 * SAM-1537: Walmart - Bulk Barcode/Image Import
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Associate\Strategy\Barcode\Internal;

use LotImageInBucket;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Lot\Image\BucketImport\Path\LotImageInBucketPathResolverCreateTrait;

/**
 * Class BarcodeRecognizer
 * @package Sam\Lot\Image\BucketImport\Associate\Strategy\Barcode\Internal
 */
class BarcodeRecognizer extends CustomizableClass
{
    use LotImageInBucketPathResolverCreateTrait;
    use FileManagerCreateTrait;

    protected static array $recognizeCache = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function initInstance(): static
    {
        require_once "bcscan/bcscan.php";
        return $this;
    }

    /**
     * Recognize barcode value in image and return it or empty string if it is not recognized
     * @param LotImageInBucket $bucketImage
     * @return string
     */
    public function recognize(LotImageInBucket $bucketImage): string
    {
        if (!array_key_exists($bucketImage->Id, self::$recognizeCache)) {
            $filePath = $this->createLotImageInBucketPathResolver()->makeFilePath($bucketImage);
            if ($this->createFileManager()->exist($filePath)) {
                $fileRootPath = $this->createLotImageInBucketPathResolver()->makeFileRootPath($bucketImage);
                self::$recognizeCache[$bucketImage->Id] = (string)getBarCode($fileRootPath);
            }
        }
        return self::$recognizeCache[$bucketImage->Id];
    }
}
