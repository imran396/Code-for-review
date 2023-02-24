<?php
/**
 * SAM-6606: Refactoring classes in the \MySearch namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 08, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Search\Query\Build\Helper;

use LotItemCustField;
use Sam\Bidder\AuctionBidder\Query\AuctionBidderQueryBuilderHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Core\CustomField\Decimal\CustomDataDecimalPureCalculator;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\CustomField\Lot\PostalCode\Distance\PostalCodeDistanceQueryBuilderCreateTrait;
use Sam\CustomField\Lot\Validate\LotCustomFieldExistenceCheckerCreateTrait;
use Sam\Lot\Search\Query\Build\Helper\Internal\UserAccessRoleProviderCreateTrait;
use Sam\Lot\Search\Query\LotSearchQuery;
use Sam\Lot\Search\Query\LotSearchQueryCriteria;
use Sam\SharedService\PostalCode\PostalCodeSharedServiceClientAwareTrait;

/**
 * Class LotSearchCustomFieldQueryBuilderHelper
 * @package Sam\Lot\Search\Query\Build\Helper
 */
class LotSearchCustomFieldQueryBuilderHelper extends CustomizableClass
{
    use AuctionBidderQueryBuilderHelperCreateTrait;
    use BaseCustomFieldHelperAwareTrait;
    use DbConnectionTrait;
    use LotCustomFieldExistenceCheckerCreateTrait;
    use LotSearchQueryBuilderHelperCreateTrait;
    use PostalCodeDistanceQueryBuilderCreateTrait;
    use PostalCodeSharedServiceClientAwareTrait;
    use UserAccessRoleProviderCreateTrait;

    /**
     * @var int|null
     */
    protected ?int $postalCustomFieldsQty = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Apply selecting lot item custom field values
     *
     * @param LotSearchQuery $query
     * @param LotItemCustField[] $lotCustomFieldsSelect
     * @return LotSearchQuery
     */
    public function applyCustomFieldSelect(LotSearchQuery $query, array $lotCustomFieldsSelect = []): LotSearchQuery
    {
        foreach ($lotCustomFieldsSelect as $lotCustomField) {
            $alias = $this->getBaseCustomFieldHelper()->makeFieldAlias($lotCustomField->Name);
            $query->addSelect($this->getCustomFieldSelectExpr($lotCustomField) . ' AS ' . $alias);
        }
        return $query;
    }

