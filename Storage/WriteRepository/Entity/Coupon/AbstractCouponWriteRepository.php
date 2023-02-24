<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Coupon;

use Coupon;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractCouponWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist Coupon entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param Coupon $entity
     */
    public function save(Coupon $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist Coupon entity in DB on behalf of respective modifier user.
     * @param Coupon $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(Coupon $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist Coupon entity in DB on behalf of system user.
     * @param Coupon $entity
     */
    public function saveWithSystemModifier(Coupon $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist Coupon entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Coupon $entity
     */
    public function forceSave(Coupon $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist Coupon entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Coupon $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(Coupon $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Coupon entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param Coupon $entity
     */
    public function delete(Coupon $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete Coupon entity on behalf of respective modifier user.
     * @param Coupon $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(Coupon $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Coupon entity on behalf of system user.
     * @param Coupon $entity
     */
    public function deleteWithSystemModifier(Coupon $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
