<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserSentLots;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use UserSentLots;

abstract class AbstractUserSentLotsWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist UserSentLots entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param UserSentLots $entity
     */
    public function save(UserSentLots $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist UserSentLots entity in DB on behalf of respective modifier user.
     * @param UserSentLots $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(UserSentLots $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist UserSentLots entity in DB on behalf of system user.
     * @param UserSentLots $entity
     */
    public function saveWithSystemModifier(UserSentLots $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist UserSentLots entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserSentLots $entity
     */
    public function forceSave(UserSentLots $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist UserSentLots entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserSentLots $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(UserSentLots $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserSentLots entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param UserSentLots $entity
     */
    public function delete(UserSentLots $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete UserSentLots entity on behalf of respective modifier user.
     * @param UserSentLots $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(UserSentLots $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserSentLots entity on behalf of system user.
     * @param UserSentLots $entity
     */
    public function deleteWithSystemModifier(UserSentLots $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
