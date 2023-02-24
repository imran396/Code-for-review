<?php
/**
 * SAM-9677: Refactor \Feed\CategoryGet
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Feed\Internal\Build;

/**
 * Trait FeedMakerCreateTrait
 * @package Sam\Lot\Category\Feed\Internal
 */
trait FeedMakerCreateTrait
{
    /**
     * @var FeedMaker|null
     */
    protected ?FeedMaker $feedMaker = null;

    /**
     * @return FeedMaker
     */
    protected function createFeedMaker(): FeedMaker
    {
        return $this->feedMaker ?: FeedMaker::new();
    }

    /**
     * @param FeedMaker $feedMaker
     * @return static
     * @internal
     */
    public function setFeedMaker(FeedMaker $feedMaker): static
    {
        $this->feedMaker = $feedMaker;
        return $this;
    }
}
