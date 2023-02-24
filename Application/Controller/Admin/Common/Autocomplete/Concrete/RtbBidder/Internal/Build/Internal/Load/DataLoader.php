<?php
/**
 * SAM-10119: Refactor RTB bidder autocomplete
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\RtbBidder\Internal\Build\Internal\Load;

use Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Query\BidderNumQueryConditionMakerCreateTrait;
use Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Query\QueryBuildingHelperCreateTrait;
use Sam\Bidder\AuctionBidder\Query\AuctionBidderQueryBuilderHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;

/**
 * Class DataLoader
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\RtbBidder\Internal\Build\Internal\Load
 */
class DataLoader extends CustomizableClass
{
    use AuctionBidderQueryBuilderHelperCreateTrait;
    use BidderNumQueryConditionMakerCreateTrait;
    use QueryBuildingHelperCreateTrait;
    use UserReadRepositoryCreateTrait;

    protected const LIMIT = 100000;

    /** @var string[] */
    protected const TEXT_SEARCH_FIELDS = [
        "u.username",
        "ui.company_name",
    ];

    /** @var string[] */
    protected const SELECT_FIELDS = [
        'DISTINCT u.id',
        'u.username',
        'ui.company_name',
        'aub.bidder_num'
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
     * Prepare conditions and load RTBD bidder data
     *
     * @param string $searchKeyword
     * @param int $filterAuctionId
     * @param int|null $filterAccountId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function load(string $searchKeyword, int $filterAuctionId, ?int $filterAccountId, bool $isReadOnlyDb = false): array
    {
        $approvedBidderWhereClause = $this->createAuctionBidderQueryBuilderHelper()->makeApprovedBidderWhereClause('aub');
        $repo = $this->createUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->innerJoinBidder() // must have Bidder role
            ->joinAuctionBidderFilterAuctionId($filterAuctionId)
            ->filterUserStatusId([Constants\User::US_ACTIVE])
            ->joinUserInfo()
            ->inlineCondition($approvedBidderWhereClause)
            ->limit(self::LIMIT)
            ->select(self::SELECT_FIELDS);

        if ($filterAccountId) {
            $repo
                ->joinUserAccount()
                ->inlineCondition("u.account_id = {$filterAccountId} OR ua.account_id = {$filterAccountId}");
        }
        $searchCondition = $this->makeSearchCondition($searchKeyword);
        if ($searchCondition) {
            $repo->inlineCondition($searchCondition);
        }
        return $repo->loadRows();
    }

    protected function makeSearchCondition(string $searchKeyword): string
    {
        if (!$searchKeyword) {
            return '';
        }
        $conditionParts = [];
        $conditionParts[] = $this->createQueryBuildingHelper()
            ->makeTypeDependentSearchCondition($searchKeyword, self::TEXT_SEARCH_FIELDS, []);
        $conditionParts[] = $this->createBidderNumQueryConditionMaker()->makeCondition($searchKeyword);
        return implode(' OR ', array_filter($conditionParts));
    }
}
