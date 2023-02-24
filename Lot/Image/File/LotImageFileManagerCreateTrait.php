<?php
/**
 * SAM-11587: Refactor Qform_UploadHelper for v4.0
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\File;

/**
 * Trait LotImageFileManagerCreateTrait
 * @package Sam\Lot\Image\File
 */
trait LotImageFileManagerCreateTrait
{
    protected ?LotImageFileManager $lotImageFileManager = null;

    /**
     * @return LotImageFileManager
     */
    protected function createLotImageFileManager(): LotImageFileManager
    {
        return $this->lotImageFileManager ?: LotImageFileManager::new();
    }

    /**
     * @param LotImageFileManager $lotImageFileManager
     * @return static
     * @internal
     */
    public function setLotImageFileManager(LotImageFileManager $lotImageFileManager): static
    {
        $this->lotImageFileManager = $lotImageFileManager;
        return $this;
    }
}
