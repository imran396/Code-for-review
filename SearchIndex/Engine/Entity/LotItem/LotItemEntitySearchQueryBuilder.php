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

namespace Sam\SearchIndex\Engine\Entity\LotItem;

use LotItemCustField;
use Sam\Bidder\AuctionBidder\Query\AuctionBidderQueryBuilderHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Core\CustomField\Decimal\CustomDataDecimalPureCalculator;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\SearchIndex\Engine\Entity\EntitySearchQueryNormalizer;
use Sam\SearchIndex\Helper\SearchIndexNormalizationHelperCreateTrait;
use Sam\User\Access\UnknownContextAccessCheckerAwareTrait;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;

/**
 * Class LotItemEntitySearchQueryBuilder
 */
class LotItemEntitySearchQueryBuilder extends CustomizableClass
{
    use AuctionBidderQueryBuilderHelperCreateTrait;
    use AuthIdentityManagerCreateTrait;
    use ConfigRepositoryAwareTrait;
    use DbConnectionTrait;
    use LotCustomFieldLoaderCreateTrait;
    use SearchIndexNormalizationHelperCreateTrait;
    use UnknownContextAccessCheckerAwareTrait;

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
     * @param string $tableAlias lot_item table alias
     * @return string
     */
    public function getWhereClause(string $searchKey, string $tableAlias = 'li'): string
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
     * @param string $tableAlias auction_lot_item table alias
     * @return string
     */
    public function getWhereClauseForLotNumber(string $searchKey, string $tableAlias = 'ali'): string
    {
        if (!empty($tableAlias)) {
            $tableAlias = "`" . $tableAlias . "`.";
        }
        $extractedData = $this->createSearchIndexNormalizationHelper()->extractLotNumbers($searchKey);
        $lotNumbers = $extractedData['lot_numbers'];
        $whereClause = '';
        if (count($lotNumbers) > 0) {
            $lotPrefixSeparator = $this->cfg()->get('core->lot->lotNo->prefixSeparator');
            $lotExtensionSeparator = $this->cfg()->get('core->lot->lotNo->extensionSeparator');
            $whereClauses = [];
            foreach ($lotNumbers as $lotNumber) {
                $likeExpr = "LIKE " . $this->escape('%' . $lotNumber . '%');
                $whereClauses[] = "CONCAT(" . $tableAlias . "`lot_num_prefix`, '" .
                    $lotPrefixSeparator . "', " . $tableAlias . "`lot_num`, '" .
                    $lotExtensionSeparator . "', " . $tableAlias . "`lot_num_ext`) " . $likeExpr;
            }
            $whereClause = '(' . implode(' OR ', $whereClauses) . ')';
        }
        return $whereClause;
    }

    /**
     * Get where clause for searching in item number
     *
     * @param string $searchKey
     * @param string $tableAlias auction_lot_item table alias
     * @return string
     */
    public function getWhereClauseForItemNumber(string $searchKey, string $tableAlias = 'li'): string
    {
        if (!empty($tableAlias)) {
            $tableAlias = "`" . $tableAlias . "`.";
        }
        $itemNumbers = $this->createSearchIndexNormalizationHelper()->extractItemNumbers($searchKey);
        $whereClause = '';
        if (count($itemNumbers) > 0) {
            $whereClauses = [];
            foreach ($itemNumbers as $itemNum) {
                $whereClauses[] = $tableAlias . "`item_num` = " .
                    $this->escape($itemNum);
            }
            $whereClause = '(' . implode(' OR ', $whereClauses) . ')';
        }
        return $whereClause;
    }

    /**
     * Get where clause for searching in categories
     *
     * @param string $searchKey
     * @param string $tableAlias
     * @return string
     */
    public function getWhereClauseForCategory(string $searchKey, string $tableAlias = 'li'): string
    {
        $searchKey = EntitySearchQueryNormalizer::new()->normalize($searchKey);
        if (!empty($tableAlias)) {
            $tableAlias = "`" . $tableAlias . "`.";
        }
        $whereClause = '';
        if ($searchKey !== '') {
            $searchTokens = EntitySearchQueryNormalizer::new()->splitToTokens($searchKey);
            foreach ($searchTokens as $index => $searchToken) {
                $likeExpr = "LIKE " . $this->escape('%' . $searchToken . '%');
                $searchTokens[$index] = "lc.`name` " . $likeExpr;
            }
            $whereClause = "(" . implode(" AND ", $searchTokens) . ")";
            $whereClause = "((SELECT COUNT(1) " .
                "FROM lot_item_category AS lic, lot_category AS lc " .
                "WHERE lic.lot_item_id = " . $tableAlias . "id " .
                "AND lic.lot_category_id = lc.id " .
                "AND " . $whereClause . ") > 0)";
        }
        return $whereClause;
    }

