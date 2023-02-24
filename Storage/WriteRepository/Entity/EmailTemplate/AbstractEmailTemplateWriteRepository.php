<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\EmailTemplate;

use EmailTemplate;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractEmailTemplateWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist EmailTemplate entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param EmailTemplate $entity
     */
    public function save(EmailTemplate $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist EmailTemplate entity in DB on behalf of respective modifier user.
     * @param EmailTemplate $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(EmailTemplate $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist EmailTemplate entity in DB on behalf of system user.
     * @param EmailTemplate $entity
     */
    public function saveWithSystemModifier(EmailTemplate $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist EmailTemplate entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param EmailTemplate $entity
     */
    public function forceSave(EmailTemplate $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist EmailTemplate entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param EmailTemplate $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(EmailTemplate $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete EmailTemplate entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param EmailTemplate $entity
     */
    public function delete(EmailTemplate $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete EmailTemplate entity on behalf of respective modifier user.
     * @param EmailTemplate $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(EmailTemplate $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete EmailTemplate entity on behalf of system user.
     * @param EmailTemplate $entity
     */
    public function deleteWithSystemModifier(EmailTemplate $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
