<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingSeo;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingSeo;

abstract class AbstractSettingSeoWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingSeo entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingSeo $entity
     */
    public function save(SettingSeo $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingSeo entity in DB on behalf of respective modifier user.
     * @param SettingSeo $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingSeo $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingSeo entity in DB on behalf of system user.
     * @param SettingSeo $entity
     */
    public function saveWithSystemModifier(SettingSeo $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingSeo entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingSeo $entity
     */
    public function forceSave(SettingSeo $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingSeo entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingSeo $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingSeo $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingSeo entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingSeo $entity
     */
    public function delete(SettingSeo $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingSeo entity on behalf of respective modifier user.
     * @param SettingSeo $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingSeo $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingSeo entity on behalf of system user.
     * @param SettingSeo $entity
     */
    public function deleteWithSystemModifier(SettingSeo $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
