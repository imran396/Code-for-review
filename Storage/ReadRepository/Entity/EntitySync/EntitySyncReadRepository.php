<?php
/**
 * General repository for EntitySync entity
 *
 * SAM-5015: Unite sync tables data scheme
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           26 Jul, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\EntitySync;

use Sam\Core\Constants;

/**
 * Class EntitySyncReadRepository
 * @package Sam\Storage\ReadRepository\Entity\EntitySync
 */
class EntitySyncReadRepository extends AbstractEntitySyncReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'account' => 'JOIN account acc ON (acc.id = esync.entity_id and esync.entity_type = ' . Constants\EntitySync::TYPE_ACCOUNT . ')',
        'auction' => 'JOIN auction a ON (a.id = esync.entity_id and esync.entity_type = ' . Constants\EntitySync::TYPE_AUCTION . ')',
        'auction_lot_item' => 'JOIN auction_lot_item ali ON (ali.id = esync.entity_id and esync.entity_type = ' . Constants\EntitySync::TYPE_AUCTION_LOT_ITEM . ')',
        'location' => 'JOIN location li ON (loc.id = esync.entity_id and esync.entity_type = ' . Constants\EntitySync::TYPE_LOCATION . ')',
        'lot_item' => 'JOIN lot_item li ON (li.id = esync.entity_id and esync.entity_type = ' . Constants\EntitySync::TYPE_LOT_ITEM . ')',
        'user' => 'JOIN user u ON (u.id = esync.entity_id and esync.entity_type = ' . Constants\EntitySync::TYPE_USER . ')',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * join entity table and filter account_id
     * @param int $entityType
     * @param int|int[] $accountId
     * @return static
     */
    public function joinEntityFilterAccountId(int $entityType, int|array|null $accountId): static
    {
        if (!$accountId) {
            return $this;
        }
        switch ($entityType) {
            case Constants\EntitySync::TYPE_AUCTION:
                $this->join('auction');
                $this->filterArray('a.account_id', $accountId);
                break;
            case Constants\EntitySync::TYPE_AUCTION_LOT_ITEM:
                $this->join('auction_lot_item');
                $this->filterArray('ali.account_id', $accountId);
                break;
            case Constants\EntitySync::TYPE_LOCATION:
                $this->join('location');
                $this->filterArray('loc.account_id', $accountId);
                break;
            case Constants\EntitySync::TYPE_LOT_ITEM:
                $this->join('lot_item');
                $this->filterArray('li.account_id', $accountId);
                break;
            case Constants\EntitySync::TYPE_USER:
                $this->join('user');
                $this->filterArray('u.account_id', $accountId);
                break;
        }
        return $this;
    }

    /**
     * Join entity table and filter active
     * @param int $entityType
     * @param bool $active
     * @return static
     */
    public function joinEntityFilterActive(int $entityType, bool $active = true): static
    {
        switch ($entityType) {
            case Constants\EntitySync::TYPE_ACCOUNT:
                $this->join('account');
                $this->filterArray('acc.active', $active);
                break;
            case Constants\EntitySync::TYPE_AUCTION:
                $this->join('auction');
                $this->filterArray('a.auction_status_id', Constants\Auction::$availableAuctionStatuses);
                break;
            case Constants\EntitySync::TYPE_AUCTION_LOT_ITEM:
                $this->join('auction_lot_item');
                $this->filterArray('ali.lot_status_id', Constants\Lot::$availableLotStatuses);
                break;
            case Constants\EntitySync::TYPE_LOCATION:
                $this->join('location');
                $this->filterArray('loc.active', $active);
                break;
            case Constants\EntitySync::TYPE_LOT_ITEM:
                $this->join('lot_item');
                $this->filterArray('li.active', $active);
                break;
            case Constants\EntitySync::TYPE_USER:
                $this->join('user');
                $this->filterArray('u.user_status_id', Constants\User::AVAILABLE_USER_STATUSES);
                break;
        }
        return $this;
    }
}
