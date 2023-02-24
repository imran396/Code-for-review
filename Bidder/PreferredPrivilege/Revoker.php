<?php
/**
 * Revoke preferred bidder privilege, if RevokePreferredBidder option is enabled
 * and user cc info is expired/missing and if user is already a preferred bidder.
 *
 * SAM-4113: Refactor logic of preferred bidder privilege revoke
 * SAM-641: Revoke preferred bidder priv
 *
 * @author        Igors Kotlevskis
 * @since         Feb 22, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidder\PreferredPrivilege;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\WriteRepository\Entity\Bidder\BidderWriteRepositoryAwareTrait;
use Sam\User\Validate\UserBillingCheckerAwareTrait;

/**
 * Class Revoker
 * @package Sam\Bidder\PreferredPrivilege
 */
class Revoker extends CustomizableClass
{
    use BidderWriteRepositoryAwareTrait;
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;
    use UserAwareTrait;
    use UserBillingCheckerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return bool
     */
    public function shouldRevoke(): bool
    {
        $isRevokePreferredBidderEnabled = $this->getSettingsManager()->getForMain(Constants\Setting::REVOKE_PREFERRED_BIDDER);
        $isPreferredBidder = $this->getUserBidderPrivilegeChecker()->hasPrivilegeForPreferred();
        $should = $isRevokePreferredBidderEnabled
            && $isPreferredBidder
            && ($this->getUserBillingChecker()->hasCcExpired($this->getUserId())
                || !$this->getUserBillingChecker()->hasCcInfo($this->getUserId(), $this->getSystemAccountId()));
        return $should;
    }

    public function revokePrivilege(int $editorUserId): void
    {
        $bidder = $this->getBidder();
        if (!$bidder) {
            log_error(
                "Available bidder not found, when revoking bidder privilege"
                . composeSuffix(['u' => $this->getUserId()])
            );
            return;
        }
        $bidder->dropPreferred();
        $this->getBidderWriteRepository()->saveWithModifier($bidder, $editorUserId);
        log_debug(
            "Preferred bidder privilege revoked because cc info expired or missed"
            . composeSuffix(['u' => $this->getUserId(), 'acc' => $this->getSystemAccountId()])
        );
    }
}
