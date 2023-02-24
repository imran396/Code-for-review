<?php
/**
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


namespace Sam\Lot\Image\BucketImport\Associate\Strategy\Filename;

use LotItemCustField;
use Sam\Lot\Image\BucketImport\Associate\Strategy\Filename\Internal\Load\FilenamePatternAssociationLotLoaderCreateTrait;

/**
 * Class CustomFieldStrategy
 * @package Sam\Lot\Image\BucketImport\Associate\Strategy
 */
class CustomFieldStrategy extends FilenameStrategyBase
{
    use FilenamePatternAssociationLotLoaderCreateTrait;

    protected LotItemCustField $customField;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotItemCustField $customField
     * @return static
     */
    public function construct(LotItemCustField $customField): static
    {
        $this->customField = $customField;
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function findLotItems(string $search, int $auctionId): array
    {
        return $this->createFilenamePatternAssociationLotLoader()->loadByCustomField($search, $this->customField, $auctionId);
    }
}
