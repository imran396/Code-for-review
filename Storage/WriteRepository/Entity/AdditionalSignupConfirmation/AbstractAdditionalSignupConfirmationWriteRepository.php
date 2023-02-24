<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AdditionalSignupConfirmation;

use AdditionalSignupConfirmation;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractAdditionalSignupConfirmationWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist AdditionalSignupConfirmation entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param AdditionalSignupConfirmation $entity
     */
    public function save(AdditionalSignupConfirmation $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist AdditionalSignupConfirmation entity in DB on behalf of respective modifier user.
     * @param AdditionalSignupConfirmation $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(AdditionalSignupConfirmation $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist AdditionalSignupConfirmation entity in DB on behalf of system user.
     * @param AdditionalSignupConfirmation $entity
     */
    public function saveWithSystemModifier(AdditionalSignupConfirmation $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist AdditionalSignupConfirmation entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AdditionalSignupConfirmation $entity
     */
    public function forceSave(AdditionalSignupConfirmation $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist AdditionalSignupConfirmation entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param AdditionalSignupConfirmation $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(AdditionalSignupConfirmation $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AdditionalSignupConfirmation entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param AdditionalSignupConfirmation $entity
     */
    public function delete(AdditionalSignupConfirmation $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete AdditionalSignupConfirmation entity on behalf of respective modifier user.
     * @param AdditionalSignupConfirmation $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(AdditionalSignupConfirmation $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete AdditionalSignupConfirmation entity on behalf of system user.
     * @param AdditionalSignupConfirmation $entity
     */
    public function deleteWithSystemModifier(AdditionalSignupConfirmation $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
