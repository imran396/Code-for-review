<?php
/**
 * SAM-4091: User credit manager class
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Feb 7, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Credit;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\UserCredit\UserCreditReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\UserCredit\UserCreditWriteRepositoryAwareTrait;
use UserCredit;

/**
 * Class UserCreditManager
 * @package Sam\User\Credit
 */
class UserCreditManager extends CustomizableClass
{
    use UserCreditReadRepositoryCreateTrait;
    use UserCreditWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $userId
     * @param float $amount
     * @param int $editorUserId
     */
    public function deduct(?int $userId, float $amount, int $editorUserId): void
    {
        if (!$userId) {
            log_error('Unknown user for deducting credit');
            return;
        }

        foreach ($this->loadAvailableCredits($userId) as $userCredit) {
            $remain = $userCredit->Credits - $userCredit->CreditsUsed;
            if (Floating::gteq($amount, $remain)) {
                $userCredit->CreditsUsed = $userCredit->Credits;
                $amount -= $remain;
            } else {
                $userCredit->CreditsUsed += $amount;
                $amount = 0.;
            }
            $this->getUserCreditWriteRepository()->saveWithModifier($userCredit, $editorUserId);

            if (Floating::eq($amount, 0.)) {
                break;
            }
        }
    }

    /**
     * @param int $userId
     * @return float|null
     */
    public function calcTotal(int $userId): ?float
    {
        $row = $this->createUserCreditReadRepository()
            ->enableReadOnlyDb(true)
            ->filterUserId($userId)
            ->select(['SUM(credits - credits_used) AS credit_total'])
            ->loadRow();
        $creditTotal = Cast::toFloat($row['credit_total'] ?? null);
        return $creditTotal;
    }

    /**
     * @param int $userId
     * @return UserCredit[]
     */
    private function loadAvailableCredits(int $userId): array
    {
        $userCredits = $this->createUserCreditReadRepository()
            ->enableReadOnlyDb(true)
            ->filterUserId($userId)
            ->inlineCondition('credits - credits_used > 0')
            ->orderByCreatedOn()
            ->loadEntities();
        return $userCredits;
    }
}
