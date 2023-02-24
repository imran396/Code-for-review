<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\CreditCard;

use CreditCard;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractCreditCardWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist CreditCard entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param CreditCard $entity
     */
    public function save(CreditCard $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist CreditCard entity in DB on behalf of respective modifier user.
     * @param CreditCard $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(CreditCard $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist CreditCard entity in DB on behalf of system user.
     * @param CreditCard $entity
     */
    public function saveWithSystemModifier(CreditCard $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist CreditCard entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param CreditCard $entity
     */
    public function forceSave(CreditCard $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist CreditCard entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param CreditCard $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(CreditCard $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete CreditCard entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param CreditCard $entity
     */
    public function delete(CreditCard $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete CreditCard entity on behalf of respective modifier user.
     * @param CreditCard $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(CreditCard $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete CreditCard entity on behalf of system user.
     * @param CreditCard $entity
     */
    public function deleteWithSystemModifier(CreditCard $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
