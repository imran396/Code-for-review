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
 * Class AssociationMap
 * @package Sam\Lot\Image\BucketImport\Associate\AssociationMap
 */
class AssociationMap extends CustomizableClass
{
    /**
     * @var LotItemAssociation[]
     */
    public array $assigned = [];
    /**
     * @var LotImageInBucket[]
     */
    public array $notAssigned = [];

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
     * @param LotImageInBucket $imageInBucket
     * @return static
     */
    public function addAssigned(LotItem $lotItem, LotImageInBucket $imageInBucket): static
    {
        if (!array_key_exists($lotItem->Id, $this->assigned)) {
            $this->assigned[$lotItem->Id] = LotItemAssociation::new()->construct($lotItem);
        }
        $this->assigned[$lotItem->Id]->addImage($imageInBucket);
        return $this;
    }

    /**
     * @param LotImageInBucket $imageInBucket
     * @return static
     */
    public function addNotAssigned(LotImageInBucket $imageInBucket): static
    {
        $this->notAssigned[] = $imageInBucket;
        return $this;
    }
}
