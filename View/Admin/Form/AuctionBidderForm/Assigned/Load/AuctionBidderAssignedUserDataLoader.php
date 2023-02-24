<?php
/**
 * Auction Bidder Assigned User Data Loader
 *
 * SAM-5593: Refactor data loaders for Auction Bidder List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 26, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderForm\Assigned\Load;

use Sam\Bidder\AuctionBidder\Query\AuctionBidderQueryBuilderHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\Form\AuctionBidderForm\Assigned\AuctionBidderAssignedConstants;

/**
 * Class AuctionBidderAssignedUserDataLoader
 */
class AuctionBidderAssignedUserDataLoader extends CustomizableClass
{
    use AuctionBidderQueryBuilderHelperCreateTrait;
    use DbConnectionTrait;
    use FilterAuctionAwareTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;

    protected ?string $filterAssignedSearchKey = null;
    protected ?string $filterBidder = null;
    protected string $sortOrderDefaultIndex = AuctionBidderAssignedConstants::ORD_DEFAULT;
    /**
     * @var string[][]
     */
    protected array $orderFieldsMapping = [
        AuctionBidderAssignedConstants::ORD_REGISTERED_ON => [
            'asc' => 'ab.registered_on ASC',
            'desc' => 'ab.registered_on DESC',
        ],
        AuctionBidderAssignedConstants::ORD_BIDDER_NUM => [
            'asc' => 'IF(ab.bidder_num IS NULL OR ab.bidder_num=\'\',1,0), ab.bidder_num ASC, u.username',
            'desc' => 'IF(ab.bidder_num IS NULL OR ab.bidder_num=\'\',0,1), ab.bidder_num DESC, u.username DESC',
        ],
        AuctionBidderAssignedConstants::ORD_USERNAME => [
            'asc' => 'u.username ASC',
            'desc' => 'u.username DESC',
        ],
        AuctionBidderAssignedConstants::ORD_EMAIL => [
            'asc' => 'u.email ASC',
            'desc' => 'u.email DESC',
        ],
        AuctionBidderAssignedConstants::ORD_NAME => [
            'asc' => 'ui.first_name ASC, ui.last_name ASC',
            'desc' => 'ui.first_name DESC, ui.last_name DESC',
        ],
        AuctionBidderAssignedConstants::ORD_FLAG => [
            'asc' => 'u.flag ASC',
            'desc' => 'u.flag DESC',
        ],
        AuctionBidderAssignedConstants::ORD_CURRENT_TOTAL => [
            'asc' => 'current_total ASC',
            'desc' => 'current_total DESC',
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
     * @param string $assignedSearchKey
     * @return static
     */
    public function filterAssignedSearchKey(string $assignedSearchKey): static
    {
        $this->filterAssignedSearchKey = $assignedSearchKey;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterAssignedSearchKey(): ?string
    {
        return $this->filterAssignedSearchKey;
    }

    /**
     * @param string $bidder
     * @return static
     */
    public function filterBidder(string $bidder): static
    {
        $this->filterBidder = $bidder;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterBidder(): ?string
    {
        return $this->filterBidder;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        $count = <<<SQL
SELECT COUNT(1) AS bidder_total
SQL;

        $countQuery = $count . $this->buildFromClause() . $this->buildWhereClause();
        $this->query($countQuery);
        $row = $this->fetchAssoc();
        return (int)$row['bidder_total'];
    }

    /**
     * @return AuctionBidderAssignedUserDto[]
     */
    public function load(): array
    {
        $dtos = [];
        $lotStatusList = implode(',', Constants\Lot::$availableLotStatuses);
        $query = <<<SQL
SELECT
    ab.id AS auction_bidder_id,
    ab.registered_on AS registered_on,
    ab.bidder_num AS bidder_num,
    ab.is_reseller AS is_reseller,
    ab.reseller_approved AS is_reseller_approved,
    ab.reseller_id AS reseller_id,
    ab.reseller_certificate AS reseller_certificate,
    u.id AS user_id,
    u.account_id AS user_account_id,
    u.email AS email,
    u.username AS username,
    ui.first_name AS first_name,
    ui.last_name AS last_name,
    (SELECT SUM(current_bid)
        FROM auction_lot_item ali
        INNER JOIN auction_lot_item_cache alic
            ON alic.auction_lot_item_id=ali.id
        INNER JOIN lot_item li
            ON li.id=ali.lot_item_id
            AND li.active
        WHERE ali.lot_status_id IN ({$lotStatusList})
             AND ali.auction_id = {$this->escape($this->getFilterAuctionId())}
             AND alic.current_bidder_id = u.id) AS current_total
SQL;

        $resultQuery = $query
            . $this->buildFromClause()
            . $this->buildWhereClause()
            . $this->buildOrderClause()
            . $this->buildLimitClause();
        $this->query($resultQuery);
        $rows = $this->fetchAllAssoc();
        foreach ($rows as $row) {
            $dtos[] = AuctionBidderAssignedUserDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @return string
     */
    protected function buildFromClause(): string
    {
        $from = ' FROM auction_bidder AS ab'
            . ' INNER JOIN user AS u ON ab.user_id = u.id'
            . ' LEFT JOIN user_info AS ui ON ab.user_id = ui.user_id';
        return $from;
    }

    /**
     * @return string
     */
    protected function buildWhereClause(): string
    {
        $usActive = Constants\User::US_ACTIVE;
        $where = <<<SQL
 WHERE
 u.user_status_id = {$usActive}
 AND ab.auction_id = {$this->escape($this->getFilterAuctionId())}
SQL;

        if ($this->getFilterAssignedSearchKey() !== '') {
            $searchWhere = '';
            $searchTerms = explode(' ', $this->getFilterAssignedSearchKey());
            $condTmpl = ' u.username LIKE %1$s'
                . ' OR u.email LIKE %1$s'
                . ' OR u.customer_no LIKE %1$s'
                . ' OR ui.first_name LIKE %1$s'
                . ' OR ui.last_name LIKE %1$s'
                . ' OR ab.bidder_num LIKE %1$s';
            foreach ($searchTerms as $searchTerm) {
                $searchTerm = $this->escape("%$searchTerm%");
                $searchWhere .= sprintf($condTmpl, $searchTerm) . ' OR ';
            }
            $searchWhere = rtrim($searchWhere, ' OR ');
            $where .= " AND ($searchWhere)";
        }

        if ($this->getFilterBidder() === AuctionBidderAssignedConstants::ASF_APPROVED) {
            $where .= ' AND ' . $this->createAuctionBidderQueryBuilderHelper()->makeApprovedBidderWhereClause('ab');
        } elseif ($this->getFilterBidder() === AuctionBidderAssignedConstants::ASF_UNAPPROVED) {
            $where .= ' AND ' . $this->createAuctionBidderQueryBuilderHelper()->makeUnApprovedBidderWhereClause('ab');
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
