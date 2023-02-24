<?php
/**
 * SAM-11587: Refactor Qform_UploadHelper for v4.0
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 20, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\File;

/**
 * Trait UniqueLotImageFileNameGeneratorCreateTrait
 * @package Sam\Lot\Image\File
 */
trait UniqueLotImageFileNameGeneratorCreateTrait
{
    protected ?UniqueLotImageFileNameGenerator $uniqueLotImageFileNameGenerator = null;

    /**
     * @return UniqueLotImageFileNameGenerator
     */
    protected function createUniqueLotImageFileNameGenerator(): UniqueLotImageFileNameGenerator
    {
        return $this->uniqueLotImageFileNameGenerator ?: UniqueLotImageFileNameGenerator::new();
    }

    /**
     * @param UniqueLotImageFileNameGenerator $uniqueLotImageFileNameGenerator
     * @return static
     * @internal
     */
    public function setUniqueLotImageFileNameGenerator(UniqueLotImageFileNameGenerator $uniqueLotImageFileNameGenerator): static
    {
        $this->uniqueLotImageFileNameGenerator = $uniqueLotImageFileNameGenerator;
        return $this;
    }
}
