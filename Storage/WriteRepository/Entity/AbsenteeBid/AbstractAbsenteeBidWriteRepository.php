<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AbsenteeBid;

use AbsenteeBid;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAbsenteeBidWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AbsenteeBid entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AbsenteeBid $entity
     */
    public function save(AbsenteeBid $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AbsenteeBid entity in DB on behalf of respective modifier user.
     * @param AbsenteeBid $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AbsenteeBid $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AbsenteeBid entity in DB on behalf of system user.
     * @param AbsenteeBid $entity
     */
    public function saveWithSystemModifier(AbsenteeBid $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AbsenteeBid entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AbsenteeBid $entity
     */
    public function forceSave(AbsenteeBid $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AbsenteeBid entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AbsenteeBid $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AbsenteeBid $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AbsenteeBid entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AbsenteeBid $entity
     */
    public function delete(AbsenteeBid $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AbsenteeBid entity on behalf of respective modifier user.
     * @param AbsenteeBid $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AbsenteeBid $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AbsenteeBid entity on behalf of system user.
     * @param AbsenteeBid $entity
     */
    public function deleteWithSystemModifier(AbsenteeBid $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
