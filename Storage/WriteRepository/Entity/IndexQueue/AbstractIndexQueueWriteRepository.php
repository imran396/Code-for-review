<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\IndexQueue;

use IndexQueue;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractIndexQueueWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist IndexQueue entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param IndexQueue $entity
     */
    public function save(IndexQueue $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist IndexQueue entity in DB on behalf of respective modifier user.
     * @param IndexQueue $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(IndexQueue $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist IndexQueue entity in DB on behalf of system user.
     * @param IndexQueue $entity
     */
    public function saveWithSystemModifier(IndexQueue $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist IndexQueue entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param IndexQueue $entity
     */
    public function forceSave(IndexQueue $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist IndexQueue entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param IndexQueue $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(IndexQueue $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete IndexQueue entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param IndexQueue $entity
     */
    public function delete(IndexQueue $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete IndexQueue entity on behalf of respective modifier user.
     * @param IndexQueue $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(IndexQueue $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete IndexQueue entity on behalf of system user.
     * @param IndexQueue $entity
     */
    public function deleteWithSystemModifier(IndexQueue $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
