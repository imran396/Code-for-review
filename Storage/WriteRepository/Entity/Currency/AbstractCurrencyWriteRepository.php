<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Currency;

use Currency;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractCurrencyWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist Currency entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param Currency $entity
     */
    public function save(Currency $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist Currency entity in DB on behalf of respective modifier user.
     * @param Currency $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(Currency $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist Currency entity in DB on behalf of system user.
     * @param Currency $entity
     */
    public function saveWithSystemModifier(Currency $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist Currency entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Currency $entity
     */
    public function forceSave(Currency $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist Currency entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Currency $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(Currency $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Currency entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param Currency $entity
     */
    public function delete(Currency $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete Currency entity on behalf of respective modifier user.
     * @param Currency $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(Currency $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Currency entity on behalf of system user.
     * @param Currency $entity
     */
    public function deleteWithSystemModifier(Currency $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
