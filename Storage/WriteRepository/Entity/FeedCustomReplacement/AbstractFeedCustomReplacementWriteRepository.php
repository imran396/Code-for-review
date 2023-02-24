<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\FeedCustomReplacement;

use FeedCustomReplacement;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractFeedCustomReplacementWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist FeedCustomReplacement entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param FeedCustomReplacement $entity
     */
    public function save(FeedCustomReplacement $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist FeedCustomReplacement entity in DB on behalf of respective modifier user.
     * @param FeedCustomReplacement $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(FeedCustomReplacement $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist FeedCustomReplacement entity in DB on behalf of system user.
     * @param FeedCustomReplacement $entity
     */
    public function saveWithSystemModifier(FeedCustomReplacement $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist FeedCustomReplacement entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param FeedCustomReplacement $entity
     */
    public function forceSave(FeedCustomReplacement $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist FeedCustomReplacement entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param FeedCustomReplacement $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(FeedCustomReplacement $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete FeedCustomReplacement entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param FeedCustomReplacement $entity
     */
    public function delete(FeedCustomReplacement $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete FeedCustomReplacement entity on behalf of respective modifier user.
     * @param FeedCustomReplacement $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(FeedCustomReplacement $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete FeedCustomReplacement entity on behalf of system user.
     * @param FeedCustomReplacement $entity
     */
    public function deleteWithSystemModifier(FeedCustomReplacement $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
