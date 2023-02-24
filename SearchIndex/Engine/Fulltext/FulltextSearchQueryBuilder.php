<?php
/**
 * Query clauses for search in fulltext index
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

use QMySqli5DatabaseResult;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class FulltextSearchQueryBuilder
 * @package Sam\SearchIndex\Engine\Fulltext
 */
class FulltextSearchQueryBuilder extends CustomizableClass
{
    use DbConnectionTrait;

    private const TABLE = 'search_index_fulltext';

    /**
     * @return static
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * Get where clause for searching
     *
     * @param string $searchKey
     * @param int|null $entityType
     * @param bool $isPublic
     * @param int|int[] $accountId << Account Id to search
     * @return string
     */
    public function getWhereClause(string $searchKey, ?int $entityType = null, bool $isPublic = false, int|array $accountId = null): string
    {
        $searchTokens = FulltextSearchQueryNormalizer::new()->splitToTokens($searchKey);
        $whereClause = '';
        if (count($searchTokens) > 0) {
            $searchInContent = $isPublic ? 'sif.public_content' : 'sif.full_content';
            $contentCompareClauseArray = [];
            foreach ($searchTokens as $token) {
                $contentCompareClauseArray[] = $searchInContent . " LIKE " . $this->escape('%' . $token . '%');
            }
            $searchCond = "AND (" . implode(" AND ", $contentCompareClauseArray) . ") ";
            $entityTypeCond = isset($entityType) ? "AND sif.entity_type = " . $this->escape($entityType) . " " : '';
            $accountCond = '1 ';
            if ($accountId) {
                $accountCond = $this->getFilterCondition("sif.account_id", $accountId) . " ";
            }
            $whereClause = "(" . $accountCond . $entityTypeCond . $searchCond . ")";
        }
        return $whereClause;
    }

    /**
     * @param string $column
     * @param mixed $values single value or array
     * @return string
     */
    protected function getFilterCondition(string $column, mixed $values): string
    {
        $values = is_array($values) ? $values : [$values];
        $cond = '';
        if ($values) {
            $conditions = [];
            // search for "null" value
            foreach ($values as $key => $value) {
                if ($value === null) {
                    $conditions[] = $column . ' IS NULL';
                    unset($values[$key]);
                }
            }
            if ($values) {
                foreach ($values as $i => $value) {
                    $values[$i] = $this->escape($value);
                }
                $list = implode(',', $values);
                $template = count($values) === 1 ? "%s = %s" : "%s IN (%s)";
                $conditions[] = sprintf($template, $column, $list);
            }
            $cond = implode(' OR ', $conditions);
            $cond = count($conditions) > 1 ? '(' . $cond . ')' : $cond;
        }
        return $cond;
    }

    /**
     * Get clause for joining with search index table
     *
     * @param int $entityType
     * @param string $joinColumn
     * @param int|array|null $accountId
     * @param string|null $accountColumn
     * @return string
     */
    public function getJoinClause(int $entityType, string $joinColumn, int|array|null $accountId = null, ?string $accountColumn = null): string
    {
        $joinClause = "`" . self::TABLE . "` AS sif ON " .
            "sif.entity_type = " . $this->escape($entityType) . " " .
            ($accountColumn ? "AND sif.account_id = " . $accountColumn . " " : '') .
            "AND sif.entity_id = " . $joinColumn . " ";
        if ($accountId) {
            $accountCond = $this->getFilterCondition("sif.account_id", $accountId);
            $joinClause .= "AND " . $accountCond . " ";
        }
        return $joinClause;
    }

    /**
     * Find $searchKey. For testing purposes
     *
     * @param string $searchKey
     * @param int|null $entityType
     * @param bool $isPublic
     * @param int|int[]|null $accountId << Account Id to search
     * @return array
     */
    public function find(string $searchKey, ?int $entityType = null, bool $isPublic = false, int|array $accountId = null): array
    {
        $result = [];
        $searchKey = FulltextSearchQueryNormalizer::new()->normalize($searchKey);
        if ($searchKey !== '') {
            // @formatter:off
             $sql =
                 "SELECT " .
                     "sif.entity_type AS entity_type, " .
                     "sif.entity_id AS entity_id " .
                 "FROM `" . self::TABLE ."` AS si " .
                 "WHERE " .
                     $this->getWhereClause($searchKey, $entityType, $isPublic, $accountId);
            // @formatter:on
            $dbResult = $this->query($sql);
            while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
                $result[] = $row;
            }
        }
        return $result;
    }
}
