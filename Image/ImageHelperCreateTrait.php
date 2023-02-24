<?php
/**
 * AM-4429: Admin image import client side resize
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Image;

/**
 * Trait ImageHelperCreateTrait
 * @package Sam\Image
 */
trait ImageHelperCreateTrait
{
    /**
     * @var ImageHelper|null
     */
    protected ?ImageHelper $imageHelper = null;

    /**
     * @return ImageHelper
     */
    protected function createImageHelper(): ImageHelper
    {
        return $this->imageHelper ?: ImageHelper::new();
    }

    /**
     * @param ImageHelper $imageHelper
     * @return static
     * @internal
     */
    public function setImageHelper(ImageHelper $imageHelper): static
    {
        $this->imageHelper = $imageHelper;
        return $this;
    }
}
