<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserDocumentViews;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use UserDocumentViews;

abstract class AbstractUserDocumentViewsWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist UserDocumentViews entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param UserDocumentViews $entity
     */
    public function save(UserDocumentViews $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist UserDocumentViews entity in DB on behalf of respective modifier user.
     * @param UserDocumentViews $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(UserDocumentViews $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist UserDocumentViews entity in DB on behalf of system user.
     * @param UserDocumentViews $entity
     */
    public function saveWithSystemModifier(UserDocumentViews $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist UserDocumentViews entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserDocumentViews $entity
     */
    public function forceSave(UserDocumentViews $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist UserDocumentViews entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserDocumentViews $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(UserDocumentViews $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserDocumentViews entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param UserDocumentViews $entity
     */
    public function delete(UserDocumentViews $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete UserDocumentViews entity on behalf of respective modifier user.
     * @param UserDocumentViews $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(UserDocumentViews $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserDocumentViews entity on behalf of system user.
     * @param UserDocumentViews $entity
     */
    public function deleteWithSystemModifier(UserDocumentViews $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
