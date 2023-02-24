<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserCustData;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use UserCustData;

abstract class AbstractUserCustDataWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist UserCustData entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param UserCustData $entity
     */
    public function save(UserCustData $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist UserCustData entity in DB on behalf of respective modifier user.
     * @param UserCustData $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(UserCustData $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist UserCustData entity in DB on behalf of system user.
     * @param UserCustData $entity
     */
    public function saveWithSystemModifier(UserCustData $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist UserCustData entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserCustData $entity
     */
    public function forceSave(UserCustData $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist UserCustData entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserCustData $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(UserCustData $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserCustData entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param UserCustData $entity
     */
    public function delete(UserCustData $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete UserCustData entity on behalf of respective modifier user.
     * @param UserCustData $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(UserCustData $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserCustData entity on behalf of system user.
     * @param UserCustData $entity
     */
    public function deleteWithSystemModifier(UserCustData $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
