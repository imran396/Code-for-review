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

namespace Sam\Lot\Image\BucketImport\Associate\AssociationMap;

use LotImageInBucket;
use LotItem;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotItemAssociation
 * @package Sam\Lot\Image\BucketImport\Associate\AssociationMap
 */
class LotItemAssociation extends CustomizableClass
{
    /**
     * @var LotItem
     */
    public LotItem $lotItem;
    /**
     * @var LotImageInBucket[]
     */
    public array $images = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotItem $lotItem
     * @return static
     */
    public function construct(LotItem $lotItem): static
    {
        $this->lotItem = $lotItem;
        return $this;
    }

    /**
     * @param LotImageInBucket $imageInBucket
     * @return static
     */
    public function addImage(LotImageInBucket $imageInBucket): static
    {
        $this->images[] = $imageInBucket;
        return $this;
    }
}
