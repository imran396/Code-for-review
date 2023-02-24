<?php
/**
 * Special for lot feed db query builder
 *
 * $isEnabledConsiderOptionHideUnsoldLots is disabled by default (false), because when lot is hidden in catalog,
 * it still can be accessed via direct link.
 * SAM-2877 says: "There is currently no need to restrict access to the lot detail page, eg if the direct link is known.
 * This feature is about hiding, not restricting."
 *
 * SAM-3517: Lots feed improvement
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy, Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 18, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\Base;

use Sam\Core\Constants;

/**
 * Class DataSourceMysql
 */
class DataSourceMysql extends \Sam\Core\Lot\LotList\DataSourceMysql
{
    protected string $filterQueryBaseTable = 'lot_item';
    /** @var string */
    protected string $accountNameOrCompany = '';

    /**
     * Return instance of self
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    protected function initResultFieldsMapping(): void
    {
        parent::initResultFieldsMapping();

        // @formatter:off
        $this->resultFieldsMapping['category_values'] = [
            // Eg. "id=1:name=Category 1||id=2:name=Category 2"
            'select' => '(SELECT '
                . 'GROUP_CONCAT('
                    . 'CONCAT_WS('
                        . '":", '
                        . 'CONCAT("id=", lc.id), '
                        . 'CONCAT("name=", lc.name)'
                    . ') SEPARATOR "||"'
                . ')'
                . ' FROM lot_category lc'
                . ' JOIN lot_item_category lic ON lic.lot_category_id = lc.id'
                . ' WHERE lic.lot_item_id = li.id AND lc.active'
                . ' ORDER BY lic.id)',
            'join' => ['lot_item'],
        ];

        $this->resultFieldsMapping['date_sold'] = [
            'select' => "li.date_sold",
            'join' => ['lot_item'],
        ];

        /**
         * Redefine auction's end timezone detection
         * - for timed as expected end TZ
         * - for live/hybrid take start TZ
         */
        $this->resultFieldsMapping['auc_tz_location'] = [
            'select' => 'atz.location',
            'join' => ['auction', 'auction_timezone'],
        ];
        // @formatter:on
    }

    /**
     * Define mapping for result fields
     */
    protected function initializeFilterQueryParts(array $queryParts = []): array
    {
        /**
         * we cannot use ali.lot_status_id condition in where clause, because it filters out un-assigned to auction lots
         */
        $this->dropFilterLotStatusId();

        $queryParts['from'] = [
            'FROM lot_item li',
            'LEFT JOIN auction_lot_item ali ON li.id = ali.lot_item_id'
            . ' AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ')',
        ];
        $queryParts['join'] = $this->getBaseJoins();
        $queryParts['where'] = $this->getBaseConditions();
        $queryParts['select'] = 'SELECT IFNULL(ali.id, 0) `ali_id`, li.id `li_id` ';
        $queryParts['select_count'] = 'SELECT COUNT(li.id) ';
        /**
         * We need to group by li.id, because
         * - lot item may be assigned to several auctions. However, it should have Active status only in one auction according data integrity rule.
         * - data integrity fails, like duplicated invoices, auction bidders
         */
        $queryParts['group'] = 'li.id';

        $queryParts = $this->addAccountFilterQueryParts($queryParts);
        return parent::initializeFilterQueryParts($queryParts);
    }

    public function filterAccountNameOrCompany(string $name): static
    {
        $this->accountNameOrCompany = trim($name);
        return $this;
    }

    /**
     * Overload parent's method
     */
    protected function addAccountFilterQueryParts(array $queryParts): array
    {
        if (
            $this->accountNameOrCompany
            && $this->cfg()->get('core->portal->enabled')
            && $this->cfg()->get('core->portal->mainAccountId') === $this->systemAccountId
        ) {
            $queryParts['join'][] = 'account';
            $queryParts['where'][] = "(acc.company_name = " . $this->escape($this->accountNameOrCompany)
                . " OR acc.name = " . $this->escape($this->accountNameOrCompany) . ")";
        }
        return $queryParts;
    }

    /**
     * Return pre-queries, that define some variables
     */
    protected function getPreQueries(): array
    {
        $preQueries = parent::getPreQueries();
        $preQueries[] = 'SET @group_concat_max_len := @@group_concat_max_len';
        $preQueries[] = 'SET SESSION group_concat_max_len = 4294967295';
        return $preQueries;
    }

    /**
     * Return post-queries, that resume initial state
     */
    protected function getPostQueries(): array
    {
        $postQueries = parent::getPostQueries();
        $postQueries[] = 'SET SESSION group_concat_max_len = @group_concat_max_len';
        return $postQueries;
    }
}
