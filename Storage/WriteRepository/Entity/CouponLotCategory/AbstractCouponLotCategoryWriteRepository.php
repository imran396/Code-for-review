<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\CouponLotCategory;

use CouponLotCategory;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractCouponLotCategoryWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist CouponLotCategory entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param CouponLotCategory $entity
     */
    public function save(CouponLotCategory $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist CouponLotCategory entity in DB on behalf of respective modifier user.
     * @param CouponLotCategory $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(CouponLotCategory $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist CouponLotCategory entity in DB on behalf of system user.
     * @param CouponLotCategory $entity
     */
    public function saveWithSystemModifier(CouponLotCategory $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist CouponLotCategory entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param CouponLotCategory $entity
     */
    public function forceSave(CouponLotCategory $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist CouponLotCategory entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param CouponLotCategory $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(CouponLotCategory $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete CouponLotCategory entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param CouponLotCategory $entity
     */
    public function delete(CouponLotCategory $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete CouponLotCategory entity on behalf of respective modifier user.
     * @param CouponLotCategory $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(CouponLotCategory $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete CouponLotCategory entity on behalf of system user.
     * @param CouponLotCategory $entity
     */
    public function deleteWithSystemModifier(CouponLotCategory $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
