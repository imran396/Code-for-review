<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Timezone;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use Timezone;

abstract class AbstractTimezoneWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist Timezone entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param Timezone $entity
     */
    public function save(Timezone $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist Timezone entity in DB on behalf of respective modifier user.
     * @param Timezone $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(Timezone $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist Timezone entity in DB on behalf of system user.
     * @param Timezone $entity
     */
    public function saveWithSystemModifier(Timezone $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist Timezone entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Timezone $entity
     */
    public function forceSave(Timezone $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist Timezone entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Timezone $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(Timezone $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Timezone entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param Timezone $entity
     */
    public function delete(Timezone $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete Timezone entity on behalf of respective modifier user.
     * @param Timezone $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(Timezone $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Timezone entity on behalf of system user.
     * @param Timezone $entity
     */
    public function deleteWithSystemModifier(Timezone $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
