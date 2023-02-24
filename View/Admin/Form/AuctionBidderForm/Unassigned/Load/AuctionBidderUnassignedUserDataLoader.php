<?php
/**
 * Auction Bidder Data Loader
 *
 * SAM-5593: Refactor data loaders for Auction Bidder List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 22, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderForm\Unassigned\Load;

use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\View\Admin\Form\AuctionBidderForm\Unassigned\AuctionBidderUnassignedConstants;

/**
 * Class AuctionBidderUnassignedUserDataLoader
 */
class AuctionBidderUnassignedUserDataLoader extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use LimitInfoAwareTrait;
    use SettingsManagerAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * @var string|null
     */
    protected ?string $filterCompanyName = null;
    protected ?string $filterCustomer = null;
    protected ?string $filterEmail = null;
    protected ?string $filterPostalCode = null;
    protected ?string $filterSearchKey = null;
    protected string $sortOrderDefaultIndex = AuctionBidderUnassignedConstants::ORD_DEFAULT;
    /**
     * @var string[][]
     */
    protected array $orderFieldsMapping = [
        AuctionBidderUnassignedConstants::ORD_CREATED_ON => [
            'ask' => 'u.created_on ASC',
            'desc' => 'u.created_on DESC',
        ],
        AuctionBidderUnassignedConstants::ORD_CUSTOMER_NO => [
            'asc' => 'customer_no ASC',
            'desc' => 'customer_no DESC',
        ],
        AuctionBidderUnassignedConstants::ORD_USERNAME => [
            'asc' => 'username ASC',
            'desc' => 'username DESC',
        ],
        AuctionBidderUnassignedConstants::ORD_EMAIL => [
            'asc' => 'email ASC',
            'desc' => 'email DESC',
        ],
        AuctionBidderUnassignedConstants::ORD_FLAG => [
            'asc' => 'flag ASC',
            'desc' => 'flag DESC',
        ]
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $filterCompanyName
     * @return static
     */
    public function filterCompanyName(string $filterCompanyName): static
    {
        $this->filterCompanyName = $filterCompanyName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterCompanyName(): ?string
    {
        return $this->filterCompanyName;
    }

    /**
     * @param string $filterCustomer
     * @return static
     */
    public function filterCustomer(string $filterCustomer): static
    {
        $this->filterCustomer = $filterCustomer;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterCustomer(): ?string
    {
        return $this->filterCustomer;
    }

    /**
     * @param string $filterEmail
     * @return static
     */
    public function filterEmail(string $filterEmail): static
    {
        $this->filterEmail = $filterEmail;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterEmail(): ?string
    {
        return $this->filterEmail;
    }

    /**
     * @param string $filterPostalCode
     * @return static
     */
    public function filterPostalCode(string $filterPostalCode): static
    {
        $this->filterPostalCode = $filterPostalCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterPostalCode(): ?string
    {
        return $this->filterPostalCode;
    }

    /**
     * @param string $filterSearchKey
     * @return static
     */
    public function filterSearchKey(string $filterSearchKey): static
    {
        $this->filterSearchKey = $filterSearchKey;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterSearchKey(): ?string
    {
        return $this->filterSearchKey;
    }

    /**
     * @return int - return value of unassigned users count
     */
    public function count(): int
    {
        $select = "SELECT COUNT(1) AS user_total";
        $countQuery = $select . $this->buildFromClause()
            . $this->buildWhereClause();
        $this->query($countQuery);
        $row = $this->fetchAssoc();
        return (int)$row['user_total'];
    }

    /**
     * @return array - return values of unassigned users
     */
    public function load(): array
    {
        $dtos = [];
        $select = <<<SQL
SELECT
    u.id AS id,
    u.customer_no AS customer_no,
    u.username AS username,
    u.email AS email,
    u.flag AS flag,
    ui.first_name AS first_name,
    ui.last_name AS last_name
SQL;
        $resultQuery = $select
            . $this->buildFromClause()
            . $this->buildWhereClause()
            . $this->buildOrderClause()
            . $this->buildLimitClause();
        $this->query($resultQuery);
        $rows = $this->fetchAllAssoc();
        foreach ($rows as $row) {
            $dtos[] = AuctionBidderUnassignedUserDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @return string
     */
    protected function buildFromClause(): string
    {
        $from = " FROM user AS u"
            . " LEFT JOIN user_info AS ui ON u.id = ui.user_id"
            . " LEFT JOIN ( SELECT COUNT(1) AS ab_total, user_id AS ab_user_id"
            . " FROM auction_bidder WHERE auction_id = " . $this->escape($this->getFilterAuctionId())
            . " GROUP BY ab_user_id ) AS ab ON ab.ab_user_id = u.id";

        $isPortalAccount = $this->isPortalSystemAccount();
        $shareInfo = (int)$this->getSettingsManager()->getForMain(Constants\Setting::SHARE_USER_INFO);
        if ($this->cfg()->get('core->portal->enabled')) {
            if (
                (
                    $isPortalAccount
                    && $shareInfo !== Constants\ShareUserInfo::NONE
                )
                || !$isPortalAccount
            ) {
                $from .= ' LEFT JOIN user_account uc'
                    . ' ON uc.user_id = u.id'
                    . ' AND uc.account_id = ' . $this->escape($this->getSystemAccountId());
            }
        }

        if ($this->getFilterPostalCode() && $this->getFilterPostalCode() !== '') {
            $postalCodeEsc = $this->escape('%' . $this->getFilterPostalCode() . '%');
            $from .= " LEFT JOIN ( SELECT COUNT(1) AS ub_total, user_id AS ub_user_id"
                . " FROM user_billing WHERE zip LIKE {$postalCodeEsc}"
                . " GROUP BY ub_user_id ) AS ub ON ub.ub_user_id = u.id";
        }

        return $from;
    }

    /**
     * @return string
     */
    protected function buildWhereClause(): string
    {
        $shareInfo = (int)$this->getSettingsManager()->getForMain(Constants\Setting::SHARE_USER_INFO);
        $isPortalAccount = $this->isPortalSystemAccount();
        $where = " WHERE u.user_status_id = " . Constants\User::US_ACTIVE;
        if ($this->cfg()->get('core->portal->enabled')) {
            $where .= ' AND (u.account_id=' . $this->escape($this->getSystemAccountId());
            if (
                (
                    $isPortalAccount
                    && $shareInfo !== Constants\ShareUserInfo::NONE
                )
                || !$isPortalAccount
            ) { // View or Edit
                $where .= ' OR uc.account_id=' . $this->escape($this->getFilterAccountId());
            } else {
                $where .= ' AND 1 ';
            }
            $where .= ' )';
        } else {
            $where .= " AND u.account_id = " . $this->escape($this->cfg()->get('core->portal->mainAccountId'));
        }

        $where .= " AND ab.ab_total IS NULL";

        if ($this->getFilterSearchKey() && $this->getFilterSearchKey() !== '') {
            $searchWhere = '';
            $searchTerms = explode(' ', $this->getFilterSearchKey());
            $condTmpl = ' u.username LIKE %1$s'
                . ' OR u.email LIKE %1$s'
                . ' OR u.customer_no LIKE %1$s'
                . ' OR ui.first_name LIKE %1$s'
                . ' OR ui.last_name LIKE %1$s';
            foreach ($searchTerms as $searchTerm) {
                $searchTermEsc = $this->escape("%{$searchTerm}%");
                $searchWhere .= sprintf($condTmpl, $searchTermEsc) . ' OR ';
            }
            $searchWhere = rtrim($searchWhere, ' OR ');
            $where .= " AND ($searchWhere)";
        }

        if ($this->getFilterCustomer() && $this->getFilterCustomer() !== '') {
            $customer = $this->escape('%' . $this->getFilterCustomer() . '%');
            $where .= " AND u.customer_no LIKE $customer";
        }
        if ($this->getFilterCompanyName() && $this->getFilterCompanyName() !== '') {
            $company = $this->escape('%' . $this->getFilterCompanyName() . '%');
            $where .= " AND ui.company_name LIKE $company";
        }
        if ($this->getFilterPostalCode() && $this->getFilterPostalCode() !== '') {
            $where .= " AND ub.ub_total IS NOT NULL";
        }
        if ($this->getFilterEmail() && $this->getFilterEmail() !== '') {
            $email = $this->escape('%' . $this->getFilterEmail() . '%');
            $where .= " AND u.email LIKE $email";
        }

        return $where;
    }

    /**
     * @return string
     */
    protected function buildOrderClause(): string
    {
        $sortOrder = $this->getSortColumn() ?: $this->sortOrderDefaultIndex;
        return sprintf(' ORDER BY %s', $this->orderFieldsMapping[$sortOrder][$this->isAscendingOrder() ? 'asc' : 'desc']);
    }

    /**
     * @return string
     */
    protected function buildLimitClause(): string
    {
        $limit = $this->getLimit();
        if ($limit === null) {
            return '';
        }
        $query = $limit;

        $offset = $this->getOffset();
        if ($offset) {
            $query = "{$offset}, {$limit}";
        }
        return sprintf(' LIMIT %s', $query);
    }
}
