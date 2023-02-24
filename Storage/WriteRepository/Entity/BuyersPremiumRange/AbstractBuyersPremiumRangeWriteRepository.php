<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\BuyersPremiumRange;

use BuyersPremiumRange;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractBuyersPremiumRangeWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist BuyersPremiumRange entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param BuyersPremiumRange $entity
     */
    public function save(BuyersPremiumRange $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist BuyersPremiumRange entity in DB on behalf of respective modifier user.
     * @param BuyersPremiumRange $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(BuyersPremiumRange $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist BuyersPremiumRange entity in DB on behalf of system user.
     * @param BuyersPremiumRange $entity
     */
    public function saveWithSystemModifier(BuyersPremiumRange $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist BuyersPremiumRange entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param BuyersPremiumRange $entity
     */
    public function forceSave(BuyersPremiumRange $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist BuyersPremiumRange entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param BuyersPremiumRange $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(BuyersPremiumRange $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete BuyersPremiumRange entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param BuyersPremiumRange $entity
     */
    public function delete(BuyersPremiumRange $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete BuyersPremiumRange entity on behalf of respective modifier user.
     * @param BuyersPremiumRange $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(BuyersPremiumRange $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete BuyersPremiumRange entity on behalf of system user.
     * @param BuyersPremiumRange $entity
     */
    public function deleteWithSystemModifier(BuyersPremiumRange $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
