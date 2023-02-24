<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserSalesCommission;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use UserSalesCommission;

abstract class AbstractUserSalesCommissionWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist UserSalesCommission entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param UserSalesCommission $entity
     */
    public function save(UserSalesCommission $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist UserSalesCommission entity in DB on behalf of respective modifier user.
     * @param UserSalesCommission $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(UserSalesCommission $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist UserSalesCommission entity in DB on behalf of system user.
     * @param UserSalesCommission $entity
     */
    public function saveWithSystemModifier(UserSalesCommission $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist UserSalesCommission entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserSalesCommission $entity
     */
    public function forceSave(UserSalesCommission $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist UserSalesCommission entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserSalesCommission $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(UserSalesCommission $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserSalesCommission entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param UserSalesCommission $entity
     */
    public function delete(UserSalesCommission $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete UserSalesCommission entity on behalf of respective modifier user.
     * @param UserSalesCommission $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(UserSalesCommission $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserSalesCommission entity on behalf of system user.
     * @param UserSalesCommission $entity
     */
    public function deleteWithSystemModifier(UserSalesCommission $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
