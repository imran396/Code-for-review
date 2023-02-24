<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Payment;

use Payment;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractPaymentWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist Payment entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param Payment $entity
     */
    public function save(Payment $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist Payment entity in DB on behalf of respective modifier user.
     * @param Payment $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(Payment $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist Payment entity in DB on behalf of system user.
     * @param Payment $entity
     */
    public function saveWithSystemModifier(Payment $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist Payment entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Payment $entity
     */
    public function forceSave(Payment $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist Payment entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param Payment $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(Payment $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Payment entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param Payment $entity
     */
    public function delete(Payment $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete Payment entity on behalf of respective modifier user.
     * @param Payment $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(Payment $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete Payment entity on behalf of system user.
     * @param Payment $entity
     */
    public function deleteWithSystemModifier(Payment $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
