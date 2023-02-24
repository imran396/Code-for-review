<?php
/**
 * SAM-6695: Image link prefix detection do not provide default value and are not based on account of context
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 03, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\ImageUrl;

/**
 * Trait ImageUrlPathBuilderCreateTrait
 * @package Sam\Application\Url\Build\Internal\ImageUrl
 */
trait ImageUrlPathBuilderCreateTrait
{
    /**
     * @var ImageUrlPathBuilder|null
     */
    protected ?ImageUrlPathBuilder $imageUrlPathBuilder = null;

    /**
     * @return ImageUrlPathBuilder
     */
    protected function createImageUrlPathBuilder(): ImageUrlPathBuilder
    {
        return $this->imageUrlPathBuilder ?: ImageUrlPathBuilder::new();
    }

    /**
     * @param ImageUrlPathBuilder $imageUrlPathBuilder
     * @return $this
     * @internal
     */
    public function setImageUrlPathBuilder(ImageUrlPathBuilder $imageUrlPathBuilder): static
    {
        $this->imageUrlPathBuilder = $imageUrlPathBuilder;
        return $this;
    }
}
