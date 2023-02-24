<?php
/**
 * Consignor Report Data Loader
 *
 * SAM-6032: Refactor Consignor report page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 28, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ConsignorReportForm\Load;

use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Filter\Entity\FilterUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Sam\View\Admin\Form\ConsignorReportForm\ConsignorReportConstants;

/**
 * Class ConsignorReportDataLoader
 */
class ConsignorReportDataLoader extends CustomizableClass
{
    use DateHelperAwareTrait;
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterDatePeriodAwareTrait;
    use FilterUserAwareTrait;
    use LimitInfoAwareTrait;
    use SettingsManagerAwareTrait;
    use SortInfoAwareTrait;
    use TimezoneLoaderAwareTrait;

    protected ?int $filterLotStatusId = null;
    protected string $sortOrderDefaultIndex = ConsignorReportConstants::ORD_DEFAULT;
    /** @var string[][] */
    protected array $orderFieldsMapping = [
        ConsignorReportConstants::ORD_CUSTOMER_NO => [
            'ASC' => 'u.customer_no ASC',
            'DESC' => 'u.customer_no DESC',
        ],
        ConsignorReportConstants::ORD_USERNAME => [
            'ASC' => 'u.username ASC',
            'DESC' => 'u.username DESC',
        ],
        ConsignorReportConstants::ORD_FIRST_NAME => [
            'ASC' => 'ui.first_name ASC',
            'DESC' => 'ui.first_name DESC',
        ],
        ConsignorReportConstants::ORD_LAST_NAME => [
            'ASC' => 'ui.last_name ASC',
            'DESC' => 'ui.last_name DESC',
        ],
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int|null
     */
    public function getFilterLotStatusId(): ?int
    {
        return $this->filterLotStatusId;
    }

    /**
     * @param int|null $lotStatusId null means All statuses
     * @return $this
     */
    public function filterLotStatusId(?int $lotStatusId): static
    {
        $this->filterLotStatusId = $lotStatusId;
        return $this;
    }

    /**
     * @return int - return value of Consignors count
     */
    public function count(): int
    {
        $countQuery = 'SELECT COUNT(1) AS cnt FROM (SELECT DISTINCT(u.id), u.customer_no, u.username, ui.first_name, ui.last_name, ali.auction_id'
            . $this->buildFromClause()
            . $this->buildWhereClause()
            . $this->buildGroupClause()
            . ') AS dis_cnt';
        $this->query($countQuery);
        $rows = $this->fetchAssoc();
        return $rows['cnt'];
    }

    /**
     * @return array - return values for Consignors
     */
    public function load(): array
    {
        $resultQuery = "SELECT DISTINCT(u.id), u.customer_no, u.username, ui.first_name, ui.last_name"
            . $this->buildFromClause()
            . $this->buildWhereClause()
            . $this->buildGroupClause()
            . $this->buildOrderClause()
            . $this->buildLimitClause();
        $this->query($resultQuery);
        $dtos = [];
        foreach ($this->fetchAllAssoc() as $row) {
            $dtos[] = ConsignorReportDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @return string
     */
    protected function buildFromClause(): string
    {
        $from = " FROM user u"
            . " LEFT JOIN user_info ui ON ui.user_id = u.id"
            . " INNER JOIN lot_item li ON u.id = li.consignor_id AND li.active"
            . " INNER JOIN auction_lot_item ali ON ali.lot_item_id = li.id"
            . " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ")";
        return $from;
    }

    /**
     * @return string
     */
    protected function buildWhereClause(): string
    {
        $where = " WHERE u.user_status_id = " . Constants\User::US_ACTIVE;

        $filterAuctionId = $this->getFilterAuctionId();
        if ($filterAuctionId) {
            $where .= ' AND ali.auction_id = ' . $this->escape($filterAuctionId);
        } else {
            // without auction id filtering and with dateFrom and dateTo filtering.
            $dateFrom = $this->getFilterStartDateUtc();
            $dateTo = $this->getFilterEndDateUtc();
            if ($dateFrom && $dateTo) {
                $tzId = (int)$this->getSettingsManager()->getForSystem(Constants\Setting::TIMEZONE_ID);
                $timezone = $this->getTimezoneLoader()->load($tzId);
                if ($timezone) {
                    $dateHelper = $this->getDateHelper();
                    $dateFrom = $dateHelper->convertTimezone($dateFrom, $timezone->Location, 'UTC');
                    $dateTo = $dateHelper->convertTimezone($dateTo, $timezone->Location, 'UTC');
                }
                $dateFrom = $dateFrom->format(DATE_ATOM);
                $dateTo = $dateTo->modify('+1 day -1 seconds')->format(DATE_ATOM);

                $where .= ' AND ali.auction_id > 0'
                    . ' AND (li.created_on >= ' . $this->escape($dateFrom)
                    . ' AND li.created_on <= ' . $this->escape($dateTo) . ')';
            }
        }

        $filterUserId = $this->getFilterUserId();
        if ($filterUserId) {
            $where .= " AND li.consignor_id = " . $this->escape($filterUserId);
        }

        $filterAccountId = $this->getFilterAccountId();
        if ($filterAccountId) {
            $where .= " AND li.account_id = " . $this->escape($filterAccountId);
        }

        $filterLotStatusId = $this->getFilterLotStatusId();
        if ($filterLotStatusId) {
            $where .= " AND ali.lot_status_id = " . $this->escape($filterLotStatusId);
        } else {
            $where .= " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ") ";
        }

        return $where;
    }

    /**
     * @return string
     */
    protected function buildGroupClause(): string
    {
        $group = '';
        if (!$this->getFilterAuctionId()) {
            $group .= ' GROUP BY u.id';
        }
        return $group;
    }

    /**
     * @return string
     */
    protected function buildOrderClause(): string
    {
        $sortOrder = $this->getSortColumn() ?: $this->sortOrderDefaultIndex;
        return sprintf(' ORDER BY %s', $this->orderFieldsMapping[$sortOrder][$this->isAscendingOrder() ? 'ASC' : 'DESC']);
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
