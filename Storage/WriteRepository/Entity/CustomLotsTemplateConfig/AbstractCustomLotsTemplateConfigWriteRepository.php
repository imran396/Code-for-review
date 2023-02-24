<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\CustomLotsTemplateConfig;

use CustomLotsTemplateConfig;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractCustomLotsTemplateConfigWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist CustomLotsTemplateConfig entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param CustomLotsTemplateConfig $entity
     */
    public function save(CustomLotsTemplateConfig $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist CustomLotsTemplateConfig entity in DB on behalf of respective modifier user.
     * @param CustomLotsTemplateConfig $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(CustomLotsTemplateConfig $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist CustomLotsTemplateConfig entity in DB on behalf of system user.
     * @param CustomLotsTemplateConfig $entity
     */
    public function saveWithSystemModifier(CustomLotsTemplateConfig $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist CustomLotsTemplateConfig entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param CustomLotsTemplateConfig $entity
     */
    public function forceSave(CustomLotsTemplateConfig $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist CustomLotsTemplateConfig entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param CustomLotsTemplateConfig $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(CustomLotsTemplateConfig $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete CustomLotsTemplateConfig entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param CustomLotsTemplateConfig $entity
     */
    public function delete(CustomLotsTemplateConfig $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete CustomLotsTemplateConfig entity on behalf of respective modifier user.
     * @param CustomLotsTemplateConfig $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(CustomLotsTemplateConfig $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete CustomLotsTemplateConfig entity on behalf of system user.
     * @param CustomLotsTemplateConfig $entity
     */
    public function deleteWithSystemModifier(CustomLotsTemplateConfig $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
