<?php
/**
 * SAM-9538: Decouple ACL checking logic from front controller
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Acl\Protect\Internal\Access\Internal\Load;

use Sam\Application\Acl\Protect\Internal\Access\Internal\Ownership\ResourceOwnershipChecker;
use Sam\Application\Acl\Role\AclChecker;
use Sam\Application\Acl\Role\AclRoleDetector;
use Sam\Application\HttpRequest\ServerRequestReader;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManager;
use Sam\User\Load\UserLoader;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;
use User;
use Sam\Core\Constants;

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

    public function isOwnerOfResource(
        int $editorUserId,
        int $resourceEntityId,
        string $resourceController,
        bool $isReadOnlyDb = false
    ): bool {
        return ResourceOwnershipChecker::new()->isOwner(
            $editorUserId,
            $resourceEntityId,
            $resourceController,
            $isReadOnlyDb
        );
    }

    public function loadEditorUser(int $editorUserId, bool $isReadOnlyDb = false): ?User
    {
        return UserLoader::new()->load($editorUserId, $isReadOnlyDb);
    }

    public function hasPrivilegeForSuperadmin(int $editorUserId, bool $isReadOnlyDb): bool
    {
        return AdminPrivilegeChecker::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->initByUserId($editorUserId)
            ->hasPrivilegeForSuperadmin();
    }

    public function loadPageRedirectionUrl(int $systemAccountId): string
    {
        return SettingsManager::new()->get(Constants\Setting::PAGE_REDIRECTION, $systemAccountId);
    }

    public function detectAclRole(?int $editorUserId, Ui $ui): string
    {
        return AclRoleDetector::new()->detect($editorUserId, $ui);
    }

    public function createAclChecker(?int $editorUserId, Ui $ui): AclChecker
    {
        return new AclChecker($editorUserId, $ui);
    }

    public function fetchServerRequestUrl(): string
    {
        return ServerRequestReader::new()->currentUrl();
    }
}
