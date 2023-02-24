<?php
/**
 * SAM-7984: Adjustments for Settlement printable with responsive layout [dev only]
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           06-19, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Access\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Path\SettlementCheckPathResolver;
use Sam\User\Load\UserLoader;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;
use User;

/**
 * Class DataProvider
 * @package Sam\Settlement\Check
 */
class DataProvider extends CustomizableClass
{
    protected ?AdminPrivilegeChecker $editorAdminPrivilegeChecker = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function hasEditorUserPrivilegeForManageSettlements(int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        $has = $this->getEditorAdminPrivilegeChecker($editorUserId, $isReadOnlyDb)
            ->hasPrivilegeForManageSettlements();
        return $has;
    }

    /**
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function hasEditorUserPrivilegeForCrossDomainAdmin(int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        $has = $this->getEditorAdminPrivilegeChecker($editorUserId, $isReadOnlyDb)
            ->hasPrivilegeForSuperadmin();
        return $has;
    }

    /**
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return User|null
     */
    public function loadEditorUser(int $editorUserId, bool $isReadOnlyDb): ?User
    {
        $editorUser = UserLoader::new()->load($editorUserId, $isReadOnlyDb);
        return $editorUser;
    }

    /**
     * @param int $settlementAccountId
     * @param string $settlementCheckFileName
     * @return string
     */
    public function makeSettlementCheckFileRootPath(int $settlementAccountId, string $settlementCheckFileName): string
    {
        $fileRootPath = SettlementCheckPathResolver::new()->makeFileRootPath($settlementAccountId, $settlementCheckFileName);
        return $fileRootPath;
    }

    /**
     * @param string $fileRootPath
     * @return bool
     */
    public function isSettlementCheckFileExists(string $fileRootPath): bool
    {
        $is = $fileRootPath ? file_exists($fileRootPath) : false;
        return $is;
    }


    /* ----- DI --------------*/
    /**
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return AdminPrivilegeChecker
     */
    private function getEditorAdminPrivilegeChecker(int $editorUserId, bool $isReadOnlyDb = false): AdminPrivilegeChecker
    {
        if ($this->editorAdminPrivilegeChecker === null) {
            $this->editorAdminPrivilegeChecker = AdminPrivilegeChecker::new()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->initByUserId($editorUserId);
        }
        return $this->editorAdminPrivilegeChecker;
    }
}
