<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\TimedOnlineItem;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use TimedOnlineItem;

abstract class AbstractTimedOnlineItemWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist TimedOnlineItem entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param TimedOnlineItem $entity
     */
    public function save(TimedOnlineItem $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist TimedOnlineItem entity in DB on behalf of respective modifier user.
     * @param TimedOnlineItem $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(TimedOnlineItem $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist TimedOnlineItem entity in DB on behalf of system user.
     * @param TimedOnlineItem $entity
     */
    public function saveWithSystemModifier(TimedOnlineItem $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist TimedOnlineItem entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param TimedOnlineItem $entity
     */
    public function forceSave(TimedOnlineItem $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist TimedOnlineItem entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param TimedOnlineItem $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(TimedOnlineItem $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete TimedOnlineItem entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param TimedOnlineItem $entity
     */
    public function delete(TimedOnlineItem $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete TimedOnlineItem entity on behalf of respective modifier user.
     * @param TimedOnlineItem $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(TimedOnlineItem $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete TimedOnlineItem entity on behalf of system user.
     * @param TimedOnlineItem $entity
     */
    public function deleteWithSystemModifier(TimedOnlineItem $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
