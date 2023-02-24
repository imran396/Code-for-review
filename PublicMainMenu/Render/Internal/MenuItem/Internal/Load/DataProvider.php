<?php
/**
 * SAM-6767: Responsive Main Menu rendering module adjustments for v3.5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\PublicMainMenu\Render\Internal\MenuItem\Internal\Load;

use Sam\Account\Access\Responsive\ResponsiveAccountPageAccessChecker;
use Sam\Application\HttpRequest\ServerRequestReader;
use Sam\Application\RequestParam\ParamFetcherForGet;
use Sam\Core\Service\CustomizableClass;
use Sam\PublicMainMenu\Render\Internal\AuctionMenu\MainMenuAuctionUrlDetector;
use Sam\User\Privilege\Validate\RoleChecker;

/**
 * Class DataProvider
 * @package
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function fetchBackPageParamUrl(): string
    {
        return ParamFetcherForGet::new()->construct()->getBackUrl()
            ?: ServerRequestReader::new()->currentUrl();
    }

    public function detectMainMenuAuctionUrl(int $accountId): string
    {
        return MainMenuAuctionUrlDetector::new()
            ->construct($accountId)
            ->detectUrl();
    }

    public function isResponsiveAccountPageAvailable(int $accountId, ?int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        return ResponsiveAccountPageAccessChecker::new()->isAvailableByAccountId($accountId, $editorUserId, $isReadOnlyDb);
    }

    public function isConsignor(?int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        return RoleChecker::new()->isConsignor($editorUserId, $isReadOnlyDb);
    }
}
