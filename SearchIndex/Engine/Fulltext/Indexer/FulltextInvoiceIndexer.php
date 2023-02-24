<?php
/**
 * Search index functionality related to Invoice search
 *
 * SAM-6474: Move full-text search query building and queue management logic to \Sam\SearchIndex namespace
 * SAM-1020: Front End - Search Page - Keyword Search Improvements
 *
 * @author        Pyotr Vorobyov
 * @version       SVN: $Id: $
 * @since         June 19, 2014
 *
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SearchIndex\Engine\Fulltext\Indexer;

use Invoice;
use QMySqli5DatabaseResult;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\SearchIndex\Engine\Fulltext\FulltextIndexerInterface;
use Sam\SearchIndex\Helper\SearchIndexNormalizationHelperCreateTrait;
use Sam\Storage\DeleteRepository\Entity\SearchIndexFulltext\SearchIndexFulltextDeleteRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\Invoice\InvoiceReadRepositoryCreateTrait;

/**
 * Class FulltextInvoiceIndexer
 * @package Sam\SearchIndex\Engine\Fulltext\Indexer
 */
class FulltextInvoiceIndexer extends CustomizableClass implements FulltextIndexerInterface
{
    use ConfigRepositoryAwareTrait;
    use DbConnectionTrait;
    use FulltextIndexDbManagerCreateTrait;
    use InvoiceLoaderAwareTrait;
    use InvoiceReadRepositoryCreateTrait;
    use SearchIndexFulltextDeleteRepositoryCreateTrait;
    use SearchIndexNormalizationHelperCreateTrait;

    protected const ENTITY_TYPE = Constants\Search::ENTITY_INVOICE;

    /**
     * @return static
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * Update search indexes for all Invoice search indexes
     * Used this in sandbox/refresh_search_index.php
     * @param int $editorUserId
     */
    public function refreshAll(int $editorUserId): void
    {
        $index = 0;
        $limit = 100;
        $repo = $this->createInvoiceReadRepository()
            ->enableReadOnlyDb(true)
            ->joinAccountFilterActive(true);
        $total = $repo->count();
        $repo->setChunkSize($limit);
        while ($invoices = $repo->loadEntities()) {
            $index += count($invoices);
            foreach ($invoices as $invoice) {
                $this->refreshByEntity($invoice, $editorUserId);
            }
            echo 'Invoices indexed: ' . $index . ' (of ' . $total . ")\n";
        }
        $this->clearWrongEntry();
    }

    /**
     * Update search index for invoice by its id
     *
     * @param int $invoiceId
     * @param int $editorUserId
     */
    public function refreshById(int $invoiceId, int $editorUserId): void
    {
        $invoice = $this->getInvoiceLoader()->load($invoiceId);
        if (!$invoice) {
            return;
        }
        $this->refreshByEntity($invoice, $editorUserId);
    }

    /**
     * Update search index for invoice
     * @param Invoice $invoice
     * @param int $editorUserId
     */
    public function refreshByEntity(Invoice $invoice, int $editorUserId): void
    {
        $mainAccountId = $this->cfg()->get('core->portal->mainAccountId');
        if ($invoice->isAmongAvailableInvoiceStatuses()) {
            $fullContent = $this->getContentForFullTextSearch($invoice);
            $publicContent = $fullContent;
            $this->createFulltextIndexDbManager()->updateIndex(
                self::ENTITY_TYPE,
                $invoice->Id,
                $fullContent,
                $publicContent,
                $invoice->AccountId,
                $editorUserId
            );
        } else {
            $this->createFulltextIndexDbManager()->deleteIndex(self::ENTITY_TYPE, $invoice->Id, $invoice->AccountId);
            $this->createFulltextIndexDbManager()->deleteIndex(self::ENTITY_TYPE, $invoice->Id, $mainAccountId);
        }
    }

    /**
     *  REMOVE wrong entry from search_index_fulltext
     */
    protected function clearWrongEntry(): void
    {
        $this->createSearchIndexFulltextDeleteRepository()
            ->inlineCondition('sif.account_id != i.account_id')
            ->innerJoinInvoice()
            ->filterEntityType(Constants\Search::ENTITY_INVOICE)
            ->delete();
    }

