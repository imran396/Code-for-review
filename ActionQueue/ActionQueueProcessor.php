<?php

/**
 * It process action queue data based on priority by action handler. Priorities are high, medium, low.
 * Execution time for high priority task is 60% and mid priority task is 30% and low priority task is 10%
 *
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
use Exception;
use Sam\ActionQueue\Internal\Dto\ProcessingResult;
use Sam\ActionQueue\Internal\Load\DataProviderCreateTrait;
use Sam\ActionQueue\Internal\Save\DataSaverCreateTrait;
use Sam\Core\Constants;
use QOptimisticLockingException;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\User\Encryption\UserCustomFieldEncryptionActionHandler;
use Sam\Email\Queue\EmailActionHandler;
use Sam\Lot\Category\Order\GlobalOrderUpdateActionHandler;
use Sam\Sms\ActionQueue\SmsActionHandler;

/**
 * Class ActionQueueProcessor
 * @package Sam\ActionQueue
 */
class ActionQueueProcessor extends CustomizableClass
{
    use DataProviderCreateTrait;
    use DataSaverCreateTrait;

    private const ERR_CLASS_NOT_FOUND = 1;          // Handler class not found
    private const ERR_IMPLEMENTATION_PROBLEM = 2;   // Handler class implemented incorrectly
    private const ERR_PROCESSING_PROBLEM = 3;       // Exception thrown while handler is processing
    private const ERR_OLC_ON_SAVE = 4;              // Optimistic Locking Constraint on ActionQueue save

    private const HIGH_PRIORITY_MULTIPLIER = .6;
    private const MID_PRIORITY_MULTIPLIER = .9;

    /**
     * Return an instance of ActionQueue_Cron
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Handle the ActionQueue
     * 60% execution time for high priority
     * 30% for medium priority
     * 10% for low priority actions
     *
     * @param int $maxExecutionSeconds Time left for handling actions
     * @return ProcessingResult
     */
    public function run(int $maxExecutionSeconds): ProcessingResult
    {
        $result = ProcessingResult::new();
        $time = time();
        // threshold time for high priority tasks
        $timeHigh = $maxExecutionSeconds * self::HIGH_PRIORITY_MULTIPLIER;
        // threshold time for medium priority tasks
        $timeMed = $maxExecutionSeconds * self::MID_PRIORITY_MULTIPLIER;
        $priority = Constants\ActionQueue::HIGH;
        // get ActionQueue_Manager
        log_debug(
            composeSuffix(
                [
                    'TS' => $time,
                    'MaxExec' => $maxExecutionSeconds,
                    'TH' => $timeHigh,
                    'TM' => $timeHigh,
                ]
            )
        );

        // loop while max execution time is not exceeded
        while ($time + $maxExecutionSeconds >= time()) {
            // fetch event for the current priority
            // or lower if there are none or
            // higher if there are no lower priority event
            $actionQueue = $this->createDataProvider()->fetchFromQueue($priority);
            // we are done with this iteration
            if ($actionQueue === null) {
                return $result;
            }

            if (!$this->isAbleToProcessActionQueue($actionQueue)) {
                $result->failedEvents++;
                $this->setFailedPriority($actionQueue, $result);
                $this->createDataSaver()->deleteWithSystemModifier($actionQueue);
                $this->logActionHandlerClassProblem($actionQueue);
            } else {
                $result = $this->process($actionQueue, $result);
            }

            $dTime = time() - $time;
            log_debug(composeSuffix(['DT' => $dTime, 'TH' => $timeHigh, 'TM' => $timeHigh]));

            // handle low priority events for the remaining time
            if ($dTime >= $timeMed) {
                $priority = Constants\ActionQueue::LOW;
            } // or handle medium priority events for the remaining time
            elseif ($dTime > $timeHigh) {
                $priority = Constants\ActionQueue::MEDIUM;
            }
        }
        // return the number of successfully handled events
        return $result;
    }

    /**
     * Checking customizable auctionHandler class and it's process method existence.
     * @param ActionQueue $actionQueue
     * @return bool
     */
    protected function isAbleToProcessActionQueue(ActionQueue $actionQueue): bool
    {
        $actionHandlerClass = $actionQueue->ActionHandler;
        $isFoundClass = class_exists($actionHandlerClass);
        $isSubclassOfCustomizableClass = is_subclass_of($actionHandlerClass, CustomizableClass::class);
        $isFoundProcessMethod = method_exists($actionHandlerClass, 'process');

        $isAble = $isFoundClass && $isSubclassOfCustomizableClass && $isFoundProcessMethod;
        return $isAble;
    }

