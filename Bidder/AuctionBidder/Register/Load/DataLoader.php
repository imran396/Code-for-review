<?php

/**
 * SAM-3904: Auction bidder registration class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/1/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Register\Load;

use Auction;
use Sam\Core\Service\CustomizableClass;
use Sam\Auction\SaleGroup\SaleGroupManagerAwareTrait;
use Sam\User\Flag\UserFlaggingAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

class DataLoader extends CustomizableClass
{
    use SaleGroupManagerAwareTrait;
    use UserFlaggingAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * @var array
     */
    protected array $registeringAuctionRows = [];
    /**
     * @var bool[]
     */
    protected array $approvableUserStatuses = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Auction $auction
     * @param bool $shouldRegisterInSaleGroup
     * @return array
     */
    public function getRegisteringAuctionRows(Auction $auction, bool $shouldRegisterInSaleGroup): array
    {
        if (!isset($this->registeringAuctionRows[$auction->Id])) {
            $this->registeringAuctionRows[$auction->Id] = $this->findRegisteringAuctionRows($auction, $shouldRegisterInSaleGroup);
        }
        return $this->registeringAuctionRows[$auction->Id];
    }

    /**
     * @param array $rows
     * @return static
     */
    public function setRegisteringAuctionRows(array $rows): static
    {
        $this->registeringAuctionRows = $rows;
        return $this;
    }

    /**
     * Return array of auctions, where registration should happen.
     * Array contains current auction and other auctions from its sales group.
     * @param Auction $auction
     * @param bool $shouldRegisterInSaleGroup
     * @return array
     */
    protected function findRegisteringAuctionRows(Auction $auction, bool $shouldRegisterInSaleGroup): array
    {
        $rows = [];
        if (
            $shouldRegisterInSaleGroup
            && $auction->SaleGroup
        ) {
            $auctionRows = $this->getSaleGroupManager()->loadAuctionRows($auction->SaleGroup, $auction->AccountId);
            if ($auctionRows) {
                foreach ($auctionRows as $row) {
                    $rows[] = [
                        'id' => (int)$row['id'],
                        'account_id' => (int)$row['account_id'],
                    ];
                }
            }
        }
        if (empty($rows)) {
            $rows[] = [
                'id' => $auction->Id,
                'account_id' => $auction->AccountId,
            ];
        }
        return $rows;
    }

    /**
     * @param int|null $userId
     * @param int $accountId
     * @return bool
     */
    public function isApprovableUser(?int $userId, int $accountId): bool
    {
        if (!$userId) {
            return false;
        }
        if (!array_key_exists($userId, $this->approvableUserStatuses)) {
            $user = $this->getUserLoader()->load($userId);
            if (!$user) {
                log_error("Available user not found" . composeSuffix(['u' => $userId]));
                return false;
            }
            // $accountId = $this->getAuction()->AccountId;
            $this->approvableUserStatuses[$userId] = $this->getUserFlagging()->isAuctionApproval($user, $accountId);
        }
        return $this->approvableUserStatuses[$userId];
    }


}
