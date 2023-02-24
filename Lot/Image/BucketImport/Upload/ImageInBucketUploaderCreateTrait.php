<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 2, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Upload;

/**
 * Trait ImageInBucketUploaderCreateTrait
 * @package Sam\Lot\Image\BucketImport\Upload
 */
trait ImageInBucketUploaderCreateTrait
{
    /**
     * @var ImageInBucketUploader|null
     */
    protected ?ImageInBucketUploader $imageInBucketUploader = null;

    /**
     * @return ImageInBucketUploader
     */
    protected function createImageInBucketUploader(): ImageInBucketUploader
    {
        return $this->imageInBucketUploader ?: ImageInBucketUploader::new();
    }

    /**
     * @param ImageInBucketUploader $imageInBucketUploader
     * @return static
     * @internal
     */
    public function setImageInBucketUploader(ImageInBucketUploader $imageInBucketUploader): static
    {
        $this->imageInBucketUploader = $imageInBucketUploader;
        return $this;
    }
}
