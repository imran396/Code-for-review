<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserWatchlist;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use UserWatchlist;

abstract class AbstractUserWatchlistWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist UserWatchlist entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param UserWatchlist $entity
     */
    public function save(UserWatchlist $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist UserWatchlist entity in DB on behalf of respective modifier user.
     * @param UserWatchlist $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(UserWatchlist $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist UserWatchlist entity in DB on behalf of system user.
     * @param UserWatchlist $entity
     */
    public function saveWithSystemModifier(UserWatchlist $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist UserWatchlist entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserWatchlist $entity
     */
    public function forceSave(UserWatchlist $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist UserWatchlist entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserWatchlist $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(UserWatchlist $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserWatchlist entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param UserWatchlist $entity
     */
    public function delete(UserWatchlist $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete UserWatchlist entity on behalf of respective modifier user.
     * @param UserWatchlist $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(UserWatchlist $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserWatchlist entity on behalf of system user.
     * @param UserWatchlist $entity
     */
    public function deleteWithSystemModifier(UserWatchlist $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
