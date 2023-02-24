<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\MailingListTemplateCategories;

use MailingListTemplateCategories;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractMailingListTemplateCategoriesWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist MailingListTemplateCategories entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param MailingListTemplateCategories $entity
     */
    public function save(MailingListTemplateCategories $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist MailingListTemplateCategories entity in DB on behalf of respective modifier user.
     * @param MailingListTemplateCategories $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(MailingListTemplateCategories $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist MailingListTemplateCategories entity in DB on behalf of system user.
     * @param MailingListTemplateCategories $entity
     */
    public function saveWithSystemModifier(MailingListTemplateCategories $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist MailingListTemplateCategories entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param MailingListTemplateCategories $entity
     */
    public function forceSave(MailingListTemplateCategories $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist MailingListTemplateCategories entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param MailingListTemplateCategories $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(MailingListTemplateCategories $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete MailingListTemplateCategories entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param MailingListTemplateCategories $entity
     */
    public function delete(MailingListTemplateCategories $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete MailingListTemplateCategories entity on behalf of respective modifier user.
     * @param MailingListTemplateCategories $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(MailingListTemplateCategories $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete MailingListTemplateCategories entity on behalf of system user.
     * @param MailingListTemplateCategories $entity
     */
    public function deleteWithSystemModifier(MailingListTemplateCategories $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
