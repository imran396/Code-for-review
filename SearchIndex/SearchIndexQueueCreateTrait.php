<?php
/**
 * SAM-6474: Move full-text search query building and queue management logic to \Sam\SearchIndex namespace
 * SAM-1020: Front End - Search Page - Keyword Search Improvements
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SearchIndex;


/**
 * Trait SearchIndexQueueCreateTrait
 * @package Sam\SearchIndex
 */
trait SearchIndexQueueCreateTrait
{
    /**
     * @var SearchIndexQueue|null
     */
    protected ?SearchIndexQueue $searchIndexQueue = null;

    /**
     * @return SearchIndexQueue
     */
    protected function createSearchIndexQueue(): SearchIndexQueue
    {
        return $this->searchIndexQueue ?: SearchIndexQueue::new();
    }

    /**
     * @param SearchIndexQueue $searchIndexQueue
     * @return static
     * @internal
     */
    public function setSearchIndexQueue(SearchIndexQueue $searchIndexQueue): static
    {
        $this->searchIndexQueue = $searchIndexQueue;
        return $this;
    }
}
