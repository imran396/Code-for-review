<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Account;

use Account;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAccountWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist Account entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param Account $entity
     */
    public function save(Account $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist Account entity in DB on behalf of respective modifier user.
     * @param Account $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(Account $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist Account entity in DB on behalf of system user.
     * @param Account $entity
     */
    public function saveWithSystemModifier(Account $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist Account entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Account $entity
     */
    public function forceSave(Account $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist Account entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Account $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(Account $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Account entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param Account $entity
     */
    public function delete(Account $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete Account entity on behalf of respective modifier user.
     * @param Account $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(Account $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Account entity on behalf of system user.
     * @param Account $entity
     */
    public function deleteWithSystemModifier(Account $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
