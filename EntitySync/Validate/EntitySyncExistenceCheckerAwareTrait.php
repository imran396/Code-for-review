<?php

namespace Sam\EntitySync\Validate;

/**
 * Trait EntitySyncExistenceCheckerAwareTrait
 * @package Sam\EntitySync\Validate
 */
trait EntitySyncExistenceCheckerAwareTrait
{
    /**
     * @var EntitySyncExistenceChecker|null
     */
    protected ?EntitySyncExistenceChecker $entitySyncExistenceChecker = null;

    /**
     * @return EntitySyncExistenceChecker
     */
    protected function getEntitySyncExistenceChecker(): EntitySyncExistenceChecker
    {
        if ($this->entitySyncExistenceChecker === null) {
            $this->entitySyncExistenceChecker = EntitySyncExistenceChecker::new();
        }
        return $this->entitySyncExistenceChecker;
    }

    /**
     * @param EntitySyncExistenceChecker $entitySyncExistenceChecker
     * @return static
     * @internal
     */
    public function setEntitySyncExistenceChecker(EntitySyncExistenceChecker $entitySyncExistenceChecker): static
    {
        $this->entitySyncExistenceChecker = $entitySyncExistenceChecker;
        return $this;
    }
}
