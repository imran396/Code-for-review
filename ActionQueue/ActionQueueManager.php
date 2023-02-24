<?php

/**
 * SAM-4451 : Apply Action Queue processor and manager
 * https://bidpath.atlassian.net/browse/SAM-4451
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Sept 23, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\ActionQueue;

use ActionQueue;
use OutOfRangeException;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\ReadRepository\Entity\ActionQueue\ActionQueueReadRepository;
use Sam\Storage\ReadRepository\Entity\ActionQueue\ActionQueueReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\ActionQueue\ActionQueueWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class ActionQueueManager
 * @package Sam\ActionQueue
 */
class ActionQueueManager extends CustomizableClass
{
    use ActionQueueReadRepositoryCreateTrait;
    use ActionQueueWriteRepositoryAwareTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use UserLoaderAwareTrait;

    /**
     * Get the singleton instance of ActionQueue_Manager
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Add action to queue
     *
     * @param string $actionHandler class implementing IActionQueueHandler
     * @param string $data data for ActionHandler. Serialized or JSON or blob
     * @param int $editorUserId user.id triggering this action
     * @param string|null $identifier optional unique identifier for this action for this ActionHandler. Default null
     * @param int|null $groupId optional group identifier for this action
     * @param int $priority default ActionQueue::MEDIUM, or ActionQueue::LOW or ActionQueue::HIGH
     * @param int $maxAttempts default 1. Max number of attempts
     */
    public function addToQueue(
        string $actionHandler,
        string $data,
        int $editorUserId,
        ?string $identifier = null,
        ?int $groupId = null,
        int $priority = Constants\ActionQueue::MEDIUM,
        int $maxAttempts = 1
    ): void {
        $actionQueue = $this->createEntityFactory()->actionQueue();
        $actionQueue->ActionHandler = $actionHandler;
        $actionQueue->Attempts = 0;
        $actionQueue->Data = $data;
        $actionQueue->Group = $groupId;
        $actionQueue->Identifier = $this->encodeIdentifier($identifier);
        $actionQueue->MaxAttempts = $maxAttempts;
        $actionQueue->Priority = $priority;
        $this->getActionQueueWriteRepository()->saveWithModifier($actionQueue, $editorUserId);
    }

    /**
     * Encode identifier
     * @param string|null $identifier
     * @return string
     */
    public function encodeIdentifier(?string $identifier): string
    {
        return sha1((string)$identifier);
    }

    /**
     * Fetch the last item from the queue with a particular priority
     * If there is no item available with that priority fetch
     * one with a lower priority
     * If there is no lower priority item, fetch one with a higher priority
     *
     * @param int $priority ActionQueue::LOW, ActionQueue::MEDIUM, ActionQueue::HIGH
     * @return ActionQueue|null
     * @throws OutOfRangeException
     */
    public function fetchFromQueue(int $priority): ?ActionQueue
    {
        if (!$this->isPriority($priority)) {
            throw new OutOfRangeException(sprintf('Not an ActionQueue priority: %s', $priority));
        }

        $repository = $this->prepareRepository()
            ->filterPriorityLessOrEqual($priority);

        if ($repository->count() > 0) {
            $actionQueue = $repository->loadEntity();
        } else {
            $actionQueue = $this->prepareRepository()
                ->filterPriorityGreater($priority)
                ->loadEntity();
        }

        return $actionQueue;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return ActionQueueReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb = false): ActionQueueReadRepository
    {
        $repository = $this->createActionQueueReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAttemptsLowerThenMaxAttempts()
            ->orderByPriority(false)
            ->orderByAttempts()
            ->orderByCreatedOn(false);
        return $repository;
    }

    /**
     * Load ActionQueue task by unique identifier.
     *
     * @param string $identifier encoded (sha1) identifier
     * @param bool $active true if action was not executed all attempts, false otherwise
     * @param bool $encode true if it's necessary to encode identifier, false otherwise
     * @return ActionQueue|null
     */
    public function loadByIdentifier(string $identifier, bool $active = false, bool $encode = false): ?ActionQueue
    {
        if ($encode) {
            $identifier = $this->encodeIdentifier($identifier);
        }
        $repository = $this->createActionQueueReadRepository()
            ->filterIdentifier($identifier)
            ->orderById();
        if ($active) {
            $repository = $repository->filterAttemptsLowerThenMaxAttempts();
        }
        return $repository->loadEntity();
    }

    /**
     * Remove pending task from actionQueue.
     *
     * @param ActionQueue $actionQueue
     * @param int $editorUserId
     */
    public function removeFromQueue(ActionQueue $actionQueue, int $editorUserId): void
    {
        if ($actionQueue->Attempts < $actionQueue->MaxAttempts) {
            $this->getActionQueueWriteRepository()->deleteWithModifier($actionQueue, $editorUserId);
        }
    }

    /**
     * Check whether a parameter matches a priority
     *
     * @param int $priority
     * @return bool
     */
    public function isPriority(int $priority): bool
    {
        $is = in_array($priority, Constants\ActionQueue::$priorities, true);
        return $is;
    }
}
