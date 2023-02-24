<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingShippingAuctionInc;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingShippingAuctionInc;

abstract class AbstractSettingShippingAuctionIncWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SettingShippingAuctionInc entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SettingShippingAuctionInc $entity
     */
    public function save(SettingShippingAuctionInc $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SettingShippingAuctionInc entity in DB on behalf of respective modifier user.
     * @param SettingShippingAuctionInc $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SettingShippingAuctionInc $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SettingShippingAuctionInc entity in DB on behalf of system user.
     * @param SettingShippingAuctionInc $entity
     */
    public function saveWithSystemModifier(SettingShippingAuctionInc $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SettingShippingAuctionInc entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingShippingAuctionInc $entity
     */
    public function forceSave(SettingShippingAuctionInc $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SettingShippingAuctionInc entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SettingShippingAuctionInc $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SettingShippingAuctionInc $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingShippingAuctionInc entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SettingShippingAuctionInc $entity
     */
    public function delete(SettingShippingAuctionInc $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SettingShippingAuctionInc entity on behalf of respective modifier user.
     * @param SettingShippingAuctionInc $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SettingShippingAuctionInc $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SettingShippingAuctionInc entity on behalf of system user.
     * @param SettingShippingAuctionInc $entity
     */
    public function deleteWithSystemModifier(SettingShippingAuctionInc $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
