<?php
/**
 * Class implements functionality for building user list query
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: ListQuery.php 20038 2014-12-17 12:23:28Z SWB\nkovalchick $
 * @since           Nov 08, 2012
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Load\UserList;

use LotItemCustField;
use QMySqliDatabaseException;
use RuntimeException;
use Sam\Core\Constants;
use Sam\Core\CustomField\Decimal\CustomDataDecimalPureCalculator;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Settings\SettingsManager;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\User\Load\UserList\Internal\ResultSetFieldMapping;
use UserCustField;

/**
 * Class UserListDatasource
 */
class UserListDatasource extends CustomizableClass
{
    use AccountAwareTrait;
    use CurrentDateTrait;
    use DbConnectionTrait;
    use FilterDatePeriodAwareTrait;
    use LimitInfoAwareTrait;
    use SystemAccountAwareTrait;

    public const JOIN_ACCOUNT = Constants\Db::TBL_ACCOUNT;
    public const JOIN_ADMIN = Constants\Db::TBL_ADMIN;
    public const JOIN_BIDDER = Constants\Db::TBL_BIDDER;
    public const JOIN_CONSIGNOR = Constants\Db::TBL_CONSIGNOR;
    public const JOIN_CONSIGNOR_COMMISSION = 'consignor_commission';
    public const JOIN_CONSIGNOR_SOLD_FEE = 'consignor_sold_fee';
    public const JOIN_CONSIGNOR_UNSOLD_FEE = 'consignor_unsold_fee';
    public const JOIN_USER_ACCOUNT = Constants\Db::TBL_USER_ACCOUNT;
    public const JOIN_USER_AUTHENTICATION = Constants\Db::TBL_USER_AUTHENTICATION;
    public const JOIN_USER_BILLING = Constants\Db::TBL_USER_BILLING;
    public const JOIN_USER_CONSIGNOR_COMMISSION_FEE = Constants\Db::TBL_USER_CONSIGNOR_COMMISSION_FEE;
    public const JOIN_USER_INFO = Constants\Db::TBL_USER_INFO;
    public const JOIN_USER_SHIPPING = Constants\Db::TBL_USER_SHIPPING;
    public const JOIN_USER_ACCOUNT_BY_SYSTEM_ACCOUNT = 'user_account_by_system_account';

    protected bool $isAdmins = false;
    protected bool $isAnyStatusResellers = false;
    protected bool $isBidders = false;
    protected bool $isConsignors = false;
    protected bool $isNone = false;
    protected bool $isPendingResellerApproval = false;
    protected bool $isSortOrderAsc = true;
    protected ?string $customer = null;
    protected ?string $company = null;
    protected ?string $postalCode = null;
    protected ?string $email = null;
    protected ?int $createdBy = null;
    protected array $customFieldFilters = [];
    protected ?string $searchKey = null;
    protected array $userStatusIds = [];
    protected ?string $sortOrder = null;
    protected ?string $sortInfo = null;
    protected array $conds = [];
    protected array $resultJoins = [];
    protected array $countJoins = [];
    protected string $select = '';
    protected array $resultSetFields = [];
    protected string $countSelect = 'SELECT COUNT(1) AS %s %s ';
    protected string $from = ' FROM user u';
    protected string $whereClause = '';
    protected string $countWhere = '';
    protected string $joinClause = '';
    protected string $countJoinClause = '';
    protected string $orderClause = '';
    protected string $limitClause = '';
    protected string $nl = "\n";
    protected ?int $shareInfo = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(array $fields): static
    {
        $this->resultSetFields = $fields;
        $this->shareInfo = (int)SettingsManager::new()->getForMain(Constants\Setting::SHARE_USER_INFO);
        return $this;
    }

    /**
     * Execute result query and return array of result values
     *
     * @return array
     */
    public function getResults(): array
    {
        $resultQuery = $this->getResultQuery();
        try {
            $this->query($resultQuery);
        } catch (QMySqliDatabaseException $e) {
            log_error($e->getMessage());
            throw new RuntimeException($e->getMessage());
        }
        $rows = $this->fetchAllAssoc();
        return $rows;
    }

