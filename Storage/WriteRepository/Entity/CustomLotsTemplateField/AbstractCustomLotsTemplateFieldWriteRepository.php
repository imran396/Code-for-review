<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\CustomLotsTemplateField;

use CustomLotsTemplateField;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractCustomLotsTemplateFieldWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist CustomLotsTemplateField entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param CustomLotsTemplateField $entity
     */
    public function save(CustomLotsTemplateField $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist CustomLotsTemplateField entity in DB on behalf of respective modifier user.
     * @param CustomLotsTemplateField $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(CustomLotsTemplateField $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist CustomLotsTemplateField entity in DB on behalf of system user.
     * @param CustomLotsTemplateField $entity
     */
    public function saveWithSystemModifier(CustomLotsTemplateField $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist CustomLotsTemplateField entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param CustomLotsTemplateField $entity
     */
    public function forceSave(CustomLotsTemplateField $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist CustomLotsTemplateField entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param CustomLotsTemplateField $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(CustomLotsTemplateField $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete CustomLotsTemplateField entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param CustomLotsTemplateField $entity
     */
    public function delete(CustomLotsTemplateField $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete CustomLotsTemplateField entity on behalf of respective modifier user.
     * @param CustomLotsTemplateField $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(CustomLotsTemplateField $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete CustomLotsTemplateField entity on behalf of system user.
     * @param CustomLotsTemplateField $entity
     */
    public function deleteWithSystemModifier(CustomLotsTemplateField $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
