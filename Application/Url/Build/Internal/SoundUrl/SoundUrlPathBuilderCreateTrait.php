<?php
/**
 * SAM-9373: Refactor play sound to avoid client side caching of stale files
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\SoundUrl;

/**
 * Trait SoundUrlPathBuilderCreateTrait
 * @package Sam\Application\Url\Build\Internal\SoundUrl
 */
trait SoundUrlPathBuilderCreateTrait
{
    /**
     * @var SoundUrlPathBuilder|null
     */
    protected ?SoundUrlPathBuilder $soundUrlPathBuilder = null;

    /**
     * @return SoundUrlPathBuilder
     */
    protected function createSoundUrlPathBuilder(): SoundUrlPathBuilder
    {
        return $this->soundUrlPathBuilder ?: SoundUrlPathBuilder::new();
    }

    /**
     * @param SoundUrlPathBuilder $soundUrlPathBuilder
     * @return $this
     * @internal
     */
    public function setSoundUrlPathBuilder(SoundUrlPathBuilder $soundUrlPathBuilder): static
    {
        $this->soundUrlPathBuilder = $soundUrlPathBuilder;
        return $this;
    }
}