    /**
     * Get where clause for searching in custom fields
     *
     * @param string $searchKey
     * @param string $tableAlias LotItem table alias
     * @param string $auctionLotTableAlias
     * @param int|null $userId
     * @param LotItemCustField[] $lotCustomFields
     * @param array|null $accessRoles user's access rights
     * @param bool $shouldCheckAccess Check access right for each custom field
     * @return string
     */
    public function getWhereClauseForCustomFields(
        string $searchKey,
        string $tableAlias = 'li',
        string $auctionLotTableAlias = 'ali',
        ?int $userId = null,
        ?array $lotCustomFields = null,
        ?array $accessRoles = null,
        bool $shouldCheckAccess = true
    ): string {
        $n = "\n";
        $searchKey = EntitySearchQueryNormalizer::new()->normalize($searchKey);
        if (!empty($tableAlias)) {
            $tableAlias = "`" . $tableAlias . "`.";
        }
        if (!empty($auctionLotTableAlias)) {
            $auctionLotTableAlias = "`" . $auctionLotTableAlias . "`.";
        }
        if ($accessRoles === null) {
            $userId = $this->createAuthIdentityManager()->isAuthorized()
                ? $this->createAuthIdentityManager()->getUserId()
                : null;

            $accessRoles = $this->getUnknownContextAccessChecker()->detectRoles($userId)[0];
        }
        if ($lotCustomFields === null) {
            $lotCustomFields = $this->createLotCustomFieldLoader()->loadByRole($accessRoles);
        }

        $whereClause = '';
        if (
            $searchKey !== ''
            && count($lotCustomFields) > 0
        ) {
            $whereClauseArray = [];
            $searchTokens = EntitySearchQueryNormalizer::new()->splitToTokens($searchKey);

            foreach ($searchTokens as $searchToken) {
                $whereClauseForTokenArray = [];

                foreach ($lotCustomFields as $lotCustomField) {
                    $additionalConds = '';
                    if ($shouldCheckAccess) {
                        $accesses = array_flip($accessRoles);
                        if ((
                                isset($accesses[Constants\Role::CONSIGNOR])
                                && !isset($accesses[Constants\Role::ADMIN])
                            )
                            && $lotCustomField->Access === Constants\Role::CONSIGNOR
                        ) {
                            $additionalConds = " AND " . $tableAlias . "consignor_id = " . $this->escape($userId);
                        }
                        if ((
                                isset($accesses[Constants\Role::BIDDER])
                                && !isset($accesses[Constants\Role::ADMIN])
                            )
                            && $lotCustomField->Access === Constants\Role::BIDDER
                        ) {
                            $additionalConds = " AND (SELECT COUNT(1) FROM auction_bidder AS aub "
                                . "WHERE aub.auction_id = " . $auctionLotTableAlias . "auction_id "
                                . $this->createAuctionBidderQueryBuilderHelper()->makeApprovedBidderWhereClause() . " "
                                . "AND aub.user_id = " . $this->escape($userId) . ")";
                        }
                    }

                    switch ($lotCustomField->Type) {
                        case Constants\CustomField::TYPE_INTEGER:
                            $number = (int)$searchToken;
                            if ($number > 0) {
                                $whereClauseForTokenArray[] = "((SELECT COUNT(1) " .
                                    "FROM lot_item_cust_field AS licf " .
                                    "LEFT JOIN lot_item_cust_data AS licd " .
                                    "ON licf.id = licd.lot_item_cust_field_id " .
                                    "WHERE licd.lot_item_id = " . $tableAlias . "id AND licd.active = true " .
                                    "AND (licd.numeric = " . $this->escape($number) .
                                    " AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) .
                                    ")$additionalConds) > 0) " . $n;
                            }
                            break;
                        case Constants\CustomField::TYPE_DECIMAL:
                            $value = (float)$searchToken;
                            if (Floating::gt($value, 0)) {
                                $precision = (int)$lotCustomField->Parameters;
                                $number = CustomDataDecimalPureCalculator::new()->calcModelValue($value, $precision);
                                $whereClauseForTokenArray[] = "((SELECT COUNT(1) " .
                                    "FROM lot_item_cust_field AS licf " .
                                    "LEFT JOIN lot_item_cust_data AS licd " .
                                    "ON licf.id = licd.lot_item_cust_field_id " .
                                    "WHERE licd.lot_item_id = " . $tableAlias . "id AND licd.active = true " .
                                    "AND (licd.numeric = " . $this->escape($number) .
                                    " AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) .
                                    ")$additionalConds) > 0) " . $n;
                            }
                            break;
                        case Constants\CustomField::TYPE_TEXT:
                        case Constants\CustomField::TYPE_SELECT:
                        case Constants\CustomField::TYPE_FULLTEXT:
                        case Constants\CustomField::TYPE_POSTALCODE:
                            $likeExpr = "LIKE " . $this->escape('%' . $searchToken . '%');
                            $whereClauseForTokenArray[] = "((SELECT COUNT(1) " .
                                "FROM lot_item_cust_field AS licf " .
                                "LEFT JOIN lot_item_cust_data AS licd " .
                                "ON licf.id = licd.lot_item_cust_field_id " .
                                "WHERE licd.lot_item_id = " . $tableAlias . "id AND licd.active = true " .
                                "AND (licd.text " . $likeExpr .
                                " AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) .
                                ")$additionalConds) > 0) " . $n;
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

    /**
     * Get where clause for searching in all lot's custom fields. No checking for access (back end)
     *
     * @param string $searchKey
     * @param string $tableAlias
     * @return string
     */
    public function getWhereClauseForCustomFieldsOptimized(string $searchKey, string $tableAlias = 'li'): string
    {
        $searchKey = EntitySearchQueryNormalizer::new()->normalize($searchKey);
        if (!empty($tableAlias)) {
            $tableAlias = "`" . $tableAlias . "`.";
        }
        $lotCustomFields = $this->createLotCustomFieldLoader()->loadAll();

        $whereClause = '';
        if (
            $searchKey !== ''
            && count($lotCustomFields) > 0
        ) {
            $whereClauseArray = [];
            $searchTokens = EntitySearchQueryNormalizer::new()->splitToTokens($searchKey);

            foreach ($searchTokens as $searchToken) {
                $isInteger = true;
                $isDecimal = true;
                $isString = true;
                $whereClauseForTokenArray = [];

                foreach ($lotCustomFields as $lotCustomField) {
                    switch ($lotCustomField->Type) {
                        case Constants\CustomField::TYPE_INTEGER:
                            if ($isInteger) {
                                $number = (int)$searchToken;
                                $isInteger = false;
                                if ($number > 0) {
                                    $whereClauseForTokenArray[] = "((SELECT COUNT(1) " .
                                        "FROM lot_item_cust_data AS licd " .
                                        "WHERE licd.lot_item_id = " . $tableAlias . "id " .
                                        "AND licd.numeric = " . $this->escape($number) . " ) > 0) ";
                                }
                            }
                            break;
                        case Constants\CustomField::TYPE_DECIMAL:
                            if ($isDecimal) {
                                $value = (float)$searchToken;
                                $isDecimal = false;
                                if (Floating::gt($value, 0)) {
                                    $precision = (int)$lotCustomField->Parameters;
                                    $number = CustomDataDecimalPureCalculator::new()->calcModelValue($value, $precision);
                                    $whereClauseForTokenArray[] = "((SELECT COUNT(1) " .
                                        "FROM lot_item_cust_data AS licd " .
                                        "WHERE licd.lot_item_id = " . $tableAlias . "id " .
                                        "AND licd.numeric = " . $this->escape($number) . " ) > 0) ";
                                }
                            }
                            break;
                        case Constants\CustomField::TYPE_TEXT:
                        case Constants\CustomField::TYPE_SELECT:
                        case Constants\CustomField::TYPE_FULLTEXT:
                        case Constants\CustomField::TYPE_POSTALCODE:
                            if ($isString) {
                                $isString = false;
                                $likeExpr = "LIKE " . $this->escape('%' . $searchToken . '%');
                                $whereClauseForTokenArray[] = "((SELECT COUNT(1) " .
                                    "FROM lot_item_cust_data AS licd " .
                                    "WHERE licd.lot_item_id = " . $tableAlias . "id " .
                                    "AND (licd.text " . $likeExpr . ")) > 0) ";
                            }
                            break;
                        case Constants\CustomField::TYPE_DATE: // Date/Time
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
