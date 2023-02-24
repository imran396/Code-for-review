<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\PhoneBidderDedicatedClerk;

use PhoneBidderDedicatedClerk;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;

abstract class AbstractPhoneBidderDedicatedClerkWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist PhoneBidderDedicatedClerk entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param PhoneBidderDedicatedClerk $entity
     */
    public function save(PhoneBidderDedicatedClerk $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist PhoneBidderDedicatedClerk entity in DB on behalf of respective modifier user.
     * @param PhoneBidderDedicatedClerk $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(PhoneBidderDedicatedClerk $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist PhoneBidderDedicatedClerk entity in DB on behalf of system user.
     * @param PhoneBidderDedicatedClerk $entity
     */
    public function saveWithSystemModifier(PhoneBidderDedicatedClerk $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist PhoneBidderDedicatedClerk entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param PhoneBidderDedicatedClerk $entity
     */
    public function forceSave(PhoneBidderDedicatedClerk $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist PhoneBidderDedicatedClerk entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param PhoneBidderDedicatedClerk $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(PhoneBidderDedicatedClerk $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete PhoneBidderDedicatedClerk entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param PhoneBidderDedicatedClerk $entity
     */
    public function delete(PhoneBidderDedicatedClerk $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete PhoneBidderDedicatedClerk entity on behalf of respective modifier user.
     * @param PhoneBidderDedicatedClerk $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(PhoneBidderDedicatedClerk $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete PhoneBidderDedicatedClerk entity on behalf of system user.
     * @param PhoneBidderDedicatedClerk $entity
     */
    public function deleteWithSystemModifier(PhoneBidderDedicatedClerk $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
