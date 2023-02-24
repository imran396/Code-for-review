<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\CustomCsvExportData;

use CustomCsvExportData;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractCustomCsvExportDataWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist CustomCsvExportData entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param CustomCsvExportData $entity
     */
    public function save(CustomCsvExportData $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist CustomCsvExportData entity in DB on behalf of respective modifier user.
     * @param CustomCsvExportData $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(CustomCsvExportData $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist CustomCsvExportData entity in DB on behalf of system user.
     * @param CustomCsvExportData $entity
     */
    public function saveWithSystemModifier(CustomCsvExportData $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist CustomCsvExportData entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param CustomCsvExportData $entity
     */
    public function forceSave(CustomCsvExportData $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist CustomCsvExportData entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param CustomCsvExportData $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(CustomCsvExportData $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete CustomCsvExportData entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param CustomCsvExportData $entity
     */
    public function delete(CustomCsvExportData $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete CustomCsvExportData entity on behalf of respective modifier user.
     * @param CustomCsvExportData $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(CustomCsvExportData $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete CustomCsvExportData entity on behalf of system user.
     * @param CustomCsvExportData $entity
     */
    public function deleteWithSystemModifier(CustomCsvExportData $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
