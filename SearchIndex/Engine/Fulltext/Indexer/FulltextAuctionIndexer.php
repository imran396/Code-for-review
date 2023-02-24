<?php
/**
 * Search index functionality related to Auction Item search
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
 * @package       com.swb.sam2.api
 */

namespace Sam\SearchIndex\Engine\Fulltext\Indexer;

use Auction;
use AuctionCustField;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Load\AuctionCustomDataLoaderAwareTrait;
use Sam\CustomField\Auction\Load\AuctionCustomFieldLoaderAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Location\Load\LocationLoaderAwareTrait;
use Sam\SearchIndex\Engine\Fulltext\FulltextIndexerInterface;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionCustField\AuctionCustFieldReadRepositoryCreateTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

/**
 * Class FulltextAuctionIndexer
 */
class FulltextAuctionIndexer extends CustomizableClass implements FulltextIndexerInterface
{
    use AuctionCustFieldReadRepositoryCreateTrait;
    use AuctionCustomDataLoaderAwareTrait;
    use AuctionCustomFieldLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionReadRepositoryCreateTrait;
    use ConfigRepositoryAwareTrait;
    use DateHelperAwareTrait;
    use DbConnectionTrait;
    use FulltextIndexDbManagerCreateTrait;
    use LocationLoaderAwareTrait;
    use TimezoneLoaderAwareTrait;

    protected const ENTITY_TYPE = Constants\Search::ENTITY_AUCTION;

