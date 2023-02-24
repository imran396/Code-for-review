<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserWavebid;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use UserWavebid;

abstract class AbstractUserWavebidWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist UserWavebid entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param UserWavebid $entity
     */
    public function save(UserWavebid $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist UserWavebid entity in DB on behalf of respective modifier user.
     * @param UserWavebid $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(UserWavebid $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist UserWavebid entity in DB on behalf of system user.
     * @param UserWavebid $entity
     */
    public function saveWithSystemModifier(UserWavebid $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist UserWavebid entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserWavebid $entity
     */
    public function forceSave(UserWavebid $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist UserWavebid entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserWavebid $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(UserWavebid $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserWavebid entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param UserWavebid $entity
     */
    public function delete(UserWavebid $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete UserWavebid entity on behalf of respective modifier user.
     * @param UserWavebid $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(UserWavebid $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserWavebid entity on behalf of system user.
     * @param UserWavebid $entity
     */
    public function deleteWithSystemModifier(UserWavebid $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
