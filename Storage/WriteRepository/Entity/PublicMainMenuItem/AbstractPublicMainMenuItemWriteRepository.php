<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\PublicMainMenuItem;

use PublicMainMenuItem;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractPublicMainMenuItemWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist PublicMainMenuItem entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param PublicMainMenuItem $entity
     */
    public function save(PublicMainMenuItem $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist PublicMainMenuItem entity in DB on behalf of respective modifier user.
     * @param PublicMainMenuItem $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(PublicMainMenuItem $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist PublicMainMenuItem entity in DB on behalf of system user.
     * @param PublicMainMenuItem $entity
     */
    public function saveWithSystemModifier(PublicMainMenuItem $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist PublicMainMenuItem entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param PublicMainMenuItem $entity
     */
    public function forceSave(PublicMainMenuItem $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist PublicMainMenuItem entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param PublicMainMenuItem $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(PublicMainMenuItem $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete PublicMainMenuItem entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param PublicMainMenuItem $entity
     */
    public function delete(PublicMainMenuItem $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete PublicMainMenuItem entity on behalf of respective modifier user.
     * @param PublicMainMenuItem $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(PublicMainMenuItem $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete PublicMainMenuItem entity on behalf of system user.
     * @param PublicMainMenuItem $entity
     */
    public function deleteWithSystemModifier(PublicMainMenuItem $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
