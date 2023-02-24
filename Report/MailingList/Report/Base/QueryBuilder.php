<?php
/**
 *
 * SAM-4751: Refactor mailing list report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-15
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\MailingList\Report\Base;

use InvalidArgumentException;
use MailingListTemplateCategories;
use OutOfRangeException;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Report\MailingList\Load\MailingListTemplateLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\MailingListTemplateAwareTrait;

/**
 * Class QueryBuilder
 * @package Sam\Report\MailingList\Report\Base
 */
abstract class QueryBuilder extends CustomizableClass
{
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use LimitInfoAwareTrait;
    use MailingListTemplateAwareTrait;
    use MailingListTemplateLoaderAwareTrait;
    use SortInfoAwareTrait;

    /** @var string */
    protected string $alias = 'u';
    /** @var string */
    protected string $table = 'user';

    /** @var string[] */
    protected array $resultFieldsMapping = [
        'username' => 'u.`username` AS username',
        'company' => 'ui.`company_name` AS company',
        'email' => 'u.`email` AS email',
        'user_flag' => 'u.`flag` AS user_flag',
        'customer_no' => 'u.`customer_no` AS customer_no',
        'first_name' => 'ui.`first_name` AS first_name',
        'last_name' => 'ui.`last_name` AS last_name',
        'user_phone' => 'ui.`phone` AS `user_phone`',
        'phone_type' => 'ui.`phone_type` AS phone_type',
        'text_alert' => 'ui.`send_text_alerts` AS `text_alert`',
        'sales_tax' => 'ui.`sales_tax` AS sales_tax',
        'apply_tax' => 'ui.`tax_application` AS apply_tax',
        'note' => 'ui.`note` AS note',
        'identification' => 'ui.`identification` AS identification',
        'identification_type' => 'ui.`identification_type` AS identification_type ',
        'shipping_contact_type' => 'ush.`contact_type` AS `shipping_contact_type`',
        'shipping_company_name' => 'ush.`company_name` AS `shipping_company_name`',
        'shipping_first_name' => 'ush.`first_name` AS shipping_first_name',
        'shipping_last_name' => 'ush.`last_name` AS shipping_last_name',
        'shipping_phone' => 'ush.`phone` AS shipping_phone',
        'shipping_fax' => 'ush.`fax` AS shipping_fax',
        'shipping_country' => 'ush.`country` AS shipping_country',
        'shipping_address' => 'ush.`address` AS shipping_address',
        'shipping_address_ln2' => 'ush.`address2` AS shipping_address_ln2',
        'shipping_address_ln3' => 'ush.`address3` AS shipping_address_ln3',
        'shipping_city' => 'ush.`city` AS shipping_city',
        'shipping_state' => 'ush.`state` AS shipping_state',
        'shipping_zip' => 'ush.`zip` AS shipping_zip',
        'ip_address' => 'ul.ip_address AS ip_address',
        'location_name' => 'location.name AS location_name',
    ];

    /** @var string[] */
    protected array $nonSortableFields = [
        'user_flag',
        'apply_tax',
    ];

