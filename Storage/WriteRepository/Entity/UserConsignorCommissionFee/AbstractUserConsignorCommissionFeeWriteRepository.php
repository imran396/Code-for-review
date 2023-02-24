<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserConsignorCommissionFee;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use UserConsignorCommissionFee;

abstract class AbstractUserConsignorCommissionFeeWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist UserConsignorCommissionFee entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param UserConsignorCommissionFee $entity
     */
    public function save(UserConsignorCommissionFee $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist UserConsignorCommissionFee entity in DB on behalf of respective modifier user.
     * @param UserConsignorCommissionFee $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(UserConsignorCommissionFee $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist UserConsignorCommissionFee entity in DB on behalf of system user.
     * @param UserConsignorCommissionFee $entity
     */
    public function saveWithSystemModifier(UserConsignorCommissionFee $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist UserConsignorCommissionFee entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserConsignorCommissionFee $entity
     */
    public function forceSave(UserConsignorCommissionFee $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist UserConsignorCommissionFee entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserConsignorCommissionFee $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(UserConsignorCommissionFee $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserConsignorCommissionFee entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param UserConsignorCommissionFee $entity
     */
    public function delete(UserConsignorCommissionFee $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete UserConsignorCommissionFee entity on behalf of respective modifier user.
     * @param UserConsignorCommissionFee $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(UserConsignorCommissionFee $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserConsignorCommissionFee entity on behalf of system user.
     * @param UserConsignorCommissionFee $entity
     */
    public function deleteWithSystemModifier(UserConsignorCommissionFee $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
