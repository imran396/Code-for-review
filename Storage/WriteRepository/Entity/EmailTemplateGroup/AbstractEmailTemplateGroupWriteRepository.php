<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\EmailTemplateGroup;

use EmailTemplateGroup;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractEmailTemplateGroupWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist EmailTemplateGroup entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param EmailTemplateGroup $entity
     */
    public function save(EmailTemplateGroup $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist EmailTemplateGroup entity in DB on behalf of respective modifier user.
     * @param EmailTemplateGroup $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(EmailTemplateGroup $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist EmailTemplateGroup entity in DB on behalf of system user.
     * @param EmailTemplateGroup $entity
     */
    public function saveWithSystemModifier(EmailTemplateGroup $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist EmailTemplateGroup entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param EmailTemplateGroup $entity
     */
    public function forceSave(EmailTemplateGroup $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist EmailTemplateGroup entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param EmailTemplateGroup $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(EmailTemplateGroup $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete EmailTemplateGroup entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param EmailTemplateGroup $entity
     */
    public function delete(EmailTemplateGroup $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete EmailTemplateGroup entity on behalf of respective modifier user.
     * @param EmailTemplateGroup $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(EmailTemplateGroup $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete EmailTemplateGroup entity on behalf of system user.
     * @param EmailTemplateGroup $entity
     */
    public function deleteWithSystemModifier(EmailTemplateGroup $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
