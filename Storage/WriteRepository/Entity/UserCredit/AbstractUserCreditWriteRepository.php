<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserCredit;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use UserCredit;

abstract class AbstractUserCreditWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist UserCredit entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param UserCredit $entity
     */
    public function save(UserCredit $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist UserCredit entity in DB on behalf of respective modifier user.
     * @param UserCredit $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(UserCredit $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist UserCredit entity in DB on behalf of system user.
     * @param UserCredit $entity
     */
    public function saveWithSystemModifier(UserCredit $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist UserCredit entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserCredit $entity
     */
    public function forceSave(UserCredit $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist UserCredit entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserCredit $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(UserCredit $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserCredit entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param UserCredit $entity
     */
    public function delete(UserCredit $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete UserCredit entity on behalf of respective modifier user.
     * @param UserCredit $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(UserCredit $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserCredit entity on behalf of system user.
     * @param UserCredit $entity
     */
    public function deleteWithSystemModifier(UserCredit $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
