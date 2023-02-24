<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuditTrail;

use AuditTrail;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAuditTrailWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AuditTrail entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AuditTrail $entity
     */
    public function save(AuditTrail $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AuditTrail entity in DB on behalf of respective modifier user.
     * @param AuditTrail $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AuditTrail $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AuditTrail entity in DB on behalf of system user.
     * @param AuditTrail $entity
     */
    public function saveWithSystemModifier(AuditTrail $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AuditTrail entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuditTrail $entity
     */
    public function forceSave(AuditTrail $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AuditTrail entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AuditTrail $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AuditTrail $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuditTrail entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AuditTrail $entity
     */
    public function delete(AuditTrail $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AuditTrail entity on behalf of respective modifier user.
     * @param AuditTrail $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AuditTrail $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AuditTrail entity on behalf of system user.
     * @param AuditTrail $entity
     */
    public function deleteWithSystemModifier(AuditTrail $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
