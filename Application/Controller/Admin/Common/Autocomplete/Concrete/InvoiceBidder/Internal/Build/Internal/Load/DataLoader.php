<?php
/**
 * SAM-10115: Refactor invoice bidder autocomplete
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\InvoiceBidder\Internal\Build\Internal\Load;

use Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Query\BidderNumQueryConditionMakerCreateTrait;
use Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Query\QueryBuildingHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataLoader
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\InvoiceBidder
 * @internal
 */
class DataLoader extends CustomizableClass
{
    use BidderNumQueryConditionMakerCreateTrait;
    use DbConnectionTrait;
    use QueryBuildingHelperCreateTrait;

    protected const NUMERIC_SEARCH_FIELDS = [
        'u.customer_no'
    ];
    protected const TEXT_SEARCH_FIELDS = [
        'u.username',
        'u.email',
        'ui.first_name',
        'ui.last_name',

    ];
    protected const SELECT_FIELDS = [
        'aub.bidder_num',
        'i.id',
        'ii.auction_id',
        'i.bidder_id',
        'u.customer_no',
        'u.username',
        'u.email',
        'ui.first_name',
        'ui.last_name',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Prepare conditions and load bidder data.
     * @param string $searchKeyword
     * @param int|null $filterAuctionId
     * @param int|null $filterAccountId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function load(string $searchKeyword, ?int $filterAuctionId, ?int $filterAccountId, bool $isReadOnlyDb = false): array
    {
        $query = $this->makeQuery($searchKeyword, $filterAuctionId, $filterAccountId);
        $this->query($query, $isReadOnlyDb);
        return $this->fetchAllAssoc();
    }

    protected function makeQuery(string $searchKeyword, ?int $filterAuctionId, ?int $filterAccountId): string
    {
        $selectExpression = implode(', ', self::SELECT_FIELDS);
        $filterCondition = $this->makeFilterCondition($searchKeyword, $filterAuctionId, $filterAccountId);

        $query = <<<SQL
SELECT {$selectExpression}
FROM invoice i
    INNER JOIN invoice_item ii ON i.id = ii.invoice_id
    INNER JOIN `user` AS u ON i.bidder_id = u.id
    LEFT JOIN `user_account` ua ON ua.user_id = u.id
    LEFT JOIN `user_info` AS ui ON u.id = ui.user_id
    LEFT JOIN auction_bidder aub ON aub.user_id = u.id AND aub.auction_id = ii.auction_id
WHERE {$filterCondition}
GROUP BY aub.bidder_num, bidder_id
SQL;
        return $query;
    }

    protected function makeFilterCondition(string $searchKeyword, ?int $filterAuctionId, ?int $filterAccountId): string
    {
        $whereExpressions = [
            'u.user_status_id = ' . Constants\User::US_ACTIVE,
            'i.invoice_status_id IN (' . implode(',', Constants\Invoice::$openInvoiceStatuses) . ') ',
            $this->makeSearchExpression($searchKeyword),
        ];
        if ($filterAuctionId) {
            $whereExpressions[] = "ii.auction_id = {$filterAuctionId}";
        }
        if ($filterAccountId) {
            $whereExpressions[] = "u.account_id = {$filterAccountId} OR ua.account_id = {$filterAccountId}";
        }

        $filterCondition = '(' . implode(') AND (', array_filter($whereExpressions)) . ')';
        return $filterCondition;
    }

    protected function makeSearchExpression(string $searchKeyword): string
    {
        $expressions = [];
        $expressions[] = $this->createQueryBuildingHelper()->makeTypeDependentSearchCondition(
            $searchKeyword,
            self::TEXT_SEARCH_FIELDS,
            self::NUMERIC_SEARCH_FIELDS
        );
        $expressions[] = $this->createBidderNumQueryConditionMaker()->makeCondition($searchKeyword);
        return implode(' OR ', array_filter($expressions));
    }
}
