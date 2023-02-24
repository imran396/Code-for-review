<?php
/**
 * General search index functionality
 *
 * SAM-6474: Move full-text search query building and queue management logic to \Sam\SearchIndex namespace
 * SAM-1020: Front End - Search Page - Keyword Search Improvements
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Mar 01, 2012
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package com.swb.sam2.api
 */

namespace Sam\SearchIndex;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\SearchIndex\Engine\Entity\EntitySearchIndexManager;
use Sam\SearchIndex\Engine\Fulltext\FulltextSearchIndexManager;

/**
 * Class SearchIndexManager
 */
class SearchIndexManager extends CustomizableClass implements SearchIndexManagerInterface
{
    /**
     * @var SearchIndexManagerInterface[]
     */
    protected array $engines = [];

    /**
     * @return static
     */
    public static function new(): static
    {
        $instance = self::_new(self::class);
        return $instance;
    }

    public function initInstance(): static
    {
        $this->engines = [
            Constants\Search::INDEX_FULLTEXT => FulltextSearchIndexManager::new(),
            Constants\Search::INDEX_NONE => EntitySearchIndexManager::new()
        ];
        return $this;
    }

    /**
     * Refresh search index data for all items of a specific entity type
     *
     * @param int $entityType
     * @param int $editorUserId
     * @return void
     */
    public function refreshAll(int $entityType, int $editorUserId): void
    {
        $this->getEngine()->refreshAll($entityType, $editorUserId);
    }

    /**
     * Update search index data for entity
     *
     * @param int $entityType
     * @param int $entityId
     * @param int $editorUserId
     * @return void
     */
    public function refresh(int $entityType, int $entityId, int $editorUserId): void
    {
        $this->getEngine()->refresh($entityType, $entityId, $editorUserId);
    }

    /**
     * Check if input string is appropriate for searching
     *
     * @param string $searchKey
     * @return bool
     */
    public function checkSearchKey(string $searchKey): bool
    {
        if ($searchKey !== '') {
            $searchKey = $this->normalizeSearchKey($searchKey);
        }
        $success = $searchKey !== '';
        return $success;
    }

    /**
     * @inheritDoc
     */
    public function normalizeSearchKey(string $searchKey): string
    {
        return $this->getEngine()->normalizeSearchKey($searchKey);
    }

    /**
     * @return SearchIndexManagerInterface
     */
    protected function getEngine(): SearchIndexManagerInterface
    {
        $type = ConfigRepository::getInstance()->get('core->search->index->type');
        if (!isset($this->engines[$type])) {
            throw new \RuntimeException("Search engine doesn't exist");
        }
        return $this->engines[$type];
    }
}
