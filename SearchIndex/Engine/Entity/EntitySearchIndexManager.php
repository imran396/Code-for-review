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

namespace Sam\SearchIndex\Engine\Entity;


use Sam\Core\Service\CustomizableClass;
use Sam\SearchIndex\SearchIndexManagerInterface;

/**
 * Class EntitySearchIndexManager
 * @package Sam\SearchIndex\Engine\Entity
 */
class EntitySearchIndexManager extends CustomizableClass implements SearchIndexManagerInterface
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    public function refreshAll(int $entityType, int $editorUserId): void
    {
        //Doesn't support this method
        return;
    }

    /**
     * @inheritDoc
     */
    public function refresh(int $entityType, int $entityId, int $editorUserId): void
    {
        //Doesn't support this method
        return;
    }

    /**
     * @inheritDoc
     */
    public function normalizeSearchKey(string $searchKey): string
    {
        return EntitySearchQueryNormalizer::new()->normalize($searchKey);
    }
}
