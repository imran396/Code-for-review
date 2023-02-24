<?php
/**
 * SAM-10097: Distinguish auction bidder autocomplete data loading end-points for different pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\AuctionBidder\Internal\Build\Internal\Load\Internal\Load;

use Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Query\BidderNumQueryConditionMakerCreateTrait;
use Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Query\QueryBuildingHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;

/**
 * Class DataLoader
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\AuctionBidder\Internal\Build
 */
class DataLoader extends CustomizableClass
{
    use BidderNumQueryConditionMakerCreateTrait;
    use QueryBuildingHelperCreateTrait;
    use UserReadRepositoryCreateTrait;

    // 1 record takes 1Kb, 100000 records approximately 100Mb
    protected const LIMIT = 100000;

    protected const NUMERIC_SEARCH_FIELDS = [
        "u.customer_no",
    ];

    /** @var string[] */
    protected const TEXT_SEARCH_FIELDS = [
        "u.email",
        "u.username",
        "ui.company_name",
        "ui.first_name",
        "ui.last_name",
    ];

    /** @var string[] */
    protected const SELECT_FIELDS = [
        'u.id',
        'u.username',
        'ui.company_name',
        'ui.first_name',
        'ui.last_name',
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Prepare conditions and prepare auction bidder data.
     * @param string $searchKeyword
     * @param int|null $filterAuctionId
     * @param int|null $contextAuctionId
     * @param int|null $filterAccountId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function load(
        string $searchKeyword,
        ?int $filterAuctionId,
        ?int $contextAuctionId,
        ?int $filterAccountId,
        bool $isReadOnlyDb = false
    ): array {
        $repo = $this->createUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->innerJoinBidder() // must have Bidder role
            ->filterUserStatusId([Constants\User::US_ACTIVE])
            ->joinUserInfo()
            ->limit(self::LIMIT)
            ->select(self::SELECT_FIELDS);

        if ($filterAuctionId) {
            $repo
                ->joinAuctionBidderFilterAuctionId($filterAuctionId)
                ->addSelect('aub.bidder_num');
        } elseif ($contextAuctionId) {
            $repo
                ->joinAuctionBidderOnAuctionId($contextAuctionId)
                ->addSelect('aub.bidder_num');
        }

        if ($filterAccountId) {
            $repo
                ->inlineCondition("u.account_id = {$filterAccountId} OR ua.account_id = {$filterAccountId}")
                ->joinUserAccount()
                ->groupById() // avoid duplication because of join with `user_account`
            ;
        }

        $searchCondition = $this->makeSearchCondition($searchKeyword, $filterAccountId, $contextAuctionId);
        if ($searchCondition) {
            $repo->inlineCondition($searchCondition);
        }

        return $repo->loadRows();
    }

    protected function makeSearchCondition(string $searchKeyword, ?int $filterAuctionId, ?int $contextAuctionId): string
    {
        if (!$searchKeyword) {
            return '';
        }
        $conditionParts = [];
        $conditionParts[] = $this->createQueryBuildingHelper()
            ->makeTypeDependentSearchCondition($searchKeyword, self::TEXT_SEARCH_FIELDS, self::NUMERIC_SEARCH_FIELDS);
        if ($filterAuctionId || $contextAuctionId) {
            $conditionParts[] = $this->createBidderNumQueryConditionMaker()->makeCondition($searchKeyword);
        }
        return implode(' OR ', array_filter($conditionParts));
    }
}
