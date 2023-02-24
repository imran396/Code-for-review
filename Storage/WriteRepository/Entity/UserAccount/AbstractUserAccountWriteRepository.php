<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserAccount;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use UserAccount;

abstract class AbstractUserAccountWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist UserAccount entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param UserAccount $entity
     */
    public function save(UserAccount $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist UserAccount entity in DB on behalf of respective modifier user.
     * @param UserAccount $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(UserAccount $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist UserAccount entity in DB on behalf of system user.
     * @param UserAccount $entity
     */
    public function saveWithSystemModifier(UserAccount $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist UserAccount entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserAccount $entity
     */
    public function forceSave(UserAccount $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist UserAccount entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserAccount $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(UserAccount $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserAccount entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param UserAccount $entity
     */
    public function delete(UserAccount $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete UserAccount entity on behalf of respective modifier user.
     * @param UserAccount $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(UserAccount $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserAccount entity on behalf of system user.
     * @param UserAccount $entity
     */
    public function deleteWithSystemModifier(UserAccount $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
