<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Feed;

use Feed;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractFeedWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist Feed entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param Feed $entity
     */
    public function save(Feed $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist Feed entity in DB on behalf of respective modifier user.
     * @param Feed $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(Feed $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist Feed entity in DB on behalf of system user.
     * @param Feed $entity
     */
    public function saveWithSystemModifier(Feed $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist Feed entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Feed $entity
     */
    public function forceSave(Feed $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist Feed entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Feed $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(Feed $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Feed entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param Feed $entity
     */
    public function delete(Feed $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete Feed entity on behalf of respective modifier user.
     * @param Feed $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(Feed $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Feed entity on behalf of system user.
     * @param Feed $entity
     */
    public function deleteWithSystemModifier(Feed $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
