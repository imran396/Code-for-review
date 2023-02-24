<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\ImageImportQueue;

use ImageImportQueue;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractImageImportQueueWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist ImageImportQueue entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param ImageImportQueue $entity
     */
    public function save(ImageImportQueue $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist ImageImportQueue entity in DB on behalf of respective modifier user.
     * @param ImageImportQueue $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(ImageImportQueue $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist ImageImportQueue entity in DB on behalf of system user.
     * @param ImageImportQueue $entity
     */
    public function saveWithSystemModifier(ImageImportQueue $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist ImageImportQueue entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param ImageImportQueue $entity
     */
    public function forceSave(ImageImportQueue $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist ImageImportQueue entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param ImageImportQueue $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(ImageImportQueue $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete ImageImportQueue entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param ImageImportQueue $entity
     */
    public function delete(ImageImportQueue $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete ImageImportQueue entity on behalf of respective modifier user.
     * @param ImageImportQueue $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(ImageImportQueue $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete ImageImportQueue entity on behalf of system user.
     * @param ImageImportQueue $entity
     */
    public function deleteWithSystemModifier(ImageImportQueue $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
