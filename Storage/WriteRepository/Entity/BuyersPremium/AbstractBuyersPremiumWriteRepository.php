<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\BuyersPremium;

use BuyersPremium;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractBuyersPremiumWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist BuyersPremium entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param BuyersPremium $entity
     */
    public function save(BuyersPremium $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist BuyersPremium entity in DB on behalf of respective modifier user.
     * @param BuyersPremium $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(BuyersPremium $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist BuyersPremium entity in DB on behalf of system user.
     * @param BuyersPremium $entity
     */
    public function saveWithSystemModifier(BuyersPremium $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist BuyersPremium entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param BuyersPremium $entity
     */
    public function forceSave(BuyersPremium $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist BuyersPremium entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param BuyersPremium $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(BuyersPremium $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete BuyersPremium entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param BuyersPremium $entity
     */
    public function delete(BuyersPremium $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete BuyersPremium entity on behalf of respective modifier user.
     * @param BuyersPremium $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(BuyersPremium $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete BuyersPremium entity on behalf of system user.
     * @param BuyersPremium $entity
     */
    public function deleteWithSystemModifier(BuyersPremium $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
