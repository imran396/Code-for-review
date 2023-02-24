<?php
/**
 * Fulltext search index related functionality
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

namespace Sam\SearchIndex\Engine\Fulltext;

use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\SearchIndex\Engine\Fulltext\Indexer\FulltextAuctionIndexer;
use Sam\SearchIndex\Engine\Fulltext\Indexer\FulltextInvoiceIndexer;
use Sam\SearchIndex\Engine\Fulltext\Indexer\FulltextLotItemIndexer;
use Sam\SearchIndex\SearchIndexManagerInterface;

/**
 * Class FulltextSearchIndexManager
 * @package Sam\SearchIndex\Engine\Fulltext
 */
class FulltextSearchIndexManager extends CustomizableClass implements SearchIndexManagerInterface
{
    protected FulltextSearchQueryNormalizer $normalizer;

    /**
     * @return static
     */
    public static function new(): static
    {
        $instance = self::_new(self::class);
        return $instance;
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->normalizer = FulltextSearchQueryNormalizer::new();
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function refresh(int $entityType, int $entityId, int $editorUserId): void
    {
        $this->getIndexer($entityType)->refreshById($entityId, $editorUserId);
    }

    /**
     * @inheritDoc
     */
    public function refreshAll(int $entityType, int $editorUserId): void
    {
        $this->getIndexer($entityType)->refreshAll($editorUserId);
    }

    /**
     * @inheritDoc
     */
    public function normalizeSearchKey(string $searchKey): string
    {
        return $this->normalizer->normalize($searchKey);
    }

    /**
     * @param int $entityType
     * @return FulltextIndexerInterface
     */
    protected function getIndexer(int $entityType): FulltextIndexerInterface
    {
        return match ($entityType) {
            Constants\Search::ENTITY_LOT_ITEM => FulltextLotItemIndexer::new(),
            Constants\Search::ENTITY_INVOICE => FulltextInvoiceIndexer::new(),
            Constants\Search::ENTITY_AUCTION => FulltextAuctionIndexer::new(),
            default => throw new InvalidArgumentException("Unknown entity type \"{$entityType}\" for creating fulltext indexer"),
        };
    }
}
