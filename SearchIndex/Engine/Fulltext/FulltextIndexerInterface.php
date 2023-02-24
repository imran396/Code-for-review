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

namespace Sam\SearchIndex\Engine\Fulltext;


interface FulltextIndexerInterface
{
    /**
     * Update search indexes for all search indexes
     * @param int $editorUserId
     */
    public function refreshAll(int $editorUserId): void;

    /**
     * Update search index for entity by its id
     *
     * @param int $entityId
     * @param int $editorUserId
     */
    public function refreshById(int $entityId, int $editorUserId): void;
}
