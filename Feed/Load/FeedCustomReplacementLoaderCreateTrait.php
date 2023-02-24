<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feed\Load;

/**
 * Trait FeedCustomReplacementLoaderCreateTrait
 * @package Sam\Feed\Load
 */
trait FeedCustomReplacementLoaderCreateTrait
{
    /**
     * @var FeedCustomReplacementLoader|null
     */
    protected ?FeedCustomReplacementLoader $feedCustomReplacementLoader = null;

    /**
     * @return FeedCustomReplacementLoader
     */
    protected function createFeedCustomReplacementLoader(): FeedCustomReplacementLoader
    {
        return $this->feedCustomReplacementLoader ?: FeedCustomReplacementLoader::new();
    }

    /**
     * @param FeedCustomReplacementLoader $loader
     * @return static
     * @internal
     */
    public function setFeedCustomReplacementLoader(FeedCustomReplacementLoader $loader): static
    {
        $this->feedCustomReplacementLoader = $loader;
        return $this;
    }
}
