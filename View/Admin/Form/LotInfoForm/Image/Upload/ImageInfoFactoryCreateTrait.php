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
 * Trait ImageInfoFactoryCreateTrait
 * @package Sam\View\Admin\Form\LotInfoForm\Image\Upload
 */
trait ImageInfoFactoryCreateTrait
{
    protected ?ImageInfoFactory $imageInfoFactory = null;

    /**
     * @return ImageInfoFactory
     */
    protected function createImageInfoFactory(): ImageInfoFactory
    {
        return $this->imageInfoFactory ?: ImageInfoFactory::new();
    }

    /**
     * @param ImageInfoFactory $imageInfoFactory
     * @return static
     * @internal
     */
    public function setImageInfoFactory(ImageInfoFactory $imageInfoFactory): static
    {
        $this->imageInfoFactory = $imageInfoFactory;
        return $this;
    }
}
