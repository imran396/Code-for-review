<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\MailingListTemplates;

use MailingListTemplates;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractMailingListTemplatesWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist MailingListTemplates entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param MailingListTemplates $entity
     */
    public function save(MailingListTemplates $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist MailingListTemplates entity in DB on behalf of respective modifier user.
     * @param MailingListTemplates $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(MailingListTemplates $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist MailingListTemplates entity in DB on behalf of system user.
     * @param MailingListTemplates $entity
     */
    public function saveWithSystemModifier(MailingListTemplates $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist MailingListTemplates entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param MailingListTemplates $entity
     */
    public function forceSave(MailingListTemplates $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist MailingListTemplates entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param MailingListTemplates $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(MailingListTemplates $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete MailingListTemplates entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param MailingListTemplates $entity
     */
    public function delete(MailingListTemplates $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete MailingListTemplates entity on behalf of respective modifier user.
     * @param MailingListTemplates $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(MailingListTemplates $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete MailingListTemplates entity on behalf of system user.
     * @param MailingListTemplates $entity
     */
    public function deleteWithSystemModifier(MailingListTemplates $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