    /**
     * Return select expression (without alias) for custom field data value select
     * @param LotItemCustField $lotCustomField
     * @return string
     */
    public function getCustomFieldSelectExpr(LotItemCustField $lotCustomField): string
    {
        if ($lotCustomField->isNumeric()) {
            $selectExpr = "(SELECT licd.`numeric` FROM lot_item_cust_data AS licd " .
                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " LIMIT 1)";
        } else {
            $selectExpr = "(SELECT licd.`text` FROM lot_item_cust_data AS licd " .
                "WHERE licd.lot_item_id = li.id AND licd.active = true " .
                "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " LIMIT 1)";
        }
        return $selectExpr;
    }

    /**
     * Apply filtering by custom fields
     * or return them in new array
     *
     * @param LotSearchQuery $query
     * @param LotSearchQueryCriteria $criteria
     * @param array $lotCustomFields
     * @return LotSearchQuery
     */
    public function applyCustomFieldFilter(
        LotSearchQuery $query,
        LotSearchQueryCriteria $criteria,
        array $lotCustomFields = []
    ): LotSearchQuery {
        foreach ($lotCustomFields as $lotCustomField) {
            if (!isset($criteria->lotCustomFieldsValue[$lotCustomField->Id])) {
                continue;
            }

            $mixValues = $criteria->lotCustomFieldsValue[$lotCustomField->Id];
            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                    $minValue = $mixValues['min'];
                    $maxValue = $mixValues['max'];
                    $query = $this->applyNumericLotCustomFieldFilterExpressions(
                        $query,
                        $lotCustomField,
                        $minValue,
                        $maxValue,
                        $criteria->userId
                    );
                    break;

                case Constants\CustomField::TYPE_DECIMAL:
                    $minValue = $mixValues['min'] !== null ? (float)$mixValues['min'] : null;
                    $maxValue = $mixValues['max'] !== null ? (float)$mixValues['max'] : null;
                    $precision = (int)$lotCustomField->Parameters;
                    $min = $minValue ? CustomDataDecimalPureCalculator::new()->calcModelValue($minValue, $precision) : null;
                    $max = $maxValue ? CustomDataDecimalPureCalculator::new()->calcModelValue($maxValue, $precision) : null;
                    $query = $this->applyNumericLotCustomFieldFilterExpressions($query, $lotCustomField, $min, $max, $criteria->userId);
                    break;

                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_FULLTEXT:
                case Constants\CustomField::TYPE_FILE:
                case Constants\CustomField::TYPE_YOUTUBELINK:
                    $query = $this->applyTextLotCustomFieldFilterExpressions($query, $lotCustomField, $mixValues, $criteria->userId);
                    break;

                case Constants\CustomField::TYPE_SELECT:
                    $query = $this->applySelectLotCustomFieldFilterExpressions($query, $lotCustomField, $mixValues, $criteria->userId);
                    break;

                case Constants\CustomField::TYPE_DATE:
                    $minValue = $mixValues['min'];
                    $maxValue = $mixValues['max'];
                    $query = $this->applyDateLotCustomFieldFilterExpressions(
                        $query,
                        $lotCustomField,
                        $minValue,
                        $maxValue,
                        $criteria->userId
                    );
                    break;

                case Constants\CustomField::TYPE_POSTALCODE:
                    $postalCode = $mixValues['pcode'];
                    $radius = $mixValues['radius'];
                    $isOrderByDistance = ($criteria->orderBy === LotSearchQueryCriteria::ORDER_BY_DISTANCE);
                    $query = $this->applyPostalCodeLotCustomFieldFilterExpression(
                        $query,
                        $lotCustomField,
                        $radius,
                        $postalCode,
                        $isOrderByDistance
                    );
            }
        }
        return $query;
    }

    /**
     * @param LotSearchQuery $query
     * @param LotItemCustField $lotCustomField
     * @param int|float|null $minValue
     * @param int|float|null $maxValue
     * @param int|null $userId
     * @return LotSearchQuery
     */
    protected function applyNumericLotCustomFieldFilterExpressions(
        LotSearchQuery $query,
        LotItemCustField $lotCustomField,
        int|float|null $minValue,
        int|float|null $maxValue,
        ?int $userId
    ): LotSearchQuery {
        $roleConditionExpression = $this->makeCustomFieldRoleConditionExpression($lotCustomField, $userId);
        $roleConds = $roleConditionExpression ? ' AND ' . $roleConditionExpression : '';
        $rangeCondition = 'licd.numeric %s %s';
        if (
            $minValue !== null
            && $maxValue !== null
        ) {
            $rangeCondition = 'licd.numeric >= %s AND licd.numeric <= %s';
        }
        $expressionPattern = "((SELECT COUNT(1) " .
            "FROM lot_item_cust_data AS licd " .
            "WHERE licd.lot_item_id = li.id AND licd.active = true " .
            "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
            "AND $rangeCondition{$roleConds}) > 0)";
        $whereExpressions = $this->makeLotCustomFieldFilterRangeExpressions($expressionPattern, $minValue, $maxValue);
        $query->addWhere($whereExpressions);
        return $query;
    }

    /**
     * @param LotSearchQuery $query
     * @param LotItemCustField $lotCustomField
     * @param int|null $minValue
     * @param int|null $maxValue
     * @param int|null $userId
     * @return LotSearchQuery
     */
    protected function applyDateLotCustomFieldFilterExpressions(
        LotSearchQuery $query,
        LotItemCustField $lotCustomField,
        ?int $minValue,
        ?int $maxValue,
        ?int $userId
    ): LotSearchQuery {
        $roleConditionExpression = $this->makeCustomFieldRoleConditionExpression($lotCustomField, $userId);
        $roleConds = $roleConditionExpression ? ' AND ' . $roleConditionExpression : '';
        $expressionPattern = "((SELECT COUNT(1) " .
            "FROM lot_item_cust_data AS licd " .
            "WHERE licd.lot_item_id = li.id AND licd.active = true " .
            "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
            "AND (FROM_UNIXTIME(licd.numeric, '%%Y-%%m-%%d') %s FROM_UNIXTIME(%s, '%%Y-%%m-%%d'))$roleConds) > 0)";
        $whereExpressions = $this->makeLotCustomFieldFilterRangeExpressions($expressionPattern, $minValue, $maxValue, true);
        $query->addWhere($whereExpressions);
        return $query;
    }

    /**
     * @param string $expressionPattern
     * @param int|float|null $minValue
     * @param int|float|null $maxValue
     * @param bool $isDate
     * @return array
     */
    protected function makeLotCustomFieldFilterRangeExpressions(
        string $expressionPattern,
        int|float|null $minValue,
        int|float|null $maxValue,
        bool $isDate = false
    ): array {
        $whereExpressions = [];
        $minValueEscaped = $this->escape($minValue);
        $maxValueEscaped = $this->escape($maxValue);

        if (
            $minValue !== null
            && $maxValue === null
        ) {
            $whereExpressions[] = sprintf($expressionPattern, '>=', $minValueEscaped);
        } elseif (
            $minValue === null
            && $maxValue !== null
        ) {
            $whereExpressions[] = sprintf($expressionPattern, '<=', $maxValueEscaped);
        } elseif (
            $minValue !== null
            && $maxValue !== null
        ) {
            if ($isDate) {
                $whereExpressions[] = sprintf($expressionPattern, '>=', $minValueEscaped);
                $whereExpressions[] = sprintf($expressionPattern, '<=', $maxValueEscaped);
            } else {
                $whereExpressions[] = sprintf($expressionPattern, $minValueEscaped, $maxValueEscaped);
            }
        }
        return $whereExpressions;
    }

    /**
     * @param LotSearchQuery $query
     * @param LotItemCustField $lotCustomField
     * @param string $value
     * @param int|null $userId
     * @return LotSearchQuery
     */
    protected function applyTextLotCustomFieldFilterExpressions(
        LotSearchQuery $query,
        LotItemCustField $lotCustomField,
        string $value,
        ?int $userId
    ): LotSearchQuery {
        $roleConditionExpression = $this->makeCustomFieldRoleConditionExpression($lotCustomField, $userId);
        $roleConds = $roleConditionExpression ? ' AND ' . $roleConditionExpression : '';
        $key = $this->escape('%' . $value . '%');
        $whereExpression = "((SELECT COUNT(1) " .
            "FROM lot_item_cust_data AS licd " .
            "WHERE licd.lot_item_id = li.id AND licd.active = true " .
            "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
            "AND (licd.text LIKE " . $key . ")$roleConds) > 0)";
        $query->addWhere($whereExpression);
        return $query;
    }

    /**
     * @param LotSearchQuery $query
     * @param LotItemCustField $lotCustomField
     * @param string $value
     * @param int|null $userId
     * @return LotSearchQuery
     */
    protected function applySelectLotCustomFieldFilterExpressions(
        LotSearchQuery $query,
        LotItemCustField $lotCustomField,
        string $value,
        ?int $userId
    ): LotSearchQuery {
        $roleConditionExpression = $this->makeCustomFieldRoleConditionExpression($lotCustomField, $userId);
        $roleConds = $roleConditionExpression ? ' AND ' . $roleConditionExpression : '';

        $whereExpression = "((SELECT COUNT(1) " .
            "FROM lot_item_cust_data AS licd " .
            "WHERE licd.lot_item_id = li.id AND licd.active = true " .
            "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
            "AND (licd.text = " . $this->escape($value) . ")$roleConds) > 0)";
        $query->addWhere($whereExpression);
        return $query;
    }

    /**
     * @param LotSearchQuery $query
     * @param LotItemCustField $lotCustomField
     * @param $radius
     * @param $postalCode
     * @param bool $isOrderByDistance
     * @return LotSearchQuery
     */
    protected function applyPostalCodeLotCustomFieldFilterExpression(
        LotSearchQuery $query,
        LotItemCustField $lotCustomField,
        $radius,
        $postalCode,
        bool $isOrderByDistance = false
    ): LotSearchQuery {
        if ($radius !== null && $postalCode !== null) {
            $coordinates = $this->getPostalCodeSharedServiceClient()->findCoordinates($postalCode);
            if ($coordinates) {
                $distanceQueryBuilder = $this->createPostalCodeDistanceQueryBuilder();
                $locationTableAlias = 'lig' . $lotCustomField->Id;
                $latitude = $coordinates['latitude'];
                $longitude = $coordinates['longitude'];
                $join = 'INNER JOIN ' . $distanceQueryBuilder->buildJoinClause('li.id', $locationTableAlias, $lotCustomField->Id);
                $query->addJoin($join);
                $query->addJoinCount($join);

                if ($isOrderByDistance) {
                    $distanceAlias = $this->createLotSearchQueryBuilderHelper()->makeOrderByDistanceAlias($lotCustomField);
                    // we use distance formula in SELECT clause to be possible to ORDER BY distance, filtering by radius in HAVING
                    $query->addSelect(
                        $distanceQueryBuilder->buildSelectExpression(
                            $latitude,
                            $longitude,
                            $locationTableAlias,
                            $distanceAlias
                        )
                    );
                    $query->addHaving(
                        $distanceQueryBuilder->buildHavingClause(
                            $radius,
                            $distanceAlias
                        )
                    );
                    $query->addWhereCount(
                        $distanceQueryBuilder->buildWhereClause(
                            $latitude,
                            $longitude,
                            $radius,
                            $locationTableAlias
                        )
                    );
                } else {    // when there is no ORDERing by distance, query could be optimized a little
                    $query->addWhere(
                        $distanceQueryBuilder->buildWhereClause(
                            $latitude,
                            $longitude,
                            $radius,
                            $locationTableAlias
                        )
                    );
                }

                // we must group results, if there is more than 1 custom field with type of Postal Code
                $postalCodeCustomFieldsQty = $this->detectPostalCustomFieldsQty();
                if ($postalCodeCustomFieldsQty > 1) {
                    $query->addGroup($query->getBaseTableAlias() . '.id ');
                    $query->setCountQueryPattern(
                        "SELECT COUNT(1) AS lot_total " .
                        "FROM `" . $query->getBaseTable() . "` AS " . $query->getBaseTableAlias() . "2 " .
                        "WHERE EXISTS (" .
                        "SELECT " . $query->getBaseTableAlias() . ".id " . "%s" . ")"
                    );
                    $query->addWhereCount($query->getBaseTableAlias() . '2.id = ' . $query->getBaseTableAlias() . '.id ');
                }
            }
        }
        return $query;
    }

    /**
     * @return int
     */
    protected function detectPostalCustomFieldsQty(): int
    {
        if ($this->postalCustomFieldsQty === null) {
            $this->postalCustomFieldsQty = $this->createLotCustomFieldExistenceChecker()
                ->countByType(Constants\CustomField::TYPE_POSTALCODE, true);
        }
        return $this->postalCustomFieldsQty;
    }

    /**
     * @param LotItemCustField $lotCustomField
     * @param int|null $userId
     * @return string
     */
    protected function makeCustomFieldRoleConditionExpression(LotItemCustField $lotCustomField, ?int $userId): string
    {
        $expression = '';
        $userAccessRoles = $this->createUserAccessRoleProvider()->get($userId);
        if (!in_array(Constants\Role::ADMIN, $userAccessRoles, true)) {
            if (
                $lotCustomField->Access === Constants\Role::CONSIGNOR
                && in_array(Constants\Role::CONSIGNOR, $userAccessRoles, true)
            ) {
                $expression = "li.consignor_id = " . $this->escape($userId);
            }
            if (
                $lotCustomField->Access === Constants\Role::BIDDER
                && in_array(Constants\Role::BIDDER, $userAccessRoles, true)
            ) {
                $expression = "(SELECT COUNT(1) FROM auction_bidder AS aub WHERE aub.auction_id = ali.auction_id"
                    . " " . $this->createAuctionBidderQueryBuilderHelper()->makeApprovedBidderWhereClause()
                    . " AND aub.user_id = " . $this->escape($userId) . ")";
            }
        }
        return $expression;
    }
}