    /**
     * Set priority based failed event counter.
     * @param ActionQueue $actionQueue
     * @param ProcessingResult $result
     * @return void
     */
    protected function setFailedPriority(ActionQueue $actionQueue, ProcessingResult $result): void
    {
        switch ($actionQueue->Priority) {
            case Constants\ActionQueue::HIGH:
                $result->failedEventsHigh++;
                break;
            case Constants\ActionQueue::MEDIUM:
                $result->failedEventsMedium++;
                break;
            case Constants\ActionQueue::LOW:
                $result->failedEventsLow++;
                break;
        }
    }

    /**
     * Set priority based success event counter.
     * @param ActionQueue $actionQueue
     * @param ProcessingResult $result
     * @return void
     */
    protected function setProcessedPriority(ActionQueue $actionQueue, ProcessingResult $result): void
    {
        switch ($actionQueue->Priority) {
            case Constants\ActionQueue::HIGH:
                $result->processedEventsHigh++;
                break;
            case Constants\ActionQueue::MEDIUM:
                $result->processedEventsMedium++;
                break;
            case Constants\ActionQueue::LOW:
                $result->processedEventsLow++;
                break;
        }
    }

    /**
     * @param ActionQueue $actionQueue
     * @return void
     */
    protected function logActionHandlerClassProblem(ActionQueue $actionQueue): void
    {
        $isFoundClass = class_exists($actionQueue->ActionHandler);
        $errorType = $isFoundClass ? self::ERR_IMPLEMENTATION_PROBLEM : self::ERR_CLASS_NOT_FOUND;
        $this->log($errorType, $actionQueue);
    }

    /**
     * Process action queue by event handler and update attempts and track statistics.
     * @param ActionQueue $actionQueue
     * @param ProcessingResult $result
     * @return ProcessingResult
     */
    protected function process(ActionQueue $actionQueue, ProcessingResult $result): ProcessingResult
    {
        $isProcessed = false;
        try {
            // execute the action handler
            /** @var SmsActionHandler|EmailActionHandler|UserCustomFieldEncryptionActionHandler|GlobalOrderUpdateActionHandler $actionHandlerClass */
            $actionHandlerClass = $actionQueue->ActionHandler;
            $actionHandler = call_user_func([$actionHandlerClass, 'new']);
            $isProcessed = $actionHandler->process($actionQueue);
        } catch (Exception $e) {
            $this->log(self::ERR_PROCESSING_PROBLEM, $actionQueue, $e->getMessage());
        }
        if (!$isProcessed) {
            // attempt failed
            $this->saveAttempts($actionQueue);
            $result->failedEvents++;
            $this->setFailedPriority($actionQueue, $result);
        } else {
            $result->processedEvents++;
            $this->setProcessedPriority($actionQueue, $result);
            // Remove successful action event
            $this->createDataSaver()->deleteWithSystemModifier($actionQueue);
        }
        return $result;
    }

    /**
     * Update attempts, considering Optimistic Locking Constraint
     * @param ActionQueue $actionQueue
     * @return void
     */
    protected function saveAttempts(ActionQueue $actionQueue): void
    {
        $actionQueue->Attempts++;
        try {
            $this->createDataSaver()->saveWithSystemModifier($actionQueue);
        } catch (QOptimisticLockingException) {
            $this->log(self::ERR_OLC_ON_SAVE, $actionQueue);
            $actionQueue->Reload();
            // increment Attempts again, since we lost that with the reload
            $actionQueue->Attempts++;
            $this->createDataSaver()->saveWithSystemModifier($actionQueue);
        }
    }

    /**
     * Logging classified problems
     * @param int $type
     * @param ActionQueue $actionQueue
     * @param string $errorMessage
     * @return void
     */
    protected function log(int $type, ActionQueue $actionQueue, string $errorMessage = ''): void
    {
        switch ($type) {
            case self::ERR_CLASS_NOT_FOUND:
                // action handler class is not defined, drop the event
                log_warning(sprintf('Action handler class %s does not exist', $actionQueue->ActionHandler));
                break;

            case self::ERR_IMPLEMENTATION_PROBLEM:
                // action handler class is not defined, drop the event
                // action handler class definition problem, drop the event
                log_warning(
                    sprintf(
                        'Action handler class %s does not extend from Customizable class or implement IActionQueueHandler interface',
                        $actionQueue->ActionHandler
                    )
                );
                break;

            case self::ERR_PROCESSING_PROBLEM:
                // an exception was thrown from within the action handler
                log_warning(
                    sprintf(
                        'Exception in Action handler %s for event %s: %s',
                        $actionQueue->ActionHandler,
                        $actionQueue->Identifier,
                        $errorMessage
                    )
                );
                break;

            case self::ERR_OLC_ON_SAVE:
                $message = 'Optimistic locking constraint for ActionQueue. Reloading and saving again'
                    . composeSuffix(
                        [
                            'aq' => $actionQueue->Id,
                            'handler' => $actionQueue->ActionHandler,
                            'Attempt' => $actionQueue->Attempts,
                        ]
                    );
                log_error($message);
                break;
        }
    }
}
