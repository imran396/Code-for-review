<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotImageInBucket;

use LotImageInBucket;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractLotImageInBucketWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist LotImageInBucket entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param LotImageInBucket $entity
     */
    public function save(LotImageInBucket $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist LotImageInBucket entity in DB on behalf of respective modifier user.
     * @param LotImageInBucket $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(LotImageInBucket $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist LotImageInBucket entity in DB on behalf of system user.
     * @param LotImageInBucket $entity
     */
    public function saveWithSystemModifier(LotImageInBucket $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist LotImageInBucket entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotImageInBucket $entity
     */
    public function forceSave(LotImageInBucket $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist LotImageInBucket entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotImageInBucket $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(LotImageInBucket $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotImageInBucket entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param LotImageInBucket $entity
     */
    public function delete(LotImageInBucket $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete LotImageInBucket entity on behalf of respective modifier user.
     * @param LotImageInBucket $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(LotImageInBucket $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotImageInBucket entity on behalf of system user.
     * @param LotImageInBucket $entity
     */
    public function deleteWithSystemModifier(LotImageInBucket $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
