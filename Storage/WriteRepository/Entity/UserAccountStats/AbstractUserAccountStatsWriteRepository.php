<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserAccountStats;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use UserAccountStats;

abstract class AbstractUserAccountStatsWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist UserAccountStats entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param UserAccountStats $entity
     */
    public function save(UserAccountStats $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist UserAccountStats entity in DB on behalf of respective modifier user.
     * @param UserAccountStats $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(UserAccountStats $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist UserAccountStats entity in DB on behalf of system user.
     * @param UserAccountStats $entity
     */
    public function saveWithSystemModifier(UserAccountStats $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist UserAccountStats entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserAccountStats $entity
     */
    public function forceSave(UserAccountStats $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist UserAccountStats entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserAccountStats $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(UserAccountStats $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserAccountStats entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param UserAccountStats $entity
     */
    public function delete(UserAccountStats $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete UserAccountStats entity on behalf of respective modifier user.
     * @param UserAccountStats $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(UserAccountStats $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserAccountStats entity on behalf of system user.
     * @param UserAccountStats $entity
     */
    public function deleteWithSystemModifier(UserAccountStats $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
