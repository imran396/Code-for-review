<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Consignor;

use Consignor;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractConsignorWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist Consignor entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param Consignor $entity
     */
    public function save(Consignor $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist Consignor entity in DB on behalf of respective modifier user.
     * @param Consignor $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(Consignor $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist Consignor entity in DB on behalf of system user.
     * @param Consignor $entity
     */
    public function saveWithSystemModifier(Consignor $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist Consignor entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Consignor $entity
     */
    public function forceSave(Consignor $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist Consignor entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Consignor $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(Consignor $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Consignor entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param Consignor $entity
     */
    public function delete(Consignor $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete Consignor entity on behalf of respective modifier user.
     * @param Consignor $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(Consignor $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Consignor entity on behalf of system user.
     * @param Consignor $entity
     */
    public function deleteWithSystemModifier(Consignor $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
