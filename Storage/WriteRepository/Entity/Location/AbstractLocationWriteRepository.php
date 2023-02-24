<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Location;

use Location;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractLocationWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist Location entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param Location $entity
     */
    public function save(Location $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist Location entity in DB on behalf of respective modifier user.
     * @param Location $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(Location $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist Location entity in DB on behalf of system user.
     * @param Location $entity
     */
    public function saveWithSystemModifier(Location $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist Location entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Location $entity
     */
    public function forceSave(Location $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist Location entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Location $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(Location $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Location entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param Location $entity
     */
    public function delete(Location $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete Location entity on behalf of respective modifier user.
     * @param Location $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(Location $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Location entity on behalf of system user.
     * @param Location $entity
     */
    public function deleteWithSystemModifier(Location $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
