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

namespace Sam\Lot\Image\BucketImport\Associate\Strategy\Filename\Internal\Load;

/**
 * Trait FilenamePatternAssociationLotsLoaderCreateTrait
 * @package Sam\Lot\Image\BucketImport\Associate\Strategy\Filename\Internal\Load
 */
trait FilenamePatternAssociationLotLoaderCreateTrait
{
    protected ?FilenamePatternAssociationLotLoader $filenamePatternAssociationLotLoader = null;

    /**
     * @return FilenamePatternAssociationLotLoader
     */
    protected function createFilenamePatternAssociationLotLoader(): FilenamePatternAssociationLotLoader
    {
        return $this->filenamePatternAssociationLotLoader ?: FilenamePatternAssociationLotLoader::new();
    }

    /**
     * @param FilenamePatternAssociationLotLoader $filenamePatternAssociationLotsLoader
     * @return static
     * @internal
     */
    public function setFilenamePatternAssociationLotLoader(FilenamePatternAssociationLotLoader $filenamePatternAssociationLotsLoader): static
    {
        $this->filenamePatternAssociationLotLoader = $filenamePatternAssociationLotsLoader;
        return $this;
    }
}
