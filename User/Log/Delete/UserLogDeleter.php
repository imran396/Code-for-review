<?php
/**
 * SAM-4702: User Log modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SAM 3.1
 * @since           01.02.2019
 * file encoding    UTF-8
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\User\Log\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\UserLog\UserLogWriteRepositoryAwareTrait;
use Sam\User\Log\Load\UserLogLoaderCreateTrait;

/**
 * Class UserLogDeleter
 * @package Sam\User\Log\Delete
 */
class UserLogDeleter extends CustomizableClass
{
    use UserLogLoaderCreateTrait;
    use UserLogWriteRepositoryAwareTrait;

    protected ?string $successMessage = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete UserLog record loaded by id
     * @param int $userLogId
     * @param int $editorUserId
     * @return static
     */
    public function deleteById(int $userLogId, int $editorUserId): static
    {
        $userLog = $this->createUserLogLoader()->load($userLogId, true);
        if ($userLog) {
            $this->getUserLogWriteRepository()->deleteWithModifier($userLog, $editorUserId);
            $this->setSuccessMessage("User Log [" . $userLog->Id . "] has been deleted");
        }
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSuccessMessage(): ?string
    {
        return $this->successMessage;
    }

    /**
     * @param string $successMessage
     * @return static
     */
    public function setSuccessMessage(string $successMessage): static
    {
        $this->successMessage = $successMessage;
        return $this;
    }
}
