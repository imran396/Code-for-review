<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserAuthentication;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use UserAuthentication;

abstract class AbstractUserAuthenticationWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist UserAuthentication entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param UserAuthentication $entity
     */
    public function save(UserAuthentication $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist UserAuthentication entity in DB on behalf of respective modifier user.
     * @param UserAuthentication $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(UserAuthentication $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist UserAuthentication entity in DB on behalf of system user.
     * @param UserAuthentication $entity
     */
    public function saveWithSystemModifier(UserAuthentication $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist UserAuthentication entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserAuthentication $entity
     */
    public function forceSave(UserAuthentication $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist UserAuthentication entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserAuthentication $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(UserAuthentication $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserAuthentication entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param UserAuthentication $entity
     */
    public function delete(UserAuthentication $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete UserAuthentication entity on behalf of respective modifier user.
     * @param UserAuthentication $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(UserAuthentication $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserAuthentication entity on behalf of system user.
     * @param UserAuthentication $entity
     */
    public function deleteWithSystemModifier(UserAuthentication $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