    /**
     * Execute count query and return number of users
     *
     * @return int
     */
    public function getCount(): int
    {
        $countQuery = $this->getCountQuery('total');
        try {
            $this->query($countQuery);
        } catch (QMySqliDatabaseException $e) {
            log_error($e->getMessage());
            throw new RuntimeException($e->getMessage());
        }
        $rows = $this->fetchAssoc();
        return (int)$rows['total'];
    }

    /**
     * Add filtering by custom field options
     *
     * @param UserCustField $lotCustomField
     * @param mixed $mixValues array('min' => min value, 'max' => max value) for types of Integer, Decimal, Date
     *                                      or string for types of Text and Select
     *                                      or integer (0, 1) for Checkbox
     */
    public function addCustomFieldFilter(UserCustField $lotCustomField, mixed $mixValues = []): void
    {
        $this->customFieldFilters[] = ['CustFld' => $lotCustomField, 'Values' => $mixValues];
    }

    /**
     * Return query of report result
     *
     * @return string
     */
    public function getResultQuery(): string
    {
        $this->buildQueryParts();
        $resultQuery = $this->select . $this->from . $this->joinClause . $this->whereClause . $this->orderClause . $this->limitClause;
        return $resultQuery;
    }

    /**
     * Return query for counting
     *
     * @param string $alias count result alias
     * @return string
     */
    public function getCountQuery(string $alias): string
    {
        $this->buildQueryParts();
        $countQuery = sprintf($this->countSelect, $alias, $this->from . $this->countJoinClause . $this->countWhere);
        return $countQuery;
    }

    /**
     * Assemble query parts using current options. Save them in $this properties ($_strSelect, $_where, $_strJoin, $_strCountJoin, $_strOrder, $_strLimit)
     */
    protected function buildQueryParts(): void
    {
        $n = $this->nl;
        if (empty($this->select)) {
            $this->buildSelect();
        }
        $this->buildWhere();
        $this->joinClause = $this->buildJoin();
        $this->countJoinClause = $this->buildCountJoin();

        $this->countWhere = $this->whereClause;

        if (empty($this->orderClause)) {
            $this->buildOrderBy();
        }

        if ($this->getLimit() > 0) {
            $limitClause = $this->getLimit();
            if ($this->getOffset() > 0) {
                $limitClause = $this->getOffset() . ', ' . $limitClause;
            }
            $this->limitClause = $n . ' LIMIT ' . $limitClause;
        }
    }

    /**
     * Build and return select clause which used to get result fields.
     * It is saved in $this->_strSelect
     *
     * @return string
     */
    protected function buildSelect(): string
    {
        $resultSetMapping = ResultSetFieldMapping::new();
        $selectFields = [];
        $joins = [];
        foreach ($this->resultSetFields as $fieldName) {
            $fieldMapping = $resultSetMapping->getFieldMapping($fieldName);
            $selectFields[] = $fieldMapping['select'] . ' AS ' . $fieldName;
            $joins[] = $fieldMapping['join'] ?? [];
        }
        $this->resultJoins = array_merge($this->resultJoins, ...$joins);

        $this->select = 'SELECT ' . implode(', ', $selectFields);
        return $this->select;
    }

