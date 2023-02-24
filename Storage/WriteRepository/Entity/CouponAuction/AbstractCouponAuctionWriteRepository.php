<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\CouponAuction;

use CouponAuction;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractCouponAuctionWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist CouponAuction entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param CouponAuction $entity
     */
    public function save(CouponAuction $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist CouponAuction entity in DB on behalf of respective modifier user.
     * @param CouponAuction $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(CouponAuction $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist CouponAuction entity in DB on behalf of system user.
     * @param CouponAuction $entity
     */
    public function saveWithSystemModifier(CouponAuction $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist CouponAuction entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param CouponAuction $entity
     */
    public function forceSave(CouponAuction $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist CouponAuction entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param CouponAuction $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(CouponAuction $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete CouponAuction entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param CouponAuction $entity
     */
    public function delete(CouponAuction $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete CouponAuction entity on behalf of respective modifier user.
     * @param CouponAuction $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(CouponAuction $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete CouponAuction entity on behalf of system user.
     * @param CouponAuction $entity
     */
    public function deleteWithSystemModifier(CouponAuction $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