    /**
     * Get get all data for search invoices by search key
     * @param Invoice $invoice
     * @return string
     */
    protected function getFullContentForInvoicesQuery(Invoice $invoice): string
    {
        // @formatter:off
        $sql = <<<SQL
SELECT
    i.invoice_no AS invoice_no,
    u.username AS user_name,
    u.customer_no AS customer,
    ui.first_name AS user_first_name,
    ui.last_name AS user_last_name,
    ui.phone AS user_phone,
    ui.company_name AS user_company_name,
    iub.phone AS b_phone,
    iub.fax AS b_fax,
    iub.company_name AS b_company_name,
    iub.first_name AS b_first_name,
    iub.last_name AS b_last_name,
    iub.email AS b_email,
    iub.country AS b_country,
    iub.address AS b_address,
    iub.address2 AS b_address2,
    iub.address3 AS b_address3,
    iub.city AS b_city,
    iub.state AS b_state,
    iub.zip AS b_zip,
    ius.phone AS s_phone,
    ius.fax AS s_fax,
    ius.company_name AS s_company_name,
    ius.first_name AS s_first_name,
    ius.last_name AS s_last_name,
    ius.country AS s_country,
    ius.address AS s_address,
    ius.address2 AS s_address2,
    ius.address3 AS s_address3,
    ius.city AS s_city,
    ius.state AS s_state,
    ius.zip AS s_zip,
    auc.name AS sale_name, 
    auc.sale_num AS sale_num, 
    GROUP_CONCAT(DISTINCT IF( licf.type IN (1,2,5) , licd.numeric , licd.text ) SEPARATOR  ' ') AS custom_data,
    GROUP_CONCAT(DISTINCT  li.name SEPARATOR  ' ') AS lot_item_name,
    GROUP_CONCAT(DISTINCT  li.item_num SEPARATOR  ' ') AS lot_item_num,
    GROUP_CONCAT(DISTINCT CASE
    WHEN ucf.type IN (1,2) AND ucf.encrypted = 0
        THEN ucd.numeric
    WHEN ucf.type IN (3,4,6,11) AND ucf.encrypted = 0
        THEN ucd.text
    ELSE NULL END SEPARATOR  ' ') AS user_cust_data
FROM 
    invoice AS i
    INNER JOIN `user` AS u ON u.id = i.bidder_id
    LEFT JOIN invoice_item AS ii ON ii.invoice_id = i.id AND ii.active
    LEFT JOIN auction AS auc ON auc.id = ii.auction_id AND ii.auction_id > 0 
    INNER JOIN lot_item AS li ON li.id = ii.lot_item_id AND ii.invoice_id = i.id AND ii.active 
    LEFT JOIN user_info AS ui ON ui.user_id = i.bidder_id
    LEFT JOIN invoice_user_billing AS iub ON iub.invoice_id = i.id
    LEFT JOIN invoice_user_shipping AS ius ON ius.invoice_id = i.id
    LEFT JOIN lot_item_cust_data AS licd ON  licd.active = 1 AND licd.lot_item_id = ii.lot_item_id
    LEFT JOIN lot_item_cust_field AS licf ON licf.in_admin_search = 1 AND licd.lot_item_cust_field_id = licf.id
    LEFT JOIN user_cust_data AS ucd ON ucd.active =1 AND ucd.user_id = i.bidder_id
    LEFT JOIN user_cust_field AS ucf ON ucf.in_invoices = 1 AND ucf.in_admin_search = 1 AND ucd.user_cust_field_id = ucf.id
WHERE 
    i.id={$invoice->Id}
SQL;
        // @formatter:on
        $dbResult = $this->query($sql);
        $rows = [];
        while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $row['b_phone'] = preg_replace('/[^a-zA-Z0-9]/', '', $row['b_phone']);
            $row['b_fax'] = preg_replace('/[^a-zA-Z0-9]/', '', $row['b_fax']);
            $row['user_phone'] = preg_replace('/[^a-zA-Z0-9]/', '', $row['user_phone']);
            $row['s_phone'] = preg_replace('/[^a-zA-Z0-9]/', '', $row['s_phone']);
            $row['s_fax'] = preg_replace('/[^a-zA-Z0-9]/', '', $row['s_fax']);
            $row = implode(' ', $row);
            $rows[] = $row;
        }

        return $rows[0];
    }

    /**
     * Get content for full text
     *
     * @param Invoice $invoice
     * @return string
     */
    protected function getContentForFullTextSearch(Invoice $invoice): string
    {
        $fullContent = $this->getFullContentForInvoicesQuery($invoice);
        $fullContent = $this->createSearchIndexNormalizationHelper()->filter($fullContent);
        return $fullContent;
    }
}