    /**
     * Build and return where clause, which is used in result and count queries.
     * It is saved in $this->_where
     *
     * @return string
     */
    protected function buildWhere(): string
    {
        $n = $this->nl;
        $this->conds = [];

        if ($this->getAccountId()) {
            $condition = '(u.account_id=' . $this->escape($this->getAccountId());
            if (
                $this->isPortalSystemAccount()
                && $this->shareInfo !== Constants\ShareUserInfo::NONE
            ) { // View or Edit
                $condition .= ' OR ua.account_id=' . $this->escape($this->getAccountId());
                $this->countJoins[] = self::JOIN_USER_ACCOUNT;
            } else {
                $condition .= ' AND 1';
            }
            $condition .= ' )';
            $this->conds[] = $condition;
        }

        if ($this->getUserStatusIds()) {
            $statusIdList = implode(',', $this->getUserStatusIds());
            $this->conds[] = 'u.user_status_id IN (' . $statusIdList . ')';
        }

        if ($this->getSearchKey()) {
            $searchKey = $this->escape('%' . $this->getSearchKey() . '%');
            $this->conds[] = "(0 OR u.username LIKE {$searchKey} " .
                //"OR u.customer_no LIKE {$strKey} " .
                "OR u.email LIKE {$searchKey} " .
                "OR ui.first_name LIKE {$searchKey} " .
                "OR ui.last_name LIKE {$searchKey} " .
                //"OR ui.company_name LIKE {$strKey} " .
                "OR CONCAT(ui.first_name, ' ', ui.last_name) LIKE {$searchKey} " .
                "OR ui.referrer_host LIKE {$searchKey} " .
                "OR ub.email LIKE {$searchKey} " .
                ")";
            $this->countJoins[] = self::JOIN_USER_INFO;
            $this->countJoins[] = self::JOIN_USER_BILLING;
        }

        if ($this->getCustomer()) {
            $customer = $this->escape('%' . $this->getCustomer() . '%');
            $this->conds[] = " u.customer_no LIKE {$customer} ";
        }
        if ($this->getCompany()) {
            $company = $this->escape('%' . $this->getCompany() . '%');
            $this->conds[] = " ui.company_name LIKE {$company} ";
            $this->countJoins[] = self::JOIN_USER_INFO;
        }
        if ($this->getPostalCode()) {
            $postalCode = $this->escape('%' . $this->getPostalCode() . '%');
            $this->conds[] = " ub.zip LIKE {$postalCode} ";
            $this->countJoins[] = self::JOIN_USER_BILLING;
        }
        if ($this->getEmail()) {
            $email = $this->escape('%' . $this->getEmail() . '%');
            $this->conds[] = " (u.email LIKE {$email} OR ub.email LIKE {$email}) ";
            $this->countJoins[] = self::JOIN_USER_BILLING;
        }
        if ($this->isFilterDatePeriod()) {
            $this->conds[] = 'u.created_on >= ' . $this->escape($this->getFilterStartDateUtcIso());
            $this->conds[] = 'u.created_on <=' . $this->escape($this->getFilterEndDateUtcIso());
        }
        if ($this->isNone()) {
            $this->conds[] = "(SELECT COUNT(user_id) FROM admin WHERE user_id = u.id) = 0";
            $this->conds[] = "(SELECT COUNT(user_id) FROM bidder WHERE user_id = u.id) = 0";
            $this->conds[] = "(SELECT COUNT(user_id) FROM consignor WHERE user_id = u.id) = 0";
        } else {
            if ($this->isAdmins()) {
                $this->conds[] = "(SELECT COUNT(1) FROM admin WHERE user_id = u.id) > 0";
            }

            if ($this->isBidders()) {
                $this->conds[] = "(SELECT COUNT(1) FROM bidder WHERE user_id = u.id) > 0";
            }

            if ($this->isConsignors()) {
                $this->conds[] = "(SELECT COUNT(1) FROM consignor WHERE user_id = u.id) > 0";
            }
        }

        if ($this->getCreatedBy()) {
            $this->conds[] = 'u.created_by=' . $this->escape($this->getCreatedBy());
        }

        // Filtering by custom fields
        $this->buildCustomFieldConditions();

        if ($this->isPendingResellerApproval() && $this->isAnyStatusResellers()) {
            $this->conds[] = 'CHAR_LENGTH(ui.reseller_cert_file) > 0';
            $this->countJoins[] = self::JOIN_USER_INFO;
        } elseif ($this->isPendingResellerApproval()) {
            $currentDateSys = $this->getCurrentDateSys();
            $currentDateSysIso = $this->escape($currentDateSys->format('Y-m-d'));
            $this->conds[] = 'CHAR_LENGTH(ui.reseller_cert_file) > 0'
                . ' AND ui.reseller_cert_expiration > ' . $currentDateSysIso
                . ' AND IFNULL(ui.reseller_cert_approved, false) = false';
            $this->countJoins[] = self::JOIN_USER_INFO;
        } elseif ($this->isAnyStatusResellers()) {
            $currentDateSys = $this->getCurrentDateSys();
            $currentDateSysIso = $this->escape($currentDateSys->format('Y-m-d'));
            $this->conds[] = 'CHAR_LENGTH(ui.reseller_cert_file) > 0'
                . ' AND (ui.reseller_cert_expiration < ' . $currentDateSysIso
                . ' OR IFNULL(ui.reseller_cert_approved, false) = true)';
            $this->countJoins[] = self::JOIN_USER_INFO;
        }

        if (!empty($this->conds)) {
            $this->whereClause = $n . ' WHERE ' . implode($n . ' AND ', $this->conds);
        }

        return $this->whereClause;
    }

