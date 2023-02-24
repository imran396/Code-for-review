<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\ActionQueue;

use ActionQueue;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractActionQueueWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist ActionQueue entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param ActionQueue $entity
     */
    public function save(ActionQueue $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist ActionQueue entity in DB on behalf of respective modifier user.
     * @param ActionQueue $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(ActionQueue $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist ActionQueue entity in DB on behalf of system user.
     * @param ActionQueue $entity
     */
    public function saveWithSystemModifier(ActionQueue $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist ActionQueue entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param ActionQueue $entity
     */
    public function forceSave(ActionQueue $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist ActionQueue entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param ActionQueue $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(ActionQueue $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete ActionQueue entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param ActionQueue $entity
     */
    public function delete(ActionQueue $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete ActionQueue entity on behalf of respective modifier user.
     * @param ActionQueue $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(ActionQueue $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete ActionQueue entity on behalf of system user.
     * @param ActionQueue $entity
     */
    public function deleteWithSystemModifier(ActionQueue $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
