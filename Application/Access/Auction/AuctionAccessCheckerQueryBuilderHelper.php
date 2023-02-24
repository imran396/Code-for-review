<?php
/**
 * SAM-7764: Refactor \Auction_Access class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Access\Auction;

use Sam\Application\Access\Auction\Internal\ResourceTypeColumnNameProviderCreateTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;
use Sam\User\Privilege\Validate\RoleCheckerAwareTrait;

/**
 * Class AuctionAccessCheckerQueryBuilderHelper
 * @package Sam\Application\Access\Auction
 */
class AuctionAccessCheckerQueryBuilderHelper extends CustomizableClass
{
    use AuctionBidderLoaderAwareTrait;
    use AuthIdentityManagerCreateTrait;
    use ResourceTypeColumnNameProviderCreateTrait;
    use RoleCheckerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return WHERE clause for resource permission check
     *
     * @param int $resourceType
     * @param int|null $userId Null for current logged user
     * @param string $tableAlias Auction table alias name
     * @return string
     */
    public function makeWhereClause(int $resourceType, ?int $userId = null, string $tableAlias = 'a'): string
    {
        $accessColumn = $this->detectResourceColumnName($resourceType, $tableAlias);
        $userId = $userId ?? $this->createAuthIdentityManager()->getUserId();

        $expression = $userId === null
            ? $this->makeExpressionForVisitor($accessColumn)
            : $this->makeExpressionForUser($userId, $accessColumn, $tableAlias);
        if (!empty($expression)) {
            $expression = "(" . $expression . ")";
        }
        return $expression;
    }

    /**
     * @param string $column
     * @return string
     */
    protected function makeExpressionForVisitor(string $column): string
    {
        return $column . " = '" . Constants\Role::VISITOR . "'";
    }

    /**
     * @param int $userId
     * @param string $column
     * @param string $tableAlias
     * @return string
     */
    protected function makeExpressionForUser(int $userId, string $column, string $tableAlias): string
    {
        $expression = '';
        $isAdmin = $this->getRoleChecker()->isAdmin($userId);
        if (!$isAdmin) {
            $approvedAuctionIds = $this->loadApprovedAuctionIds($userId) ?: [0];
            $idColumn = $tableAlias
                ? "{$tableAlias}.id"
                : 'id';
            $expression = "({$column} = '" . Constants\Role::BIDDER . "'" .
                " AND $idColumn IN (" . implode(', ', $approvedAuctionIds) . "))" .
                " OR {$column} NOT IN ('" . Constants\Role::BIDDER . "', '" . Constants\Role::ADMIN . "')" .
                " OR {$column} IS NULL";     // no auction assigned lots can't be checked for access permission
        }
        return $expression;
    }

    /**
     * @param int $resourceId
     * @param string $tableAlias
     * @return string
     */
    protected function detectResourceColumnName(int $resourceId, string $tableAlias): string
    {
        $column = $this->createResourceTypeColumnNameProvider()->getColumnName($resourceId);
        if ($tableAlias !== '') {
            $column = "{$tableAlias}.{$column}";
        }
        return $column;
    }

    /**
     * @param int $userId
     * @return array
     */
    protected function loadApprovedAuctionIds(int $userId): array
    {
        $auctionBidders = $this->getAuctionBidderLoader()->loadOpenAuctionsApprovedIn($userId);
        $auctionIds = [];
        foreach ($auctionBidders as $auctionBidder) {
            $auctionIds[] = $auctionBidder->AuctionId;
        }
        return $auctionIds;
    }
}
