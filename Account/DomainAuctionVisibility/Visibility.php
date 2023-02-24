<?php
/**
 * Account visibility
 *
 * @see https://bidpath.atlassian.net/browse/SAM-3051
 *
 * If it is "separate", auctions (info, lots, catalog, registration, bidding console, etc)
 * should ONLY be accessible on the main domain or the auction account (sub)domain
 *
 * If it is "directlink", auctions (info, lots, catalog, registration, bidding console, etc)
 * should be accessible via direct link on the main domain and the auction account (sub)domain and all other account
 * (sub)domains. Auctions of other accounts shall NOT be listed on an account (sub)domain auctions page and search
 * results shall NOT include results from other domains.
 *
 * if it is "transparent", auctions should be accessible and listed and results from other domains should be returned
 * on searches. In My items list items of any account (as it is on the main account)
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 16, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\DomainAuctionVisibility;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class Visibility
 *
 * @todo: Adjust unit tests, apply for validation in MySettlementsController.
 */
class Visibility extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param VisibilityContext $visibilityContext
     * @return bool
     */
    public function isAllowed(VisibilityContext $visibilityContext): bool
    {
        if (!$visibilityContext->isSamPortal()) {
            return true;
        }

        $visibility = $visibilityContext->getPortalDomainAuctionVisibility();
        if ($visibility === Constants\AccountVisibility::SEPARATE) {
            return $this->isAllowedForSeparate($visibilityContext);
        }

        if ($visibility === Constants\AccountVisibility::DIRECT_LINK) {
            return true;
        }

        if ($visibility === Constants\AccountVisibility::TRANSPARENT) {
            return true;
        }

        return false;
    }

    /**
     * @param VisibilityContext $visibilityContext
     * @return bool
     */
    protected function isAllowedForSeparate(VisibilityContext $visibilityContext): bool
    {
        $systemAccount = $visibilityContext->getSystemAccount();
        if ($systemAccount->Id === $this->cfg()->get('core->portal->mainAccountId')) {
            return true;
        }

        $auction = $visibilityContext->getAuction();
        if (
            $auction
            && (
                $systemAccount->Id === $auction->AccountId
                || $systemAccount->ShowAll
            ) // SAM-6068
        ) {
            return true;
        }

        $account = $visibilityContext->getAccount();
        if (
            $account
            && (
                $systemAccount->Id === $account->Id
                || $systemAccount->ShowAll
            ) // SAM-6068
        ) {
            return true;
        }

        return false;
    }
}
