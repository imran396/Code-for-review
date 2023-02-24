<?php
/**
 * SAM-4440: Refactor feed management logic to \Sam\Feed namespace
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/15/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feed\Load;

/**
 * Trait FeedLoaderAwareTrait
 * @package Sam\Feed\Load
 */
trait FeedLoaderAwareTrait
{
    /**
     * @var FeedLoader|null
     */
    protected ?FeedLoader $feedLoader = null;

    /**
     * @return FeedLoader
     */
    protected function getFeedLoader(): FeedLoader
    {
        if ($this->feedLoader === null) {
            $this->feedLoader = FeedLoader::new();
        }
        return $this->feedLoader;
    }

    /**
     * @param FeedLoader $feedLoader
     * @return static
     */
    public function setFeedLoader(FeedLoader $feedLoader): static
    {
        $this->feedLoader = $feedLoader;
        return $this;
    }
}
