<?php
/**
 * SAM-11587: Refactor Qform_UploadHelper for v4.0
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\File;

use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;

/**
 * Class UniqueLotImageFileNameGenerator
 * @package Sam\Lot\Image\File
 */
class UniqueLotImageFileNameGenerator extends CustomizableClass
{
    use LotImageLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function generate(string $originalFileName, int $lotItemId, ?int $lotImageId = null): string
    {
        $fileNameInfo = pathinfo(str_replace(' ', '', $originalFileName));
        $fileName = $fileNameInfo['filename'];
        $fileExtension = $fileNameInfo['extension'];
        while ($this->getLotImageLoader()->loadByImageLink("{$fileName}.{$fileExtension}", [], (array)$lotImageId)) {
            $fileName .= '__' . $lotItemId;
        }
        return "{$fileName}.{$fileExtension}";
    }
}
