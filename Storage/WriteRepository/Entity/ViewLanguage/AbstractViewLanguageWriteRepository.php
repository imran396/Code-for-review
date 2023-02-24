<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\ViewLanguage;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use ViewLanguage;

abstract class AbstractViewLanguageWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist ViewLanguage entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param ViewLanguage $entity
     */
    public function save(ViewLanguage $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist ViewLanguage entity in DB on behalf of respective modifier user.
     * @param ViewLanguage $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(ViewLanguage $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist ViewLanguage entity in DB on behalf of system user.
     * @param ViewLanguage $entity
     */
    public function saveWithSystemModifier(ViewLanguage $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist ViewLanguage entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param ViewLanguage $entity
     */
    public function forceSave(ViewLanguage $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist ViewLanguage entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param ViewLanguage $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(ViewLanguage $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete ViewLanguage entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param ViewLanguage $entity
     */
    public function delete(ViewLanguage $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete ViewLanguage entity on behalf of respective modifier user.
     * @param ViewLanguage $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(ViewLanguage $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete ViewLanguage entity on behalf of system user.
     * @param ViewLanguage $entity
     */
    public function deleteWithSystemModifier(ViewLanguage $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
