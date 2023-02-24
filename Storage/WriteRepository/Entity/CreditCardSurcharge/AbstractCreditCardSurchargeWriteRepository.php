<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\CreditCardSurcharge;

use CreditCardSurcharge;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractCreditCardSurchargeWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist CreditCardSurcharge entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param CreditCardSurcharge $entity
     */
    public function save(CreditCardSurcharge $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist CreditCardSurcharge entity in DB on behalf of respective modifier user.
     * @param CreditCardSurcharge $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(CreditCardSurcharge $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist CreditCardSurcharge entity in DB on behalf of system user.
     * @param CreditCardSurcharge $entity
     */
    public function saveWithSystemModifier(CreditCardSurcharge $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist CreditCardSurcharge entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param CreditCardSurcharge $entity
     */
    public function forceSave(CreditCardSurcharge $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist CreditCardSurcharge entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param CreditCardSurcharge $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(CreditCardSurcharge $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete CreditCardSurcharge entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param CreditCardSurcharge $entity
     */
    public function delete(CreditCardSurcharge $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete CreditCardSurcharge entity on behalf of respective modifier user.
     * @param CreditCardSurcharge $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(CreditCardSurcharge $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete CreditCardSurcharge entity on behalf of system user.
     * @param CreditCardSurcharge $entity
     */
    public function deleteWithSystemModifier(CreditCardSurcharge $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
