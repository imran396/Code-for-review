<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserShipping;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use UserShipping;

abstract class AbstractUserShippingWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist UserShipping entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param UserShipping $entity
     */
    public function save(UserShipping $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist UserShipping entity in DB on behalf of respective modifier user.
     * @param UserShipping $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(UserShipping $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist UserShipping entity in DB on behalf of system user.
     * @param UserShipping $entity
     */
    public function saveWithSystemModifier(UserShipping $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist UserShipping entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserShipping $entity
     */
    public function forceSave(UserShipping $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist UserShipping entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserShipping $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(UserShipping $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserShipping entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param UserShipping $entity
     */
    public function delete(UserShipping $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete UserShipping entity on behalf of respective modifier user.
     * @param UserShipping $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(UserShipping $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserShipping entity on behalf of system user.
     * @param UserShipping $entity
     */
    public function deleteWithSystemModifier(UserShipping $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
