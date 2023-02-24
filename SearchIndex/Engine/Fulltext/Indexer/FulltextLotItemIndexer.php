<?php
/**
 * Search index functionality related to Lot Item search
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
 */

namespace Sam\SearchIndex\Engine\Fulltext\Indexer;

use LotItem;
use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Renderer\LotCategoryRendererAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\SearchIndex\Engine\Fulltext\FulltextIndexerInterface;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCustField\LotItemCustFieldReadRepositoryCreateTrait;

/**
 * Class FulltextLotItemIndexer
 * @package Sam\SearchIndex\Engine\Fulltext\Indexer
 */
class FulltextLotItemIndexer extends CustomizableClass implements FulltextIndexerInterface
{
    use ConfigRepositoryAwareTrait;
    use DbConnectionTrait;
    use FulltextIndexDbManagerCreateTrait;
    use LotCategoryRendererAwareTrait;
    use LotCustomDataLoaderCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotItemCustFieldReadRepositoryCreateTrait;
    use LotItemLoaderAwareTrait;
    use LotItemReadRepositoryCreateTrait;
    use LotRendererAwareTrait;

    protected const ENTITY_TYPE = Constants\Search::ENTITY_LOT_ITEM;

    protected array $publicAccess = [
        Constants\Role::VISITOR,
        Constants\Role::USER,
        Constants\Role::BIDDER,
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * Update search index for lot by its id
     *
     * @param int $entityId
     * @param int $editorUserId
     */
    public function refreshById(int $entityId, int $editorUserId): void
    {
        $lotItem = $this->getLotItemLoader()->load($entityId);
        if (!$lotItem) {
            return;
        }
        $this->refreshByEntity($lotItem, $editorUserId);
    }

    /**
     * Update search indexes for all lots search indexes
     * Used this in sandbox/refresh_search_index.php
     * @param int $editorUserId
     */
    public function refreshAll(int $editorUserId): void
    {
        $offset = 0;
        $limit = 100;
        $lotItemRepository = $this->createLotItemReadRepository()
            ->enableReadOnlyDb(true)
            ->filterActive(true)
            ->joinAccountFilterActive(true)
            ->setChunkSize($limit);
        $total = $lotItemRepository->count();
        while ($lotItems = $lotItemRepository->loadEntities()) {
            foreach ($lotItems as $lotItem) {
                $this->refreshByEntity($lotItem, $editorUserId);
            }
            echo 'Lots indexed: ' . ($offset + count($lotItems)) . ' (of ' . $total . ")\n";
            $offset += $limit;
        }

        $this->clearWrongEntry();
    }

    /**
     * Update search index for lot
     * @param LotItem $lotItem
     * @param int $editorUserId
     */
    protected function refreshByEntity(LotItem $lotItem, int $editorUserId): void
    {
        $mainAccountId = $this->cfg()->get('core->portal->mainAccountId');
        if ($lotItem->isActive()) {
            [$fullContent, $publicContent] = $this->getContentForFulltextSearch($lotItem);
            $this->createFulltextIndexDbManager()->updateIndex(
                self::ENTITY_TYPE,
                $lotItem->Id,
                $fullContent,
                $publicContent,
                $lotItem->AccountId,
                $editorUserId
            );
        } else {
            $this->createFulltextIndexDbManager()->deleteIndex(self::ENTITY_TYPE, $lotItem->Id, $lotItem->AccountId);
            $this->createFulltextIndexDbManager()->deleteIndex(self::ENTITY_TYPE, $lotItem->Id, $mainAccountId);
        }
    }

    /**
     *  REMOVE wrong entry from search_index_fulltext
     */
    protected function clearWrongEntry(): void
    {
        $lotItemEntityType = Constants\Search::ENTITY_LOT_ITEM;
        $sql = <<<SQL
DELETE FROM search_index_fulltext
USING search_index_fulltext
  INNER JOIN lot_item ON lot_item.id = search_index_fulltext.entity_id
                         AND search_index_fulltext.entity_type = {$lotItemEntityType}
                         AND lot_item.account_id != search_index_fulltext.account_id
SQL;

        $this->nonQuery($sql);
    }

    /**
     * @param LotItem $lotItem
     * @return array
     */
    protected function getContentForFulltextSearch(LotItem $lotItem): array
    {
        $fullContent = $publicContent = $this->getCategoryContent($lotItem) .
            ' ' . $this->getLotItemContent($lotItem);
        [$customFieldFullContent, $customFieldPublicContent] = $this->getCustomFieldContent($lotItem);
        $fullContent .= ' ' . $customFieldFullContent;
        $publicContent .= ' ' . $customFieldPublicContent;
        return [$fullContent, $publicContent];
    }

    /**
     * @param LotItem $lotItem
     * @return string
     */
    protected function getCategoryContent(LotItem $lotItem): string
    {
        $categories = $this->getLotCategoryRenderer()->getCategoriesText($lotItem->Id);
        $categories = preg_replace('/>/mu', ' ', $categories);
        return $categories;
    }

    /**
     * @param LotItem $lotItem
     * @return string
     */
    protected function getLotItemContent(LotItem $lotItem): string
    {
        $content = $this->getLotRenderer()->makeName($lotItem->Name) . ' ' . $lotItem->Description;
        return $content;
    }

    /**
     * Return contents of search index for lot
     *
     * @param LotItem $lotItem
     * @return array($fullContent, $publicContent)
     */
    protected function getCustomFieldContent(LotItem $lotItem): array
    {
        $publicContents = $fullContents = [];
        $publicContentLotCustomFields = $this->loadCustomFieldsForPublicContent();
        $publicContentLotCustomFieldIds = ArrayHelper::toArrayByProperty($publicContentLotCustomFields, 'Id');
        $fullContentLotCustomFields = $this->loadCustomFieldsForFullContent();
        foreach ($fullContentLotCustomFields as $lotCustomField) {
            $lotCustomData = $this->createLotCustomDataLoader()->load($lotCustomField->Id, $lotItem->Id, true);
            if (!$lotCustomData) {
                continue;
            }
            $value = '';
            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                    $value = $lotCustomData->Numeric;
                    break;
                case Constants\CustomField::TYPE_DECIMAL:
                    $value = $lotCustomData->calcDecimalValue((int)$lotCustomField->Parameters);
                    break;
                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_SELECT:
                case Constants\CustomField::TYPE_FULLTEXT:
                case Constants\CustomField::TYPE_POSTALCODE:
                    $value = $lotCustomData->Text;
                    break;
                case Constants\CustomField::TYPE_DATE:
                case Constants\CustomField::TYPE_FILE:
                    // not used in search
                    break;
            }
            $fullContents[] = $value;
            if (in_array($lotCustomField->Id, $publicContentLotCustomFieldIds, true)) {
                $publicContents[] = $value;
            }
        }
        $fullContent = implode(' ', $fullContents);
        $publicContent = implode(' ', $publicContents);
        return [$fullContent, $publicContent];
    }

    /**
     * Load all custom fields for public content.
     * Must be active, in search and VISITOR,USER,BIDDER access rights.
     *
     * @return LotItemCustField[]
     */
    protected function loadCustomFieldsForPublicContent(): array
    {
        return $this->createLotItemCustFieldReadRepository()
            ->filterActive(true)
            ->filterSearchIndex(true)
            ->filterAccess($this->publicAccess)
            ->loadEntities();
    }

    /**
     * Load all custom fields for full content.
     * Must be active, any access rights
     *
     * @return LotItemCustField[]
     */
    protected function loadCustomFieldsForFullContent(): array
    {
        return $this->createLotCustomFieldLoader()->loadAll();
    }
}
