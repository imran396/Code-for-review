<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 31, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Save;

use LotImage;
use Sam\Core\Service\CustomizableClass;

/**
 * Class BucketImportLotImageProductionResult
 * @package Sam\Lot\Image\BucketImport\Save
 */
class BucketImportLotImageProductionResult extends CustomizableClass
{
    public const STATUS_FAIL = 'FAIL';
    public const STATUS_SUCCESS = 'OK';

    public string $status;
    public ?string $message = null;
    public ?LotImage $savedLotImage = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotImage $lotImage
     * @return static
     */
    public function constructSuccessResult(LotImage $lotImage): static
    {
        $this->status = self::STATUS_SUCCESS;
        $this->savedLotImage = $lotImage;
        return $this;
    }

    /**
     * @param string $message
     * @return static
     */
    public function constructFailResult(string $message): static
    {
        $this->status = self::STATUS_FAIL;
        $this->message = $message;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }
}
