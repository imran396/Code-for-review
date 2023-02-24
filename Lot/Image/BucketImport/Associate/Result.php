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

namespace Sam\Lot\Image\BucketImport\Associate;

use Sam\Core\Service\CustomizableClass;

/**
 * Class AssociatorResult
 * @package Sam\Lot\Image\BucketImport\Associate
 */
class Result extends CustomizableClass
{
    /**
     * @var bool
     */
    public bool $isSuccess = false;
    /**
     * @var int[]
     */
    public array $removedImageIds = [];
    /**
     * @var array|string[]
     */
    public array $errorMessages = [];
    /**
     * @var array|string[]
     */
    public array $successMessages = [];
    /**
     * @var array|string[]
     */
    public array $warningMessages = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $isSuccess
     * @return static
     */
    public function enableSuccess(bool $isSuccess): static
    {
        $this->isSuccess = $isSuccess;
        return $this;
    }

    /**
     * @param int $id
     * @return static
     */
    public function addRemovedImageId(int $id): static
    {
        $this->removedImageIds[] = $id;
        return $this;
    }

    /**
     * @param array|string[] $errorMessages
     * @return static
     */
    public function setErrorMessages(array $errorMessages): static
    {
        $this->errorMessages = $errorMessages;
        return $this;
    }

    /**
     * @param array|string[] $successMessages
     * @return static
     */
    public function setSuccessMessages(array $successMessages): static
    {
        $this->successMessages = $successMessages;
        return $this;
    }

    /**
     * @param array|string[] $warningMessages
     * @return static
     */
    public function setWarningMessages(array $warningMessages): static
    {
        $this->warningMessages = $warningMessages;
        return $this;
    }
}