    /** @var string[] */
    protected array $availableReturnFields = [];
    /** @var string[] */
    protected array $defaultReturnFields = [
        'customer_no',
        'username',
        'company',
        'email',
        'user_flag',
        'first_name',
        'last_name',
        'user_phone',
        'phone_type',
        'text_alert',
        'sales_tax',
        'apply_tax',
        'note',
        'identification',
        'identification_type',
        'shipping_contact_type',
        'shipping_company_name',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_phone',
        'shipping_fax',
        'shipping_country',
        'shipping_address',
        'shipping_address_ln2',
        'shipping_address_ln3',
        'shipping_city',
        'shipping_state',
        'shipping_zip',
        'ip_address',
        'location_name',
    ];
    /** @var string[] */
    protected array $returnFields = [];
    /** @var array<string, array{'asc': string, 'desc': string}> */
    protected array $defaultSortOrders = [];
    /** @var array<string, array{'asc': string, 'desc': string}> */
    protected array $orderFieldsMapping = [];
    /** @var string */
    protected string $sortOrderDefaultIndex = 'username';
    /** @var int[] */
    protected array $lotCategoryIds = [];
    /** @var string[]|null */
    protected ?array $queryParts = null;

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->initSortFields();
        return $this;
    }

    /**
     * Build Count Query
     * @return string|null
     */
    public function buildCountQuery(): ?string
    {
        $countQuery = null;
        $queryParts = $this->getQueryParts();
        if ($queryParts) {
            if ($this->isGroupClause()) {
                $countQuery = $this->buildCountQueryForGrouping();
            } else {
                $countQuery = $queryParts['select_count']
                    . $queryParts['from']
                    . $queryParts['where']
                    . $queryParts['group'];
            }
        }
        return $countQuery;
    }

    /**
     * @return string
     */
    public function buildCountQueryForGrouping(): string
    {
        $queryParts = $this->getQueryParts();
        return $queryParts['select_count']
            . "FROM `{$this->table}` AS {$this->alias}2 "
            . "WHERE EXISTS ("
            . "SELECT {$this->alias}.id"
            . $queryParts['from']
            . $queryParts['where'] . ($queryParts['where'] ? ' AND' : ' WHERE')
            . " {$this->alias}2.id = {$this->alias}.id "
            . $queryParts['group'] . ")";
    }

    /**
     * Build Result Query
     * @return string|null
     */
    public function buildResultQuery(): ?string
    {
        $resultQuery = null;
        $queryParts = $this->getQueryParts();
        if ($queryParts) {
            $resultQuery = $queryParts['select']
                . $queryParts['from']
                . $queryParts['where']
                . $queryParts['group']
                . $queryParts['order']
                . $queryParts['limit'];
        }
        return $resultQuery;
    }

    /**
     * Get Query Parts
     * @return array
     */
    public function getQueryParts(): array
    {
        if ($this->queryParts === null) {
            $this->buildQueryParts();
        }
        // we want to rebuild LIMIT clause in every call
        $this->queryParts['limit'] = $this->getLimitClause();
        return $this->queryParts;
    }

    protected function buildQueryParts(): void
    {
        $this->queryParts = [
            'select' => $this->getSelectClause(),
            'select_count' => 'SELECT COUNT(1) AS `total`',
            'from' => $this->getFromClause(),
            'where' => $this->getWhereClause(),
            'order' => $this->getOrderClause(),
            'group' => $this->getGroupClause(),
        ];
    }

    /**
     * @param array $queryParts
     * @return static
     */
    public function setQueryParts(array $queryParts): static
    {
        $this->queryParts = $queryParts;
        return $this;
    }

    /**
     * Get Return Fields
     * @return array
     */
    public function getReturnFields(): array
    {
        return $this->returnFields;
    }

    /**
     * Set Return Fields
     * @param string[] $returnFields
     * @return static
     */
    public function setReturnFields(array $returnFields): static
    {
        $this->returnFields = $returnFields;
        return $this;
    }

    /**
     * Get Available Return Fields
     * @return string[]
     */
    public function getAvailableReturnFields(): array
    {
        return $this->availableReturnFields;
    }

    /**
     * Set Available Return Fields
     * @param string[] $availableReturnFields
     * @return static
     */
    public function setAvailableReturnFields(array $availableReturnFields): static
    {
        $this->availableReturnFields = $availableReturnFields;
        return $this;
    }

    /**
     * Get nonSortableFields
     * @return string[]
     */
    public function getNonSortableFields(): array
    {
        return $this->nonSortableFields;
    }

    /**
     * Get resultFieldsMapping
     * @return array
     */
    public function getResultFieldsMapping(): array
    {
        return $this->resultFieldsMapping;
    }

    /**
     * Get defaultReturnFields
     * @return string[]
     */
    public function getDefaultReturnFields(): array
    {
        return $this->defaultReturnFields;
    }

    /**
     * define output default sort orders
     * based on User export default fields
     */
    protected function setDefaultSortOrders(): void
    {
        foreach ($this->availableReturnFields as $key => $returnField) {
            $tempField = substr($returnField, 0, strpos($returnField, "AS"));
            $sortProps = [
                'asc' => $tempField . " ASC",
                'desc' => $tempField . " DESC",
            ];
            $this->defaultSortOrders[$key] = $sortProps;
        }
        if (empty($this->orderFieldsMapping)) {
            $this->orderFieldsMapping = $this->defaultSortOrders;
        }
    }

    /**
     * @return string
     */
    protected function getSelectClause(): string
    {
        return '';
    }

    /**
     * Get From Clause
     * @return string
     */
    protected function getFromClause(): string
    {
        $mailingListId = $this->getMailingListTemplateId();
        $mailingListTemplate = $this->getMailingListTemplateLoader()->load($mailingListId);
        if (!$mailingListTemplate) {
            throw new InvalidArgumentException('Cannot load mailing list template by id' . composeSuffix(['id' => $mailingListId]));
        }

        $userType = $mailingListTemplate->UserType;

        $join = match ($userType) {
            Constants\MailingListTemplate::UT_BIDDER => " JOIN `bidder` as b ON b.user_id = u.id ",
            Constants\MailingListTemplate::UT_CONSIGNOR => " JOIN `consignor` as c ON c.user_id = u.id ",
            default => '',
        };
        $join .= " LEFT JOIN `user_info` as ui ON ui.user_id = u.id";

        $auctionIdCond = '';
        if ($mailingListTemplate->AuctionId) {
            $auctionIdCond = " AND auction_id = " . $this->escape($mailingListTemplate->AuctionId);
        }

        $periodStartCond = '';
        if ($mailingListTemplate->PeriodStart) {
            $periodStartCond = " AND date_sold >= " . $this->escape($mailingListTemplate->PeriodStart);
        }

        $periodEndCond = '';
        if ($mailingListTemplate->PeriodEnd) {
            $periodEndCond = " AND date_sold <= " . $this->escape($mailingListTemplate->PeriodEnd);
        }

        if (
            $userType === Constants\MailingListTemplate::UT_BIDDER
            && (
                $mailingListTemplate->AuctionId
                || $mailingListTemplate->PeriodStart
                || $mailingListTemplate->PeriodEnd
                || $mailingListTemplate->MoneySpentTo
                || $mailingListTemplate->MoneySpentFrom
            )
        ) {
            $join .= " JOIN (SELECT COALESCE(SUM(hammer_price),0) as hammer_sum, winning_bidder_id FROM `lot_item` " .
                "WHERE winning_bidder_id IS NOT NULL "
                . $periodStartCond
                . $periodEndCond
                . $auctionIdCond
                . " GROUP BY winning_bidder_id)"
                . " as win_sum ON u.id = win_sum.winning_bidder_id ";
        }
        $mailingListCategories = $this->getMailingListTemplateLoader()->loadTemplateCategories($mailingListTemplate->Id);
        $this->addCategoryIdsWithParents($mailingListCategories);

        if (!empty($this->lotCategoryIds)) {
            $join .= " JOIN `my_search` as ms ON ms.user_id = u.id";
            $join .= " JOIN `my_search_category` as msc ON ms.id = msc.my_search_id";
        }

        $join .= " LEFT JOIN `user_shipping` as ush ON u.id = ush.user_id";
        $join .= " LEFT JOIN `user_billing`  as ub ON u.id = ub.user_id";
        $join .= " LEFT JOIN `user_login` as ul ON ul.user_id = u.id";
        $join .= " LEFT JOIN `location` as location ON location.account_id = u.account_id";

        $query = " FROM `$this->table` as $this->alias ";

        $query .= $join;

        return $query;
    }

    /**
     * Get SQL Where Clause
     * @return string
     */
    protected function getWhereClause(): string
    {
        $accountId = $this->getFilterAccountId();
        if (is_array($accountId)) {
            $accountId = reset($accountId);
        }

        $mailingListTemplate = $this->getMailingListTemplate();
        if (!$mailingListTemplate) {
            throw new InvalidArgumentException(
                'Cannot load mailing list template by id'
                . composeSuffix(['id' => $this->getMailingListTemplateId()])
            );
        }

        $query = ' WHERE u.user_status_id = ' . Constants\User::US_ACTIVE . ' ';

        $categoriesQuery = '';
        if (!empty($this->lotCategoryIds)) {
            $categoriesQuery = $this->getCategoryCondition();
        }

        $moneySpentQuery = '';
        $consignorCond = '';

        $userType = $mailingListTemplate->UserType;
        if ($userType === Constants\MailingListTemplate::UT_BIDDER) {
            if ($mailingListTemplate->MoneySpentTo) {
                $moneySpentQuery .= " AND win_sum.hammer_sum <= " . $this->escape($mailingListTemplate->MoneySpentTo) . " ";
            }

            if ($mailingListTemplate->MoneySpentFrom) {
                $moneySpentQuery .= " AND win_sum.hammer_sum  >= " . $this->escape($mailingListTemplate->MoneySpentFrom) . " ";
            }
        }

        if ($userType === Constants\MailingListTemplate::UT_CONSIGNOR) {
            $consignorCond .= " AND u.id IN (SELECT `consignor_id` FROM `lot_item` "
                . "WHERE active AND `id` IN (SELECT `lot_item_id` "
                . "FROM `auction_lot_item` WHERE lot_status_id IN ("
                . implode(',', Constants\Lot::$availableLotStatuses) . ") AND %s )) ";

            if ($mailingListTemplate->AuctionId) {
                $auctionIdCond = " `auction_id` = " . $this->escape($mailingListTemplate->AuctionId);
            } else {
                $auctionIdCond = " `auction_id` IN (SELECT `id` FROM `auction` a WHERE %s AND %s ) ";
                $periodStartCond = " TRUE ";
                if ($mailingListTemplate->PeriodStart) {
                    $periodStartCond = " (IF(a.auction_type='" . Constants\Auction::TIMED . "', "
                        . "(IF(a.event_type=" . Constants\Auction::ET_ONGOING . ",a.created_on,a.end_date)), "
                        . "a.start_closing_date)) >= " . $this->escape($mailingListTemplate->PeriodStart);
                }

                $periodEndCond = " TRUE ";
                if ($mailingListTemplate->PeriodEnd) {
                    $periodEndCond = " (IF(a.auction_type='" . Constants\Auction::TIMED . "', "
                        . "(IF(a.event_type=" . Constants\Auction::ET_ONGOING . ",a.created_on,a.end_date)), "
                        . "a.start_closing_date)) <= " . $this->escape($mailingListTemplate->PeriodEnd);
                }
                $auctionIdCond = sprintf($auctionIdCond, $periodStartCond, $periodEndCond);
            }
            $consignorCond = sprintf($consignorCond, $auctionIdCond);
        }

        $query .= $categoriesQuery . $moneySpentQuery . $consignorCond;

        if ($accountId > 0) {
            $query .= ' AND u.account_id = ' . $this->escape($accountId) . ' ';
        }

        return $query;
    }

    /**
     * Get Group Clause
     * @return string
     */
    protected function getGroupClause(): string
    {
        return 'GROUP BY u.id ';
    }

    /**
     * Get Limit Clause
     * @return string
     */
    protected function getLimitClause(): string
    {
        $limit = $this->getLimit();
        if ($limit === null) {
            return '';
        }
        $query = $limit;

        $offset = $this->getOffset();
        if ($offset) {
            $query = $offset . ',' . $query;
        }
        return sprintf('LIMIT %s ', $query);
    }

    /**
     * Return ORDER part of the query
     * @return string
     */
    protected function getOrderClause(): string
    {
        $sortOrder = $this->getSortColumn() ?: $this->sortOrderDefaultIndex;
        if ($sortOrder === 'username') {
            if (!in_array($sortOrder, $this->getReturnFields(), true)) {
                throw new OutOfRangeException(sprintf("Can't sort by %s if it is not in ReturnFields", $sortOrder));
            }
        }
        return sprintf('ORDER BY %s ', $this->orderFieldsMapping[$sortOrder][$this->isAscendingOrder() ? 'asc' : 'desc']);
    }

    /**
     * @param MailingListTemplateCategories[] $mailingListCategories
     */
    protected function addCategoryIdsWithParents(array $mailingListCategories): void
    {
        foreach ($mailingListCategories as $mailingListCategory) {
            $lotCategoryId = $mailingListCategory->CategoryId;
            if (!in_array($lotCategoryId, $this->lotCategoryIds, true)) {
                $this->lotCategoryIds[] = $lotCategoryId;
            }
        }
        asort($this->lotCategoryIds);
    }

    /**
     * Return sql query for filtering by categories
     * @return string
     */
    protected function getCategoryCondition(): string
    {
        $lotCategoryIdList = implode(",", $this->lotCategoryIds);
        $cond =
            // @formatter:off
            " AND ((ms.category_match = '" . Constants\MySearch::CATEGORY_MATCH_ANY . "'"
                . " AND msc.category_id IN  (" . $lotCategoryIdList . ")"
                . " AND (SELECT COUNT(id) FROM `lot_category` as lc"
                    . " WHERE lc.`id` IN (SELECT category_id FROM `my_search_category`"
                        . " WHERE `my_search_id` = ms.id)"
                    . " AND lc.`parent_id` IS NOT NULL"
                    . " AND lc.`parent_id` = msc.category_id)"
                . " = 0)"
            . " OR (ms.category_match = '" . Constants\MySearch::CATEGORY_MATCH_ALL . "'"
                . " AND"
                    . " ((SELECT COUNT(id)"
                    . " FROM `my_search_category`"
                    . " WHERE `my_search_id` = ms.id"
                    . " AND `category_id` IN  (" . $lotCategoryIdList . "))"
                    . " = "
                    . " (SELECT COUNT(id)"
                    . " FROM `my_search_category`"
                    . " WHERE `my_search_id` = ms.id))"
            . "))";
            // @formatter:on
        return $cond;
    }

    /**
     * Initialize SortFields
     */
    protected function initSortFields(): void
    {
        $availableReturnFields = $this->getResultFieldsMapping();
        $returnFields = $this->getDefaultReturnFields();
        $this->setAvailableReturnFields($availableReturnFields)
            ->setReturnFields($returnFields)
            ->setDefaultSortOrders();
    }

    /**
     * @return bool
     */
    protected function isGroupClause(): bool
    {
        return (bool)strpos($this->getQueryParts()['from'], 'JOIN');
    }
}
