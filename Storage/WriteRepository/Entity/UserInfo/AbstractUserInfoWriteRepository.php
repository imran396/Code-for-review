<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserInfo;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use UserInfo;

abstract class AbstractUserInfoWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist UserInfo entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param UserInfo $entity
     */
    public function save(UserInfo $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist UserInfo entity in DB on behalf of respective modifier user.
     * @param UserInfo $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(UserInfo $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist UserInfo entity in DB on behalf of system user.
     * @param UserInfo $entity
     */
    public function saveWithSystemModifier(UserInfo $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist UserInfo entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserInfo $entity
     */
    public function forceSave(UserInfo $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist UserInfo entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserInfo $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(UserInfo $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserInfo entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param UserInfo $entity
     */
    public function delete(UserInfo $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete UserInfo entity on behalf of respective modifier user.
     * @param UserInfo $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(UserInfo $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserInfo entity on behalf of system user.
     * @param UserInfo $entity
     */
    public function deleteWithSystemModifier(UserInfo $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
