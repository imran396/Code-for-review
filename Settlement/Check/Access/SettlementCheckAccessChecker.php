<?php
/**
 * SAM-7984: Adjustments for Settlement printable with responsive layout [dev only]
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           06-13, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Access;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManager;
use Sam\Settlement\Check\Access\Internal\Load\DataProvider;
use Sam\Settlement\Check\Access\SettlementCheckAccessCheckingResult as Result;

/**
 * Class SettlementCheckAccessChecker
 * @package Sam\Settlement\Check
 */
class SettlementCheckAccessChecker extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    public const OP_IS_MULTIPLE_TENANT = OptionalKeyConstants::KEY_IS_MULTIPLE_TENANT; // bool
    public const OP_SETTLEMENT_CHECK_FILE_NAME = 'settlementCheckFileName'; // string

    protected ?DataProvider $dataProvider = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $settlementAccountId null if does not provided with GET request
     * @param int|null $editorUserId null if anonymous user
     * @param bool $isReadOnlyDb
     * @param array $optionals = [
     *     self::OP_IS_MULTIPLE_TENANT => bool,
     *     self::OP_SETTLEMENT_CHECK_FILE_NAME => string,
     * ]
     * @return SettlementCheckAccessCheckingResult
     */
    public function checkAccessForView(
        ?int $settlementAccountId,
        ?int $editorUserId = null,
        bool $isReadOnlyDb = false,
        array $optionals = []
    ): Result {
        $result = Result::new()->construct($settlementAccountId, $editorUserId);

        if (!$settlementAccountId) {
            return $result->addError(Result::ERR_SETTLEMENT_ACCOUNT_INVALID);
        }

        if (!$editorUserId) {
            return $result->addError(Result::ERR_ANONYMOUS_USER_ACCESS_DENIED);
        }

        $dataProvider = $this->createDataProvider();
        $editorUser = $dataProvider->loadEditorUser($editorUserId, $isReadOnlyDb);
        if (!$editorUser) {
            return $result->addError(Result::ERR_EDITOR_USER_NOT_FOUND);
        }

        $result->fileName = $this->fetchOptionalSettlementCheckFileName($settlementAccountId, $optionals);
        if (!$result->fileName) {
            return $result->addError(Result::ERR_CHECK_FILE_NOT_CONFIGURED);
        }

        $result->filePath = $dataProvider->makeSettlementCheckFileRootPath($settlementAccountId, $result->fileName);
        $isFileFound = $dataProvider->isSettlementCheckFileExists($result->filePath);
        if (!$isFileFound) {
            return $result->addError(Result::ERR_CHECK_FILE_NOT_FOUND);
        }

        if (!$dataProvider->hasEditorUserPrivilegeForManageSettlements($editorUserId, $isReadOnlyDb)) {
            return $result->addError(Result::ERR_MANAGE_SETTLEMENTS_PRIVILEGE_ABSENT);
        }

        $isMultipleTenant = $this->fetchOptionalIsMultipleTenant($optionals);
        if (!$isMultipleTenant) {
            return $result->addSuccess(Result::OK_ALLOW_ACCESS_FOR_SINGLE_TENANT_INSTALLATION);
        }

        $hasPrivilegeForCrossDomainAdmin = $dataProvider
            ->hasEditorUserPrivilegeForCrossDomainAdmin($editorUserId, $isReadOnlyDb);
        if ($hasPrivilegeForCrossDomainAdmin) {
            return $result->addSuccess(Result::OK_ALLOW_ACCESS_FOR_CROSS_DOMAIN_ADMIN);
        }

        if ($editorUser->AccountId === $settlementAccountId) {
            return $result->addSuccess(Result::OK_ALLOW_ACCESS_FOR_ACCOUNT_MATCH);
        }

        return $result->addError(Result::ERR_ACCESS_DENIED);
    }

    /**
     * @param array $optionals
     * @return bool
     */
    protected function fetchOptionalIsMultipleTenant(array $optionals): bool
    {
        return (bool)($optionals[self::OP_IS_MULTIPLE_TENANT] ?? $this->cfg()->get('core->portal->enabled'));
    }

    /**
     * @param int $settlementAccountId
     * @param array $optionals
     * @return string
     */
    protected function fetchOptionalSettlementCheckFileName(int $settlementAccountId, array $optionals): string
    {
        return (string)($optionals[self::OP_SETTLEMENT_CHECK_FILE_NAME]
            ?? SettingsManager::new()->get(Constants\Setting::STLM_CHECK_FILE, $settlementAccountId));
    }

    // --- DI ---

    /**
     * @return DataProvider
     */
    protected function createDataProvider(): DataProvider
    {
        return $this->dataProvider ?: DataProvider::new();
    }

    /**
     * @param DataProvider $dataProvider
     * @return $this
     * @internal
     */
    public function setDataProvider(DataProvider $dataProvider): static
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }
}
