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

namespace Sam\SearchIndex\Engine\Fulltext\Indexer;


use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\SearchIndex\Engine\Fulltext\FulltextSearchQueryNormalizer;
use Sam\Storage\DeleteRepository\Entity\SearchIndexFulltext\SearchIndexFulltextDeleteRepositoryCreateTrait;

/**
 * Class FulltextIndexDbManager
 * @package Sam\SearchIndex\Engine\Fulltext\Indexer
 */
class FulltextIndexDbManager extends CustomizableClass
{
    use DbConnectionTrait;
    use SearchIndexFulltextDeleteRepositoryCreateTrait;

    private const TABLE = 'search_index_fulltext';

    protected ?FulltextSearchQueryNormalizer $normalizer = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $entityType
     * @param int $entityId
     * @param string $fullContent
     * @param string $publicContent
     * @param int $accountId
     * @param int $editorUserId
     */
    public function updateIndex(
        int $entityType,
        int $entityId,
        string $fullContent,
        string $publicContent,
        int $accountId,
        int $editorUserId
    ): void {
        $fullContent = implode(' ', $this->getNormalizer()->splitToTokens($fullContent));
        $publicContent = implode(' ', $this->getNormalizer()->splitToTokens($publicContent));
        $query = '(' . $this->escape($accountId) . ', ' .
            $this->escape($entityType) . ', ' .
            $this->escape($entityId) . ', ' .
            $this->escape($publicContent) . ', ' .
            $this->escape($fullContent) . ', ' .
            $this->escape($editorUserId) . ')';
        $query = 'REPLACE `' . self::TABLE . '` (`account_id`, `entity_type`, `entity_id`, `public_content`, `full_content`, `modified_by`) VALUES ' . $query;
        $this->nonQuery($query);
    }

    /**
     * @param int $entityType
     * @param int $entityId
     * @param int $accountId
     */
    public function deleteIndex(int $entityType, int $entityId, int $accountId): void
    {
        $this->createSearchIndexFulltextDeleteRepository()
            ->filterAccountId($accountId)
            ->filterEntityType($entityType)
            ->filterEntityId($entityId)
            ->delete();
    }

    /**
     * @return FulltextSearchQueryNormalizer
     */
    protected function getNormalizer(): FulltextSearchQueryNormalizer
    {
        if (!$this->normalizer) {
            $this->normalizer = FulltextSearchQueryNormalizer::new();
        }
        return $this->normalizer;
    }
}