    /**
     * @return static
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * Update search index for Auction by its id
     *
     * @param int $auctionId auction.id
     * @param int $editorUserId
     */
    public function refreshById(int $auctionId, int $editorUserId): void
    {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            return;
        }
        $this->refreshByEntity($auction, $editorUserId);
    }

    /**
     * Update search indexes for all Auctions search indexes
     * Used this in sandbox/refresh_search_index.php
     * @param int $editorUserId
     */
    public function refreshAll(int $editorUserId): void
    {
        $offset = 0;
        $limit = 100;
        $auctionRepository = $this->createAuctionReadRepository()
            ->enableReadOnlyDb(true)
            ->joinAccountFilterActive(true)
            ->setChunkSize($limit);
        $total = $auctionRepository->count();
        while ($auctions = $auctionRepository->loadEntities()) {
            foreach ($auctions as $auction) {
                $this->refreshByEntity($auction, $editorUserId);
            }
            echo 'Auctions indexed: ' . ($offset + count($auctions)) . ' (of ' . $total . ")\n";
            $offset += $limit;
        }
        $this->clearWrongEntry();
    }

    /**
     * Update search index for Auction
     *
     * @param Auction $auction
     * @param int $editorUserId
     */
    public function refreshByEntity(Auction $auction, int $editorUserId): void
    {
        $mainAccountId = $this->cfg()->get('core->portal->mainAccountId');
        if (!$auction->isDeleted()) {
            [$fullContent, $publicContent] = $this->getContentForFulltextSearch($auction, $editorUserId);
            $this->createFulltextIndexDbManager()->updateIndex(
                self::ENTITY_TYPE,
                $auction->Id,
                $fullContent,
                $publicContent,
                $auction->AccountId,
                $editorUserId
            );
        } else {
            $this->createFulltextIndexDbManager()->deleteIndex(self::ENTITY_TYPE, $auction->Id, $auction->AccountId);
            $this->createFulltextIndexDbManager()->deleteIndex(self::ENTITY_TYPE, $auction->Id, $mainAccountId);
        }
    }

    /**
     *  REMOVE wrong entry from search_index_fulltext
     */
    protected function clearWrongEntry(): void
    {
        $auctionEntityType = Constants\Search::ENTITY_AUCTION;
        $sql = <<<SQL
DELETE FROM search_index_fulltext
USING search_index_fulltext
INNER JOIN auction ON auction.id = search_index_fulltext.entity_id
AND search_index_fulltext.entity_type = {$auctionEntityType}
AND auction.account_id != search_index_fulltext.account_id
SQL;
        $this->nonQuery($sql);
    }

    /**
     * @param Auction $auction
     * @param int $editorUserId
     * @return array
     */
    protected function getContentForFulltextSearch(Auction $auction, int $editorUserId): array
    {
        $fullContent = $publicContent = $this->getLocationContent($auction) .
            ' ' . $this->getAuctionContent($auction);
        [$customFieldFullContent, $customFieldPublicContent] = $this->getCustomFieldContent($auction, $editorUserId);
        $fullContent .= ' ' . $customFieldFullContent;
        $publicContent .= ' ' . $customFieldPublicContent;
        return [$fullContent, $publicContent];
    }

    /**
     * @param Auction $auction
     * @return string
     */
    protected function getLocationContent(Auction $auction): string
    {
        $invoiceLocation = $this->getLocationLoader()->loadCommonOrSpecificLocation(Constants\Location::TYPE_AUCTION_INVOICE, $auction, true);
        $output = '';
        if (
            $invoiceLocation
            && $invoiceLocation->Active
        ) {
            $output = $invoiceLocation->Name . ' ' .
                $invoiceLocation->Address . ' ' .
                $invoiceLocation->Country . ' ' .
                $invoiceLocation->State . ' ' .
                $invoiceLocation->City . ' ' .
                $invoiceLocation->Zip;
        }
        return $output;
    }

    /**
     * @param Auction $auction
     * @return string
     */
    protected function getAuctionContent(Auction $auction): string
    {
        $content = $auction->SaleNum . ' ' . $auction->SaleNumExt . ' ' .
            $auction->Name . ' ' . $auction->Description . ' ' .
            $auction->TermsAndConditions . ' ' . $auction->AuctionType . ' ' .
            $auction->AuctionHeldIn . ' ' . $auction->AuctionAuctioneerId . ' ' .
            $auction->Email . ' ' . $auction->SaleGroup . ' ' .
            $auction->BlacklistPhrase . ' ' . $auction->TextMsgNotification . ' ' .
            $auction->EventId . ' ' . $auction->InvoiceNotes . ' ' .
            $auction->ShippingInfo . ' ' . $auction->TaxDefaultCountry . ' ' .
            $auction->GcalEventKey . ' ' . Constants\Auction::$auctionStatusNames[$auction->AuctionStatusId];

        $dateHelper = $this->getDateHelper();

        $timezone = $this->getTimezoneLoader()->load($auction->TimezoneId);
        if ($auction->StartBiddingDate) {
            $content .= ' ' . $dateHelper->formatUtcDate($auction->StartBiddingDate, $auction->AccountId, $timezone->Location ?? null);
        }

        if ($auction->StartClosingDate) {
            $content .= ' ' . $dateHelper->formatUtcDate($auction->StartClosingDate, $auction->AccountId, $timezone->Location ?? null);
        }

        if ($auction->EndDate) {
            $content .= ' ' . $dateHelper->formatUtcDate($auction->EndDate, $auction->AccountId, $timezone->Location ?? null);
        }

        return $content;
    }

    /**
     * Return contents of search index for Auction
     *
     * @param Auction $auction
     * @param int $editorUserId
     * @return array($fullContent, $publicContent)
     */
    protected function getCustomFieldContent(Auction $auction, int $editorUserId): array
    {
        $publicContents = $fullContents = [];
        $publicContentAuctionCustomFields = $this->loadCustomFieldsForPublicContent();
        $publicContentAuctionCustomFieldIds = ArrayHelper::toArrayByProperty($publicContentAuctionCustomFields, 'Id');
        $fullContentAuctionCustomFields = $this->loadCustomFieldsForFullContent();
        foreach ($fullContentAuctionCustomFields as $auctionCustomField) {
            $auctionCustomData = $this->getAuctionCustomDataLoader()->loadOrCreate($auctionCustomField, $auction->Id, $editorUserId);
            $value = '';
            switch ($auctionCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                    $value = $auctionCustomData->Numeric;
                    break;
                case Constants\CustomField::TYPE_DECIMAL:
                    $value = $auctionCustomData->calcDecimalValue((int)$auctionCustomField->Parameters);
                    break;
                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_SELECT:
                case Constants\CustomField::TYPE_FULLTEXT:
                case Constants\CustomField::TYPE_POSTALCODE:
                    $value = $auctionCustomData->Text;
                    break;
                case Constants\CustomField::TYPE_DATE:
                case Constants\CustomField::TYPE_FILE:
                    // not used in search
                    break;
            }
            $fullContents[] = $value;
            if (in_array($auctionCustomField->Id, $publicContentAuctionCustomFieldIds, true)) {
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
     * @return AuctionCustField[]
     */
    protected function loadCustomFieldsForPublicContent(): array
    {
        return $this->createAuctionCustFieldReadRepository()
            ->filterActive(true)
            ->filterPublicList(true)
            ->loadEntities();
    }

    /**
     * Load all custom fields for full content.
     * Must be active, any access rights
     * @return AuctionCustField[]
     */
    protected function loadCustomFieldsForFullContent(): array
    {
        $customFields = $this->getAuctionCustomFieldLoader()->loadAll();
        return $customFields;
    }
}
