<?php
/**
 *
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Image\LinkPrefix;

/**
 * Trait ImageLinkPrefixAnalyserCreateTrait
 * @package Sam\Image\LinkPrefix
 */
trait ImageLinkPrefixAnalyserCreateTrait
{
    /**
     * @var ImageLinkPrefixAnalyser|null
     */
    protected ?ImageLinkPrefixAnalyser $imageLinkPrefixAnalyser = null;

    /**
     * @return ImageLinkPrefixAnalyser
     */
    protected function createImageLinkPrefixAnalyser(): ImageLinkPrefixAnalyser
    {
        return $this->imageLinkPrefixAnalyser ?: ImageLinkPrefixAnalyser::new();
    }

    /**
     * @param ImageLinkPrefixAnalyser $imageLinkPrefixAnalyser
     * @return $this
     * @internal
     */
    public function setImageLinkPrefixAnalyser(ImageLinkPrefixAnalyser $imageLinkPrefixAnalyser): static
    {
        $this->imageLinkPrefixAnalyser = $imageLinkPrefixAnalyser;
        return $this;
    }
}
