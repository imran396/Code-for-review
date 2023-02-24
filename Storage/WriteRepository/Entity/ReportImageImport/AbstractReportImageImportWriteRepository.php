<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\ReportImageImport;

use ReportImageImport;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractReportImageImportWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist ReportImageImport entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param ReportImageImport $entity
     */
    public function save(ReportImageImport $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist ReportImageImport entity in DB on behalf of respective modifier user.
     * @param ReportImageImport $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(ReportImageImport $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist ReportImageImport entity in DB on behalf of system user.
     * @param ReportImageImport $entity
     */
    public function saveWithSystemModifier(ReportImageImport $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist ReportImageImport entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param ReportImageImport $entity
     */
    public function forceSave(ReportImageImport $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist ReportImageImport entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param ReportImageImport $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(ReportImageImport $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete ReportImageImport entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param ReportImageImport $entity
     */
    public function delete(ReportImageImport $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete ReportImageImport entity on behalf of respective modifier user.
     * @param ReportImageImport $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(ReportImageImport $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete ReportImageImport entity on behalf of system user.
     * @param ReportImageImport $entity
     */
    public function deleteWithSystemModifier(ReportImageImport $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
