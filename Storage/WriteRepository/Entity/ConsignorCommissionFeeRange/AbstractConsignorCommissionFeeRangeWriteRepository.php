<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\ConsignorCommissionFeeRange;

use ConsignorCommissionFeeRange;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractConsignorCommissionFeeRangeWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist ConsignorCommissionFeeRange entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param ConsignorCommissionFeeRange $entity
     */
    public function save(ConsignorCommissionFeeRange $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist ConsignorCommissionFeeRange entity in DB on behalf of respective modifier user.
     * @param ConsignorCommissionFeeRange $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(ConsignorCommissionFeeRange $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist ConsignorCommissionFeeRange entity in DB on behalf of system user.
     * @param ConsignorCommissionFeeRange $entity
     */
    public function saveWithSystemModifier(ConsignorCommissionFeeRange $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist ConsignorCommissionFeeRange entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param ConsignorCommissionFeeRange $entity
     */
    public function forceSave(ConsignorCommissionFeeRange $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist ConsignorCommissionFeeRange entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param ConsignorCommissionFeeRange $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(ConsignorCommissionFeeRange $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete ConsignorCommissionFeeRange entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param ConsignorCommissionFeeRange $entity
     */
    public function delete(ConsignorCommissionFeeRange $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete ConsignorCommissionFeeRange entity on behalf of respective modifier user.
     * @param ConsignorCommissionFeeRange $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(ConsignorCommissionFeeRange $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete ConsignorCommissionFeeRange entity on behalf of system user.
     * @param ConsignorCommissionFeeRange $entity
     */
    public function deleteWithSystemModifier(ConsignorCommissionFeeRange $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
