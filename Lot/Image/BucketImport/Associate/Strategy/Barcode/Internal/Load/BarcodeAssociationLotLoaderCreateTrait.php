<?php
/**
 * SAM-1537: Walmart - Bulk Barcode/Image Import
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Associate\Strategy\Barcode\Internal\Load;

/**
 * Trait BarcodeAssociationLotLoaderCreateTrait
 * @package Sam\Lot\Image\BucketImport\Associate\Strategy\Barcode\Internal\Load
 */
trait BarcodeAssociationLotLoaderCreateTrait
{
    /**
     * @var BarcodeAssociationLotLoader|null
     */
    protected ?BarcodeAssociationLotLoader $barcodeAssociationLotLoader = null;

    /**
     * @return BarcodeAssociationLotLoader
     */
    protected function createBarcodeAssociationLotLoader(): BarcodeAssociationLotLoader
    {
        return $this->barcodeAssociationLotLoader ?: BarcodeAssociationLotLoader::new();
    }

    /**
     * @param BarcodeAssociationLotLoader $barcodeAssociationLotLoader
     * @return static
     * @internal
     */
    public function setBarcodeAssociationLotLoader(BarcodeAssociationLotLoader $barcodeAssociationLotLoader): static
    {
        $this->barcodeAssociationLotLoader = $barcodeAssociationLotLoader;
        return $this;
    }
}
