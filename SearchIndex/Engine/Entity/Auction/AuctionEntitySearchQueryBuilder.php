<?php
/**
 * Query clauses for search not using data indexing
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

namespace Sam\SearchIndex\Engine\Entity\Auction;

use Sam\Core\Constants;
use Sam\Core\CustomField\Decimal\CustomDataDecimalPureCalculator;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Load\AuctionCustomFieldLoaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\SearchIndex\Engine\Entity\EntitySearchQueryNormalizer;

/**
 * Class LotItemEntitySearchQueryBuilder
 */
class AuctionEntitySearchQueryBuilder extends CustomizableClass
{
    use AuctionCustomFieldLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use DbConnectionTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * Get where clause for searching in lot item data
     *
     * @param string $searchKey
     * @param string $tableAlias auction table alias
     * @return string
     */
    public function getWhereClause(string $searchKey, string $tableAlias = 'a'): string
    {
        if (!empty($tableAlias)) {
            $tableAlias = "`" . $tableAlias . "`.";
        }
        $searchTokens = EntitySearchQueryNormalizer::new()->splitToTokens($searchKey);
        $whereClause = '';
        if (count($searchTokens) > 0) {
            $nameCompareClauses = [];
            $descriptionCompareClauses = [];
            foreach ($searchTokens as $searchToken) {
                $likeExpr = "LIKE " . $this->escape('%' . $searchToken . '%');
                $nameCompareClauses[] = $tableAlias . "`name` " . $likeExpr;
                $descriptionCompareClauses[] = $tableAlias . "`description` " . $likeExpr;
            }
            $nameCompareClause = implode(" AND ", $nameCompareClauses);
            $descriptionCompareClause = implode(" AND ", $descriptionCompareClauses);
            $whereClause = "((" . $nameCompareClause . ") OR (" . $descriptionCompareClause . "))";
        }
        return $whereClause;
    }

    /**
     * Get where clause for searching in lot number/extension
     *
     * @param string $searchKey
     * @param string $tableAlias auction_auction table alias
     * @return string
     */
//    public function getWhereClauseForSaleNo($searchKey, $tableAlias = 'a')
//    {
//        if (!empty($tableAlias)) {
//            $tableAlias = "`" . $tableAlias . "`.";
//        }
//        $extractedData = Search_Index_Manager::getInstance()->extractSaleNumbers($searchKey);
//        $lotNumbers = $extractedData['sale_numbers'];
//        $whereClause = '';
//        if (count($lotNumbers) > 0) {
//            $saleExtensionSeparator = cfg()->core->auction->saleNo->extensionSeparator;
//            $whereClauses = [];
//            foreach ($lotNumbers as $lotNumber) {
//                $likeExpr = "LIKE " . $this->db->SqlVariable('%' . $lotNumber . '%');
//                $whereClauses[] = "CONCAT(" . $tableAlias . "`sale_no`, '" .
//                    $saleExtensionSeparator . "', " . $tableAlias . "`sale_no_ext`) " . $likeExpr;
//            }
//            $whereClause = '(' . implode(' OR ', $whereClauses) . ')';
//        }
//        return $whereClause;
//    }

    /**
     * Get where clause for searching in all lot's custom fields. No checking for access (back end)
     *
     * @param string $searchKey
     * @param string $tableAlias
     * @return string
     */
    public function getWhereClauseForCustomFieldsOptimized(string $searchKey, string $tableAlias = 'a'): string
    {
        $searchKey = EntitySearchQueryNormalizer::new()->normalize($searchKey);
        if (!empty($tableAlias)) {
            $tableAlias = "`" . $tableAlias . "`.";
        }
        /**
         * Load auction custom fields for "Admin List", because auction search presents in admin site only.
         */
        $auctionCustomFields = $this->getAuctionCustomFieldLoader()->loadForAdminList();

        $whereClause = '';
        if (
            $searchKey !== ''
            && count($auctionCustomFields) > 0
        ) {
            $whereClauseArray = [];
            $searchTokens = EntitySearchQueryNormalizer::new()->splitToTokens($searchKey);

            foreach ($searchTokens as $searchToken) {
                $isInteger = true;
                $isDecimal = true;
                $isString = true;
                $whereClauseForTokenArray = [];

                foreach ($auctionCustomFields as $auctionCustomField) {
                    switch ($auctionCustomField->Type) {
                        case Constants\CustomField::TYPE_INTEGER:
                            if ($isInteger) {
                                $number = (int)$searchToken;
                                $isInteger = false;
                                if ($number > 0) {
                                    $whereClauseForTokenArray[] = "((SELECT COUNT(1) " .
                                        "FROM auction_cust_data AS acd " .
                                        "WHERE acd.auction_id = " . $tableAlias . "id " .
                                        "AND acd.numeric = " . $this->escape($number) . " ) > 0) ";
                                }
                            }
                            break;
                        case Constants\CustomField::TYPE_DECIMAL:
                            if ($isDecimal) {
                                $value = (float)$searchToken;
                                $isDecimal = false;
                                if (Floating::gt($value, 0)) {
                                    $precision = (int)$auctionCustomField->Parameters;
                                    $number = CustomDataDecimalPureCalculator::new()->calcModelValue($value, $precision);
                                    $whereClauseForTokenArray[] = "((SELECT COUNT(1) " .
                                        "FROM auction_cust_data AS acd " .
                                        "WHERE acd.auction_id = " . $tableAlias . "id " .
                                        "AND acd.numeric = " . $this->escape($number) . " ) > 0) ";
                                }
                            }
                            break;
                        case Constants\CustomField::TYPE_TEXT:
                        case Constants\CustomField::TYPE_SELECT:
                        case Constants\CustomField::TYPE_FULLTEXT:
                            if ($isString) {
                                $isString = false;
                                $likeExpr = "LIKE " . $this->escape('%' . $searchToken . '%');
                                $whereClauseForTokenArray[] = "((SELECT COUNT(1) " .
                                    "FROM auction_cust_data AS acd " .
                                    "WHERE acd.auction_id = " . $tableAlias . "id " .
                                    "AND (acd.text " . $likeExpr . ")) > 0) ";
                            }
                            break;
                        case Constants\CustomField::TYPE_DATE: // Date/Time
                        case Constants\CustomField::TYPE_FILE:
                            break;
                    }
                }
                if ($whereClauseForTokenArray) {
                    $whereClauseArray[] = "(" . implode(" OR ", $whereClauseForTokenArray) . ")";
                }
            }
            if ($whereClauseArray) {
                $whereClause = "(" . implode(" AND ", $whereClauseArray) . ")";
            }
        }
        return $whereClause;
    }
}
