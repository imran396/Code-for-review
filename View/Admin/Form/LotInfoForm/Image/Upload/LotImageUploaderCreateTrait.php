<?php
/**
 * SAM-7914: Refactor \LotImage_UploadLotImage
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoForm\Image\Upload;

/**
 * Trait LotImageUploaderCreateTrait
 * @package Sam\View\Admin\Form\LotInfoForm\Image\Upload
 */
trait LotImageUploaderCreateTrait
{
    protected ?LotImageUploader $lotImageUploader = null;

    /**
     * @return LotImageUploader
     */
    protected function createLotImageUploader(): LotImageUploader
    {
        return $this->lotImageUploader ?: LotImageUploader::new();
    }

    /**
     * @param LotImageUploader $lotImageUploader
     * @return static
     * @internal
     */
    public function setLotImageUploader(LotImageUploader $lotImageUploader): static
    {
        $this->lotImageUploader = $lotImageUploader;
        return $this;
    }
}
