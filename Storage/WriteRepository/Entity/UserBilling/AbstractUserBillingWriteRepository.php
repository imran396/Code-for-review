<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserBilling;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use UserBilling;

abstract class AbstractUserBillingWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist UserBilling entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param UserBilling $entity
     */
    public function save(UserBilling $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist UserBilling entity in DB on behalf of respective modifier user.
     * @param UserBilling $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(UserBilling $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist UserBilling entity in DB on behalf of system user.
     * @param UserBilling $entity
     */
    public function saveWithSystemModifier(UserBilling $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist UserBilling entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserBilling $entity
     */
    public function forceSave(UserBilling $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist UserBilling entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserBilling $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(UserBilling $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserBilling entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param UserBilling $entity
     */
    public function delete(UserBilling $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete UserBilling entity on behalf of respective modifier user.
     * @param UserBilling $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(UserBilling $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserBilling entity on behalf of system user.
     * @param UserBilling $entity
     */
    public function deleteWithSystemModifier(UserBilling $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
