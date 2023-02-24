<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mau 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Validate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Service\CustomizableClass;

/**
 * This class contains range validation result
 *
 * Class RangeValidationResultStatus
 * @package Sam\Consignor\Commission\Edit\Validate
 */
class RangeValidationResultStatus extends CustomizableClass
{
    public readonly int $rangeIndex;
    public readonly string $property;
    public readonly ResultStatus $resultStatus;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $rangeIndex
     * @param string $property
     * @param ResultStatus $resultStatus
     * @return static
     */
    public function construct(int $rangeIndex, string $property, ResultStatus $resultStatus): static
    {
        $this->rangeIndex = $rangeIndex;
        $this->property = $property;
        $this->resultStatus = $resultStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->resultStatus->getMessage();
    }
}