    protected function buildJoin(): string
    {
        $joins = array_merge($this->resultJoins, $this->countJoins);
        $joins = array_unique($joins);
        $joinClause = '';
        foreach ($joins as $table) {
            $joinClause .= ' ' . $this->makeJoinExpression($table);
        }
        return $joinClause;
    }

    protected function buildCountJoin(): string
    {
        $joins = array_unique($this->countJoins);
        $joinClause = '';
        foreach ($joins as $table) {
            $joinClause .= ' ' . $this->makeJoinExpression($table);
        }
        return $joinClause;
    }

    protected function makeJoinExpression(string $table): string
    {
        return match ($table) {
            self::JOIN_USER_AUTHENTICATION => 'LEFT JOIN user_authentication AS uau ON uau.user_id = u.id',
            self::JOIN_USER_INFO => 'LEFT JOIN user_info AS ui ON ui.user_id = u.id',
            self::JOIN_USER_BILLING => 'LEFT JOIN user_billing AS ub ON ub.user_id = u.id',
            self::JOIN_USER_SHIPPING => 'LEFT JOIN user_shipping us ON us.user_id = u.id',
            self::JOIN_ACCOUNT => 'LEFT JOIN account AS acc ON u.account_id = acc.id',
            self::JOIN_USER_ACCOUNT => 'LEFT JOIN user_account ua ON ua.user_id = u.id AND ua.account_id = ' . ($this->getAccountId() ?? $this->getSystemAccountId()),
            self::JOIN_USER_ACCOUNT_BY_SYSTEM_ACCOUNT => 'LEFT JOIN user_account ua_by_sysacc ON ua_by_sysacc.user_id = u.id AND ua_by_sysacc.account_id = ' . $this->getSystemAccountId(),
            self::JOIN_ADMIN => 'LEFT JOIN `admin` ad ON ad.user_id = u.id',
            self::JOIN_CONSIGNOR => 'LEFT JOIN consignor cons ON cons.user_id = u.id',
            self::JOIN_BIDDER => 'LEFT JOIN bidder b ON b.user_id = u.id',
            self::JOIN_USER_CONSIGNOR_COMMISSION_FEE => 'LEFT JOIN user_consignor_commission_fee uccf ON uccf.user_id = u.id AND uccf.account_id = '
                . ($this->getAccountId() ?? $this->getSystemAccountId()),
            self::JOIN_CONSIGNOR_COMMISSION => 'LEFT JOIN consignor_commission_fee ccfc ON ccfc.id = uccf.commission_id AND ccfc.active',
            self::JOIN_CONSIGNOR_SOLD_FEE => 'LEFT JOIN consignor_commission_fee ccfsf ON ccfsf.id = uccf.sold_fee_id AND ccfsf.active',
            self::JOIN_CONSIGNOR_UNSOLD_FEE => 'LEFT JOIN consignor_commission_fee ccfuf ON ccfuf.id = uccf.unsold_fee_id AND ccfuf.active',
            default => throw new \InvalidArgumentException("Unknown JOIN table '{$table}'"),
        };
    }

