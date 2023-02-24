<?php
/**
 * SAM-4989: User Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          boanerge
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/20/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Commission;

use QBaseClass;
use Sam\Commission\Load\UserSalesCommissionLoaderCreateTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserSalesCommission\UserSalesCommissionWriteRepositoryAwareTrait;
use UserSalesCommission;

/**
 * Class CommissionManager
 * @package Sam\Commission
 */
class CommissionManager extends CustomizableClass
{
    use AccountAwareTrait;
    use CurrentDateTrait;
    use EditorUserAwareTrait;
    use UserAwareTrait;
    use UserSalesCommissionLoaderCreateTrait;
    use UserSalesCommissionWriteRepositoryAwareTrait;

    public const TYPE_SALES = UserSalesCommission::class;

    protected string $type = self::TYPE_SALES;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Bulk create commissions
     * @param array $commissions [[amount, percent]]
     * @param int $userId
     * @param int $editorUserId
     */
    public function bulkCreate(array $commissions, int $userId, int $editorUserId): void
    {
        if (!$commissions) {
            return;
        }

        foreach ($commissions as $commission) {
            $this->create(
                (float)$commission[0],
                (float)$commission[1],
                $userId,
                $editorUserId
            );
        }
    }

    /**
     * Bulk update commissions
     * Works in 3 steps: update modified records, remove unused records, add new records.
     * It's faster than just 2 steps algorithm: remove old records, add new records.
     * @param array $newCommissions [[amount, percent]]
     * @param int $userId
     * @param int $editorUserId
     */
    public function bulkUpdate(array $newCommissions, int $userId, int $editorUserId): void
    {
        $oldCommissions = $this->getOldCommissions($userId);

        // Exclude duplicate records
        foreach ($oldCommissions as $oldKey => $oldCommission) {
            foreach ($newCommissions ?: [] as $newKey => $newCommission) {
                if (
                    trim((string)$oldCommission->Amount) === trim($newCommission[0])
                    && trim((string)$oldCommission->Percent) === trim($newCommission[1])
                ) {
                    unset($oldCommissions[$oldKey], $newCommissions[$newKey]);
                }
                if (!$newCommission) {
                    unset($newCommissions[$newKey]);
                }
            }
        }

        // Order keys ascending
        $oldCommissions = array_values($oldCommissions);

        // Update modified records
        $counter = 0;
        foreach ($newCommissions ?: [] as $key => $newCommission) {
            if (!isset($oldCommissions[$counter])) {
                break;
            }
            $oldCommissions[$counter]->AccountId = $this->getAccountId();
            $oldCommissions[$counter]->Amount = (float)$newCommission[0];
            $oldCommissions[$counter]->Percent = $newCommission[1] !== null ? (float)$newCommission[1] : null; // commission percent can be null
            $this->getUserSalesCommissionWriteRepository()->saveWithModifier($oldCommissions[$counter], $editorUserId);
            unset($newCommissions[$key], $oldCommissions[$counter]);
            $counter++;
        }

        // Remove unused records
        foreach ($oldCommissions as $oldCommission) {
            $this->getUserSalesCommissionWriteRepository()->deleteWithModifier($oldCommission, $editorUserId);
        }

        // Add new records
        $this->bulkCreate($newCommissions, $userId, $editorUserId);
    }

    /**
     * Create commission
     * @param float $amount
     * @param float $percent
     * @param int $userId
     * @param int $editorUserId
     * @return UserSalesCommission
     */
    public function create(float $amount, float $percent, int $userId, int $editorUserId): QBaseClass
    {
        /** @var UserSalesCommission $commission */
        $commission = new $this->type();
        $commission->AccountId = $this->getAccountId();
        $commission->Amount = $amount;
        $commission->Percent = $percent;
        $commission->UserId = $userId;
        $this->getUserSalesCommissionWriteRepository()->saveWithModifier($commission, $editorUserId);
        return $commission;
    }

    /**
     * @param string $type
     * @return static
     */
    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get old commissions
     * @param int $userId
     * @return UserSalesCommission[]
     */
    private function getOldCommissions(int $userId): array
    {
        if ($this->type === self::TYPE_SALES) {
            return $this->createUserSalesCommissionLoader()->loadByUserId($userId);
        }
        return [];
    }
}
