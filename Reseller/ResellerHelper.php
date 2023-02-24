<?php

namespace Sam\Reseller;

use Sam\Core\Service\CustomizableClass;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Reseller\UserCert\ResellerUserCertHelperAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * General helping methods for working with resellers (certificates may be linked to user or auction_bidder entity)
 *
 * SAM-2428: Bidonfusion - Reseller certificate tracking changes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Sep 13, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */
class ResellerHelper extends CustomizableClass
{
    use AuctionBidderLoaderAwareTrait;
    use ResellerUserCertHelperAwareTrait;
    use SettingsManagerAwareTrait;

    private const DISABLED = 0;            // Reseller feature disabled
    private const USER_CERT = 1;           // Reseller cert is linked to user's profile and used everywhere
    private const AUCTION_BIDDER_CERT = 2; // Reseller cert is linked to auction_bidder entry. One certificate for one auction.

    /**
     * Class instantiation method
     * @return static or customized instance
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return the state of reseller feature - how it is currently configured
     * @return int
     */
    public function getResellerFeatureState(): int
    {
        $sm = $this->getSettingsManager();
        $state = self::DISABLED;
        $isEnableResellerReg = (bool)$sm->getForMain(Constants\Setting::ENABLE_RESELLER_REG);
        if ($isEnableResellerReg) {
            $isSaveResellerCertInProfile = (bool)$sm->getForMain(Constants\Setting::SAVE_RESELLER_CERT_IN_PROFILE);
            $state = $isSaveResellerCertInProfile ? self::USER_CERT : self::AUCTION_BIDDER_CERT;
        }
        return $state;
    }

    /**
     * Check if reseller feature is configured to accept user's profile certificate
     * @return bool
     */
    public function isUserCert(): bool
    {
        $is = $this->getResellerFeatureState() === self::USER_CERT;
        return $is;
    }

    /**
     * Check if reseller feature is configured to accept auction bidder certificates
     * @return bool
     */
    public function isAuctionBidderCert(): bool
    {
        $is = $this->getResellerFeatureState() === self::AUCTION_BIDDER_CERT;
        return $is;
    }

    /**
     * Check if reseller feature is disabled
     * @return bool
     */
    public function isDisabled(): bool
    {
        $is = $this->getResellerFeatureState() === self::DISABLED;
        return $is;
    }

    /**
     * Check if user is reseller with approved and not expired certificate
     * @param int $userId
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isValidReseller(int $userId, ?int $auctionId, bool $isReadOnlyDb = false): bool
    {
        $isValidReseller = false;
        if ($this->isUserCert()) {
            $isValidReseller = $this->getResellerUserCertHelper()
                ->isActualAndApprovedByUserId($userId, $isReadOnlyDb);
        } elseif ($this->isAuctionBidderCert()) {
            $auctionBidder = $this->getAuctionBidderLoader()->load($userId, $auctionId, $isReadOnlyDb);
            if (
                $auctionBidder
                && $auctionBidder->IsReseller
                && $auctionBidder->ResellerApproved
            ) {
                $isValidReseller = true;
            }
        }
        return $isValidReseller;
    }
}
