<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\ConsignorCommissionFee;

use ConsignorCommissionFee;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractConsignorCommissionFeeWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist ConsignorCommissionFee entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param ConsignorCommissionFee $entity
     */
    public function save(ConsignorCommissionFee $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist ConsignorCommissionFee entity in DB on behalf of respective modifier user.
     * @param ConsignorCommissionFee $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(ConsignorCommissionFee $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist ConsignorCommissionFee entity in DB on behalf of system user.
     * @param ConsignorCommissionFee $entity
     */
    public function saveWithSystemModifier(ConsignorCommissionFee $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist ConsignorCommissionFee entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param ConsignorCommissionFee $entity
     */
    public function forceSave(ConsignorCommissionFee $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist ConsignorCommissionFee entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param ConsignorCommissionFee $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(ConsignorCommissionFee $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete ConsignorCommissionFee entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param ConsignorCommissionFee $entity
     */
    public function delete(ConsignorCommissionFee $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete ConsignorCommissionFee entity on behalf of respective modifier user.
     * @param ConsignorCommissionFee $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(ConsignorCommissionFee $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete ConsignorCommissionFee entity on behalf of system user.
     * @param ConsignorCommissionFee $entity
     */
    public function deleteWithSystemModifier(ConsignorCommissionFee $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
