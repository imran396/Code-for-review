<?php
/**
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Content\Build\Internal\Template\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManager;
use Sam\Storage\ReadRepository\Entity\Settlement\SettlementReadRepository;
use Sam\User\Load\Exception\CouldNotFindUser;
use Sam\User\Load\UserLoader;
use Sam\Core\Constants;

/**
 * Class DataProvider
 * @package Sam\Settlement\Check
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

    public function loadMemoData(int $settlementId, bool $isReadOnlyDb = false): array
    {
        $select = [
            'acc.name AS account_name',
            's.settlement_no',
        ];
        return SettlementReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->joinAccount()
            ->filterId($settlementId)
            ->select($select)
            ->loadRow();
    }

    public function loadUserData(array $select, int $userId, bool $isReadOnlyDb = false): array
    {
        $row = UserLoader::new()->loadSelected($select, $userId, $isReadOnlyDb);
        if (!$row) {
            throw CouldNotFindUser::withId($userId);
        }
        return $row;
    }

    public function loadAddressTemplate(int $accountId): string
    {
        return SettingsManager::new()->get(Constants\Setting::STLM_CHECK_ADDRESS_TEMPLATE, $accountId);
    }

    public function loadPayeeTemplate(int $accountId): string
    {
        return SettingsManager::new()->get(Constants\Setting::STLM_CHECK_PAYEE_TEMPLATE, $accountId);
    }

    public function loadMemoTemplate(int $accountId): string
    {
        return (string)SettingsManager::new()->get(Constants\Setting::STLM_CHECK_MEMO_TEMPLATE, $accountId);
    }
}
