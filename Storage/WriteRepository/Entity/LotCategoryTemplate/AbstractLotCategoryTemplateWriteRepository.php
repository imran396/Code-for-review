<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotCategoryTemplate;

use LotCategoryTemplate;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractLotCategoryTemplateWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist LotCategoryTemplate entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param LotCategoryTemplate $entity
     */
    public function save(LotCategoryTemplate $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist LotCategoryTemplate entity in DB on behalf of respective modifier user.
     * @param LotCategoryTemplate $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(LotCategoryTemplate $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist LotCategoryTemplate entity in DB on behalf of system user.
     * @param LotCategoryTemplate $entity
     */
    public function saveWithSystemModifier(LotCategoryTemplate $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist LotCategoryTemplate entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotCategoryTemplate $entity
     */
    public function forceSave(LotCategoryTemplate $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist LotCategoryTemplate entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param LotCategoryTemplate $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(LotCategoryTemplate $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotCategoryTemplate entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param LotCategoryTemplate $entity
     */
    public function delete(LotCategoryTemplate $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete LotCategoryTemplate entity on behalf of respective modifier user.
     * @param LotCategoryTemplate $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(LotCategoryTemplate $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete LotCategoryTemplate entity on behalf of system user.
     * @param LotCategoryTemplate $entity
     */
    public function deleteWithSystemModifier(LotCategoryTemplate $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
