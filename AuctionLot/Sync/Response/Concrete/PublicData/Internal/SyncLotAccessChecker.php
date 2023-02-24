<?php
/**
 * SAM-6503: Even when the Limit Bidding Info Permission is set to admin it shows asking bid for others User as well
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal;

use Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal\Load\SyncLotAccessCheckerLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SyncLotAccessChecker
 * @package Sam\AuctionLot\Sync\Access
 * @internal
 */
class SyncLotAccessChecker extends CustomizableClass
{
    use SyncLotAccessCheckerLoaderAwareTrait;

    /**
     * @var int|null
     */
    protected ?int $editorUserId;
    /**
     * @var array
     */
    protected array $loadedAuctionIds;
    /**
     * @var array|null
     */
    protected ?array $openAuctionIdsApprovedIn = null;
    /**
     * @var bool|null
     */
    protected ?bool $isUserAdmin = null;
    /**
     * @var bool|null
     */
    protected ?bool $isUserConsignor = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $editorUserId
     * @param array $loadedAuctionIds
     * @param bool $isProfilingEnabled
     * @return static
     */
    public function construct(?int $editorUserId, array $loadedAuctionIds, bool $isProfilingEnabled = false): static
    {
        $this->editorUserId = $editorUserId;
        $this->loadedAuctionIds = $loadedAuctionIds;
        $this->getSyncLotAccessCheckerLoader()->construct($isProfilingEnabled);
        return $this;
    }

    /**
     * Check if the current user has access role for the auction
     *
     * @param string $access
     * @param int $auctionId
     * @param int|null $consignorUserId
     * @return bool
     */
    public function hasAccess(string $access, int $auctionId, ?int $consignorUserId): bool
    {
        $hasAccess = true;
        if (!$this->editorUserId) {
            $hasAccess = ($access === Constants\Role::VISITOR);
        } elseif (!in_array($access, [Constants\Role::VISITOR, Constants\Role::USER], true)) {
            $isAdmin = $this->isUserAdmin();
            if (!$isAdmin) {
                $hasAccess = false;
                if ($access === Constants\Role::BIDDER) {
                    $hasAccess = $this->isUserBidderForAuction($auctionId)
                        || $this->isUserConsignorForAuction($consignorUserId);
                }
            }
        }
        return $hasAccess;
    }

    /**
     * @param int $auctionId
     * @return bool
     */
    protected function isUserBidderForAuction(int $auctionId): bool
    {
        $approvedAuctionIds = $this->getOpenAuctionIdsApprovedIn();
        return in_array($auctionId, $approvedAuctionIds, true);
    }

    /**
     * @param int|null $auctionConsignorId
     * @return bool
     */
    protected function isUserConsignorForAuction(?int $auctionConsignorId): bool
    {
        return $this->isUserConsignor()
            && $auctionConsignorId === $this->editorUserId;
    }

    /**
     * @return bool
     */
    protected function isUserAdmin(): bool
    {
        if ($this->isUserAdmin === null) {
            $this->isUserAdmin = $this->getSyncLotAccessCheckerLoader()->isUserAdmin($this->editorUserId);
        }
        return $this->isUserAdmin;
    }

    /**
     * @return bool
     */
    protected function isUserConsignor(): bool
    {
        if ($this->isUserConsignor === null) {
            $this->isUserConsignor = $this->getSyncLotAccessCheckerLoader()->isUserConsignor($this->editorUserId);
        }
        return $this->isUserConsignor;
    }

    /**
     * @return array
     */
    protected function getOpenAuctionIdsApprovedIn(): array
    {
        if ($this->openAuctionIdsApprovedIn === null) {
            $this->openAuctionIdsApprovedIn = $this->getSyncLotAccessCheckerLoader()
                ->loadAuctionIdsApprovedIn($this->editorUserId, $this->loadedAuctionIds);
        }
        return $this->openAuctionIdsApprovedIn;
    }
}
