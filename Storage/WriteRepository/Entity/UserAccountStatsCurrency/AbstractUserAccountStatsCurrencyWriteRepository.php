<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserAccountStatsCurrency;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use UserAccountStatsCurrency;

abstract class AbstractUserAccountStatsCurrencyWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist UserAccountStatsCurrency entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param UserAccountStatsCurrency $entity
     */
    public function save(UserAccountStatsCurrency $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist UserAccountStatsCurrency entity in DB on behalf of respective modifier user.
     * @param UserAccountStatsCurrency $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(UserAccountStatsCurrency $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist UserAccountStatsCurrency entity in DB on behalf of system user.
     * @param UserAccountStatsCurrency $entity
     */
    public function saveWithSystemModifier(UserAccountStatsCurrency $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist UserAccountStatsCurrency entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserAccountStatsCurrency $entity
     */
    public function forceSave(UserAccountStatsCurrency $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist UserAccountStatsCurrency entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param UserAccountStatsCurrency $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(UserAccountStatsCurrency $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserAccountStatsCurrency entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param UserAccountStatsCurrency $entity
     */
    public function delete(UserAccountStatsCurrency $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete UserAccountStatsCurrency entity on behalf of respective modifier user.
     * @param UserAccountStatsCurrency $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(UserAccountStatsCurrency $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete UserAccountStatsCurrency entity on behalf of system user.
     * @param UserAccountStatsCurrency $entity
     */
    public function deleteWithSystemModifier(UserAccountStatsCurrency $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