    /**
     * Build where and join clauses for custom fields selection
     */
    protected function buildCustomFieldConditions(): void
    {
        foreach ($this->customFieldFilters as $customFieldFilter) {
            /** @var LotItemCustField $lotCustomField */
            $lotCustomField = $customFieldFilter['CustFld'];
            $values = $customFieldFilter['Values'];

            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                    $min = $values['min'];
                    $max = $values['max'];
                    if ($min !== null && $max === null) {
                        $this->conds[] = "((SELECT COUNT(1) " .
                            "FROM user_cust_data AS ucd " .
                            "WHERE ucd.user_id = u.id AND ucd.active = true " .
                            "AND ucd.user_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                            "AND ucd.numeric >= " . $this->escape($min) . ") > 0)";
                    } elseif ($min !== null && $max !== null) {
                        $this->conds[] = "((SELECT COUNT(1) " .
                            "FROM user_cust_data AS ucd " .
                            "WHERE ucd.user_id = u.id AND ucd.active = true " .
                            "AND ucd.user_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                            "AND ucd.numeric >= " . $this->escape($min) . ") > 0)";
                        $this->conds[] = "((SELECT COUNT(1) " .
                            "FROM user_cust_data AS ucd " .
                            "WHERE ucd.user_id = u.id AND ucd.active = true " .
                            "AND ucd.user_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                            "AND ucd.numeric <= " . $this->escape($max) . ") > 0)";
                    } elseif ($min === null && $max !== null) {
                        $this->conds[] = "((SELECT COUNT(1) " .
                            "FROM user_cust_data AS ucd " .
                            "WHERE ucd.user_id = u.id AND ucd.active = true " .
                            "AND ucd.user_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                            "AND ucd.numeric <= " . $this->escape($max) . ") > 0)";
                    }
                    break;

                case Constants\CustomField::TYPE_DECIMAL:
                    $min = $values['min'];
                    $max = $values['max'];
                    $precision = (int)$lotCustomField->Parameters;
                    $dbMin = CustomDataDecimalPureCalculator::new()->calcModelValue((float)$min, $precision);
                    $dbMax = CustomDataDecimalPureCalculator::new()->calcModelValue((float)$max, $precision);
                    if ($min !== null && $max === null) {
                        $this->conds[] = "((SELECT COUNT(1) " .
                            "FROM user_cust_data AS ucd " .
                            "WHERE ucd.user_id = u.id AND ucd.active = true " .
                            "AND ucd.user_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                            "AND ucd.numeric >= " . $this->escape($dbMin) . ") > 0)";
                    } elseif ($min !== null && $max !== null) {
                        $this->conds[] = "((SELECT COUNT(1) " .
                            "FROM user_cust_data AS ucd " .
                            "WHERE ucd.user_id = u.id AND ucd.active = true " .
                            "AND ucd.user_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                            "AND ucd.numeric >= " . $this->escape($dbMin) . ") > 0)";
                        $this->conds[] = "((SELECT COUNT(1) " .
                            "FROM user_cust_data AS ucd " .
                            "WHERE ucd.user_id = u.id AND ucd.active = true " .
                            "AND ucd.user_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                            "AND ucd.numeric <= " . $this->escape($dbMax) . ") > 0)";
                    } elseif ($min === null && $max !== null) {
                        $this->conds[] = "((SELECT COUNT(1) " .
                            "FROM user_cust_data AS ucd " .
                            "WHERE ucd.user_id = u.id AND ucd.active = true " .
                            "AND ucd.user_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                            "AND (ucd.numeric <= " . $this->escape($dbMax) . ")) > 0)";
                    }
                    break;

                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_FULLTEXT:
                case Constants\CustomField::TYPE_PASSWORD:
                    $searchKey = $this->escape('%' . $values . '%');
                    $this->conds[] = "((SELECT COUNT(1) " .
                        "FROM user_cust_data AS ucd " .
                        "WHERE ucd.user_id = u.id AND ucd.active = true " .
                        "AND ucd.user_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                        "AND ucd.text LIKE " . $searchKey . ") > 0)";
                    break;

                case Constants\CustomField::TYPE_SELECT:
                    $this->conds[] = "((SELECT COUNT(1) " .
                        "FROM user_cust_data AS ucd " .
                        "WHERE ucd.user_id = u.id AND ucd.active = true " .
                        "AND ucd.user_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                        "AND ucd.text = " . $this->escape($values) . ") > 0)";
                    break;

                case Constants\CustomField::TYPE_DATE:
                    $min = $values['min'];
                    $max = $values['max'];
                    if ($min !== null && $max === null) {
                        $this->conds[] = "((SELECT COUNT(1) " .
                            "FROM user_cust_data AS ucd " .
                            "WHERE ucd.user_id = u.id AND ucd.active = true " .
                            "AND ucd.user_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                            "AND FROM_UNIXTIME(ucd.numeric, '%Y-%m-%d') >= FROM_UNIXTIME(" . $this->escape($min) . ", '%Y-%m-%d')) > 0)";
                    } elseif ($min !== null && $max !== null) {
                        $this->conds[] = "((SELECT COUNT(1) " .
                            "FROM user_cust_data AS ucd " .
                            "WHERE ucd.user_id = u.id AND ucd.active = true " .
                            "AND ucd.user_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                            "AND FROM_UNIXTIME(ucd.numeric, '%Y-%m-%d') >= FROM_UNIXTIME(" . $this->escape($min) . ", '%Y-%m-%d')) > 0)";
                        $this->conds[] = "((SELECT COUNT(1) " .
                            "FROM user_cust_data AS ucd " .
                            "WHERE ucd.user_id = u.id AND ucd.active = true " .
                            "AND ucd.user_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                            "AND FROM_UNIXTIME(ucd.numeric, '%Y-%m-%d') <= FROM_UNIXTIME(" . $this->escape($max) . ", '%Y-%m-%d')) > 0)";
                    } elseif ($min === null && $max !== null) {
                        $this->conds[] = "((SELECT COUNT(1) " .
                            "FROM user_cust_data AS ucd " .
                            "WHERE ucd.user_id = u.id AND ucd.active = true " .
                            "AND ucd.user_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                            "AND FROM_UNIXTIME(ucd.numeric, '%Y-%m-%d') <= FROM_UNIXTIME(" . $this->escape($max) . ", '%Y-%m-%d')) > 0)";
                    }
                    break;

                case Constants\CustomField::TYPE_CHECKBOX:
                    $this->conds[] = "((SELECT COUNT(1) " .
                        "FROM user_cust_data AS ucd " .
                        "WHERE ucd.user_id = u.id AND ucd.active = true " .
                        "AND ucd.user_cust_field_id = " . $this->escape($lotCustomField->Id) . " " .
                        "AND ucd.numeric = " . $this->escape($values) . ") > 0)";
                    break;
            }
        }
    }

    /**
     * Build and return order by clause
     * It is saved in $this->_strOrder
     *
     * @return string
     */
    protected function buildOrderBy(): string
    {
        if ($this->getSortInfo()) {
            $sortInfo = $this->getSortInfo();
        } elseif ($this->getSortOrder()) {
            $sortInfo = $this->getSortOrder() . ($this->isSortOrderAsc() ? ' ASC' : ' DESC');
        } else {
            $sortInfo = 'u.username';
        }
        $this->orderClause = $this->nl . ' ORDER BY ' . $sortInfo;
        return $this->orderClause;
    }

    /**
     * Return value of isAdmins property
     * @return bool
     */
    public function isAdmins(): bool
    {
        return $this->isAdmins;
    }

    /**
     * Set isAdmins property value and normalize boolean value
     * @param bool $isAdmins
     * @return static
     */
    public function enableAdmins(bool $isAdmins): static
    {
        $this->isAdmins = $isAdmins;
        return $this;
    }

    /**
     * Return value of isAnyStatusResellers property
     * @return bool
     */
    public function isAnyStatusResellers(): bool
    {
        return $this->isAnyStatusResellers;
    }

    /**
     * Set isAnyStatusResellers property value and normalize boolean value
     * @param bool $isAnyStatusResellers
     * @return static
     */
    public function enableAnyStatusResellers(bool $isAnyStatusResellers): static
    {
        $this->isAnyStatusResellers = $isAnyStatusResellers;
        return $this;
    }

    /**
     * Return value of isBidders property
     * @return bool
     */
    public function isBidders(): bool
    {
        return $this->isBidders;
    }

    /**
     * Set isBidders property value and normalize boolean value
     * @param bool $isBidders
     * @return static
     */
    public function enableBidders(bool $isBidders): static
    {
        $this->isBidders = $isBidders;
        return $this;
    }

    /**
     * Return value of isConsignors property
     * @return bool
     */
    public function isConsignors(): bool
    {
        return $this->isConsignors;
    }

    /**
     * Set isConsignors property value and normalize boolean value
     * @param bool $isConsignors
     * @return static
     */
    public function enableConsignors(bool $isConsignors): static
    {
        $this->isConsignors = $isConsignors;
        return $this;
    }

    /**
     * Return value of isNone property
     * @return bool
     */
    public function isNone(): bool
    {
        return $this->isNone;
    }

    /**
     * Set isNone property value and normalize boolean value
     * @param bool $isNone
     * @return static
     */
    public function enableNone(bool $isNone): static
    {
        $this->isNone = $isNone;
        return $this;
    }

    /**
     * Return value of isPendingResellerApproval property
     * @return bool
     */
    public function isPendingResellerApproval(): bool
    {
        return $this->isPendingResellerApproval;
    }

    /**
     * Set isPendingResellerApproval property value and normalize boolean value
     * @param bool $isPendingResellerApproval
     * @return static
     */
    public function enablePendingResellerApproval(bool $isPendingResellerApproval): static
    {
        $this->isPendingResellerApproval = $isPendingResellerApproval;
        return $this;
    }

    /**
     * Return value of Customer property
     * @return string|null
     */
    public function getCustomer(): ?string
    {
        return $this->customer;
    }

    /**
     * Set Customer property value
     * @param string $customer
     * @return static
     */
    public function setCustomer(string $customer): static
    {
        $this->customer = trim($customer);
        return $this;
    }

    /**
     * Return value of company property
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * Set company property value
     * @param string $company
     * @return static
     */
    public function setCompany(string $company): static
    {
        $this->company = trim($company);
        return $this;
    }

    /**
     * Return value of postalCode property
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * Set postalCode property value
     * @param string $postalCode
     * @return static
     */
    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = trim($postalCode);
        return $this;
    }

    /**
     * Return value of createdBy property
     * @return int|null
     */
    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    /**
     * Set createdBy property value and normalize to integer positive value
     * @param int|null $createdBy
     * @return static
     */
    public function setCreatedBy(?int $createdBy): static
    {
        $this->createdBy = Cast::toInt($createdBy, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * Return value of customFieldFilters property
     * @return array
     */
    public function getCustomFieldFilters(): array
    {
        return $this->customFieldFilters;
    }

    /**
     * Set customFieldFilters property value and normalize to string array value
     * @param array $customFieldFilters
     * @return static
     */
    public function setCustomFieldFilters(array $customFieldFilters): static
    {
        $this->customFieldFilters = ArrayCast::makeStringArray($customFieldFilters);
        return $this;
    }

    /**
     * Return value of email property
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set email property value and normalize to string value
     * @param string $email
     * @return static
     */
    public function setEmail(string $email): static
    {
        $this->email = trim($email);
        return $this;
    }

    /**
     * Return value of userStatusIds property
     * @return int[]
     */
    public function getUserStatusIds(): array
    {
        return $this->userStatusIds;
    }

    /**
     * Set userStatusIds property value and normalize to integer positive value
     * @param int[] $userStatusIds
     * @return static
     */
    public function setUserStatusIds(array $userStatusIds): static
    {
        $this->userStatusIds = ArrayCast::makeIntArray($userStatusIds);
        return $this;
    }

    /**
     * Return value of searchKey property
     * @return string|null
     */
    public function getSearchKey(): ?string
    {
        return $this->searchKey;
    }

    /**
     * Set searchKey property value
     * @param string $searchKey
     * @return static
     */
    public function setSearchKey(string $searchKey): static
    {
        $this->searchKey = trim($searchKey);
        return $this;
    }

    /**
     * Return value of sortOrder property
     * @return string|null
     */
    public function getSortOrder(): ?string
    {
        return $this->sortOrder;
    }

    /**
     * Set sortOrder property value
     * @param string $sortOrder
     * @return static
     */
    public function setSortOrder(string $sortOrder): static
    {
        $this->sortOrder = trim($sortOrder);
        return $this;
    }

    /**
     * Return value of isSortOrderAsc property
     * @return bool
     */
    public function isSortOrderAsc(): bool
    {
        return $this->isSortOrderAsc;
    }

    /**
     * Set isSortOrderAsc property value and normalize boolean value
     * @param bool $isSortOrderAsc
     * @return static
     */
    public function enableSortOrderAsc(bool $isSortOrderAsc): static
    {
        $this->isSortOrderAsc = $isSortOrderAsc;
        return $this;
    }

    /**
     * Return value of sortInfo property
     * @return string|null
     */
    public function getSortInfo(): ?string
    {
        return $this->sortInfo;
    }

    /**
     * Set sortInfo property value and normalize to string value
     * @param string $sortInfo
     * @return static
     */
    public function setSortInfo(string $sortInfo): static
    {
        $this->sortInfo = trim($sortInfo);
        return $this;
    }
}
