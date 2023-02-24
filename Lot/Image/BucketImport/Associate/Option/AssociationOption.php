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
use Sam\Core\Service\CustomizableClass;

/**
 * Class Option
 * @package Sam\Lot\Image\BucketImport\Associate\Option
 */
class AssociationOption extends CustomizableClass
{
    public string $key;
    public int $associationType;
    public ?LotItemCustField $customField;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $key
     * @param int $associationType
     * @param LotItemCustField|null $customField
     * @return static
     */
    public function construct(string $key, int $associationType, ?LotItemCustField $customField = null): static
    {
        $this->key = $key;
        $this->associationType = $associationType;
        $this->customField = $customField;
        return $this;
    }
}
