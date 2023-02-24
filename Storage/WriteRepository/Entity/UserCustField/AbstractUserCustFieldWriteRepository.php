<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserCustField;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use UserCustField;

abstract class AbstractUserCustFieldWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist UserCustField entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param UserCustField $entity
     */
    public function save(UserCustField $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist UserCustField entity in DB on behalf of respective modifier user.
     * @param UserCustField $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(UserCustField $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist UserCustField entity in DB on behalf of system user.
     * @param UserCustField $entity
     */
    public function saveWithSystemModifier(UserCustField $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist UserCustField entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserCustField $entity
     */
    public function forceSave(UserCustField $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist UserCustField entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserCustField $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(UserCustField $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserCustField entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param UserCustField $entity
     */
    public function delete(UserCustField $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete UserCustField entity on behalf of respective modifier user.
     * @param UserCustField $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(UserCustField $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserCustField entity on behalf of system user.
     * @param UserCustField $entity
     */
    public function deleteWithSystemModifier(UserCustField $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
