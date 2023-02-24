<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SearchIndexFulltext;

use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SearchIndexFulltext;

abstract class AbstractSearchIndexFulltextWriteRepository extends WriteRepositoryBase
{
    /**
     * Persist SearchIndexFulltext entity in DB.
     * (!) Avoid this method call, because it doesn't define modifier user. Call saveWithModifier(), saveWithSystemModifier() instead.
     * @param SearchIndexFulltext $entity
     */
    public function save(SearchIndexFulltext $entity): void
    {
        $this->saveEntity($entity);
    }

    /**
     * Persist SearchIndexFulltext entity in DB on behalf of respective modifier user.
     * @param SearchIndexFulltext $entity
     * @param int $editorUserId
     */
    public function saveWithModifier(SearchIndexFulltext $entity, int $editorUserId): void
    {
        $this->saveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Persist SearchIndexFulltext entity in DB on behalf of system user.
     * @param SearchIndexFulltext $entity
     */
    public function saveWithSystemModifier(SearchIndexFulltext $entity): void
    {
        $this->saveEntityWithSystemModifier($entity);
    }

    /**
     * Persist SearchIndexFulltext entity in DB and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SearchIndexFulltext $entity
     */
    public function forceSave(SearchIndexFulltext $entity): void
    {
        $this->forceSaveEntity($entity);
    }

    /**
     * Persist SearchIndexFulltext entity in DB on behalf of modifier user and ignore Optimistic Locking check.
     * (!) If you have a reason to call this function, you MUST describe it by comment near the call.
     * @param SearchIndexFulltext $entity
     * @param int $editorUserId
     */
    public function forceSaveWithModifier(SearchIndexFulltext $entity, int $editorUserId): void
    {
        $this->forceSaveEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SearchIndexFulltext entity without specifying user who performs the action.
     * (!) Avoid this method call. Delete with help of deleteWithModifier(), deleteWithSystemModifier() instead.
     * @param SearchIndexFulltext $entity
     */
    public function delete(SearchIndexFulltext $entity): void
    {
        $this->deleteEntity($entity);
    }

    /**
     * Delete SearchIndexFulltext entity on behalf of respective modifier user.
     * @param SearchIndexFulltext $entity
     * @param int $editorUserId
     */
    public function deleteWithModifier(SearchIndexFulltext $entity, int $editorUserId): void
    {
        $this->deleteEntityWithModifier($entity, $editorUserId);
    }

    /**
     * Delete SearchIndexFulltext entity on behalf of system user.
     * @param SearchIndexFulltext $entity
     */
    public function deleteWithSystemModifier(SearchIndexFulltext $entity): void
    {
        $this->deleteEntityWithSystemModifier($entity);
    }
}
