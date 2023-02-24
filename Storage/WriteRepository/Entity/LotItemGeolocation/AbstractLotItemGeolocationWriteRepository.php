<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotItemGeolocation;

use LotItemGeolocation;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractLotItemGeolocationWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist LotItemGeolocation entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param LotItemGeolocation $entity
     */
    public function save(LotItemGeolocation $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist LotItemGeolocation entity in DB on behalf of respective modifier user.
     * @param LotItemGeolocation $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(LotItemGeolocation $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist LotItemGeolocation entity in DB on behalf of system user.
     * @param LotItemGeolocation $entity
     */
    public function saveWithSystemModifier(LotItemGeolocation $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist LotItemGeolocation entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotItemGeolocation $entity
     */
    public function forceSave(LotItemGeolocation $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist LotItemGeolocation entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotItemGeolocation $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(LotItemGeolocation $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotItemGeolocation entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param LotItemGeolocation $entity
     */
    public function delete(LotItemGeolocation $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete LotItemGeolocation entity on behalf of respective modifier user.
     * @param LotItemGeolocation $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(LotItemGeolocation $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotItemGeolocation entity on behalf of system user.
     * @param LotItemGeolocation $entity
     */
    public function deleteWithSystemModifier(LotItemGeolocation $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
