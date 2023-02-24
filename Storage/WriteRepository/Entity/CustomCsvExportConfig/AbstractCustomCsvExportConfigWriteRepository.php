<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\CustomCsvExportConfig;

use CustomCsvExportConfig;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractCustomCsvExportConfigWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist CustomCsvExportConfig entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param CustomCsvExportConfig $entity
     */
    public function save(CustomCsvExportConfig $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist CustomCsvExportConfig entity in DB on behalf of respective modifier user.
     * @param CustomCsvExportConfig $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(CustomCsvExportConfig $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist CustomCsvExportConfig entity in DB on behalf of system user.
     * @param CustomCsvExportConfig $entity
     */
    public function saveWithSystemModifier(CustomCsvExportConfig $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist CustomCsvExportConfig entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param CustomCsvExportConfig $entity
     */
    public function forceSave(CustomCsvExportConfig $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist CustomCsvExportConfig entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param CustomCsvExportConfig $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(CustomCsvExportConfig $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete CustomCsvExportConfig entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param CustomCsvExportConfig $entity
     */
    public function delete(CustomCsvExportConfig $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete CustomCsvExportConfig entity on behalf of respective modifier user.
     * @param CustomCsvExportConfig $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(CustomCsvExportConfig $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete CustomCsvExportConfig entity on behalf of system user.
     * @param CustomCsvExportConfig $entity
     */
    public function deleteWithSystemModifier(CustomCsvExportConfig $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
