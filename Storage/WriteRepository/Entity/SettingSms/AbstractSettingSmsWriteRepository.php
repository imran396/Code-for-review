<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingSms;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingSms;

abstract class AbstractSettingSmsWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingSms entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingSms $entity
     */
    public function save(SettingSms $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingSms entity in DB on behalf of respective modifier user.
     * @param SettingSms $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingSms $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingSms entity in DB on behalf of system user.
     * @param SettingSms $entity
     */
    public function saveWithSystemModifier(SettingSms $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingSms entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingSms $entity
     */
    public function forceSave(SettingSms $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingSms entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingSms $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingSms $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingSms entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingSms $entity
     */
    public function delete(SettingSms $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingSms entity on behalf of respective modifier user.
     * @param SettingSms $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingSms $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingSms entity on behalf of system user.
     * @param SettingSms $entity
     */
    public function deleteWithSystemModifier(SettingSms $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
