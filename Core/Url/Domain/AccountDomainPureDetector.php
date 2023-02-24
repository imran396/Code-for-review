<?php
/**
 * SAM-9477: Extend JSON API GET configuration information endpoint to include installation and authorized account information
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Core\Url\Domain;

use Account;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

class AccountDomainPureDetector extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Account $account
     * @param int $mainAccountId
     * @param string $appHttpHost
     * @param string $portalUrlHandling
     * @return string
     * #[Pure]
     */
    public function detectByAccount(
        Account $account,
        int $mainAccountId,
        string $appHttpHost,
        string $portalUrlHandling
    ): string {
        return $this->detectByValues(
            $account->Id,
            $account->UrlDomain,
            $account->Name,
            $mainAccountId,
            $appHttpHost,
            $portalUrlHandling
        );
    }

    /**
     * @param int $accountId
     * @param string $urlDomain
     * @param string $name
     * @param int $mainAccountId
     * @param string $appHttpHost
     * @param string $portalUrlHandling
     * @return string
     * #[Pure]
     */
    public function detectByValues(
        int $accountId,
        string $urlDomain,
        string $name,
        int $mainAccountId,
        string $appHttpHost,
        string $portalUrlHandling
    ): string {
        if ($this->isMainAccount($accountId, $mainAccountId)) {
            $domain = $appHttpHost;
        } elseif (
            $urlDomain
            && $this->isAccountUrlDomainAllowed($portalUrlHandling)
        ) {
            $domain = $urlDomain;
        } else {
            $domain = $name . "." . $appHttpHost;
        }
        return $domain;
    }

    /**
     * @param int $accountId
     * @param int $mainAccountId
     * @return bool
     * #[Pure]
     */
    protected function isMainAccount(int $accountId, int $mainAccountId): bool
    {
        return $accountId === $mainAccountId;
    }

    /**
     * @param string $portalUrlHandling
     * @return bool
     * #[Pure]
     */
    protected function isAccountUrlDomainAllowed(string $portalUrlHandling): bool
    {
        return $portalUrlHandling === Constants\PortalUrlHandling::MAIN_DOMAIN;
    }
}
