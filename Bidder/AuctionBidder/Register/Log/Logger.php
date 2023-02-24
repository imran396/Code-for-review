<?php
/**
 * Log helper
 *
 * SAM-3904: Auction bidder registration class
 *
 * @author        Igors Kotlevskis
 * @since         Sep 14, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidder\AuctionBidder\Register\Log;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;

/**
 * Class Logger
 * @package Sam\Bidder\AuctionBidder\Register\Log
 */
class Logger extends CustomizableClass
{
    use AuctionAwareTrait;
    use UserAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Add prefix and postfix with additional info to logged message
     * @param string $message
     * @param int|null $userId null - for $this->userId
     * @param int|null $auctionId
     * @return string
     */
    public function decorate(string $message, ?int $userId = null, ?int $auctionId = null): string
    {
        if ($userId === null) {
            $userId = $this->getUserId();
        }
        if ($auctionId === null) {
            $auctionId = $this->getAuctionId();
        }

        $userAdditional = $auctionAdditional = '';
        if ($this->getUserBidderPrivilegeChecker()->hasPrivilegeForAgent()) {
            if ($this->getUserId() === $userId) {
                $userAdditional = " (agent)";
            } else {
                $userAdditional = " (buyer)";
            }
        }

        $saleGroup = $this->getAuction()->SaleGroup;
        if ($saleGroup !== '') {
            $auctionAdditional = " (Sale Group: {$saleGroup})";
        }

        $info = [
            'u' => "{$userId}{$userAdditional}",
            'a' => "{$auctionId}{$auctionAdditional}",
        ];
        $infoList = composeSuffix($info);
        $message = "Auction bidder registration: {$message}{$infoList}";
        return $message;
    }
}
