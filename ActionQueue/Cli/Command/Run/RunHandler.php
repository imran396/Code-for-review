<?php
/**
 * SAM-9809:  Refactor Action Queue Module
 * https://bidpath.atlassian.net/browse/SAM-9809
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\ActionQueue\Cli\Command\Run;

use QCallerException;
use QUndefinedPrimaryKeyException;
use Sam\ActionQueue\ActionQueueProcessor;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\LocalFileManager;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class RunHandler
 * @package Sam\ActionQueue\Cli\Command\Run
 */
class RunHandler extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function handle(): void
    {
        $ts = microtime(true);
        $this->cfg()->set('core->filesystem->managerClass', LocalFileManager::class);
        $executionTime = $this->cfg()->get('core->actionQueue->executionTime');
        $timeLeft = (int)floor($executionTime - (microtime(true) - $ts));
        $actionQueue = ActionQueueProcessor::new();
        try {
            $processResult = $actionQueue->run($timeLeft);
            $stats = 'Ok: ' . $processResult->processedEvents;
            if ($processResult->processedEvents) {
                $stats .= '(' . $processResult->processedEventsHigh
                    . '/' . $processResult->processedEventsMedium
                    . '/' . $processResult->processedEventsLow . ')';
            }
            $stats .= '; Failed: ' . $processResult->failedEvents;
            if ($processResult->failedEvents) {
                $stats .= '(' . $processResult->failedEventsHigh
                    . '/' . $processResult->failedEventsMedium
                    . '/' . $processResult->failedEventsLow . ')';
            }
            log_info($stats);
            log_info(composeSuffix(['Execution time' => (microtime(true) - $ts) . 's']));
        } catch (QUndefinedPrimaryKeyException|QCallerException $e) {
            log_error($e->getMessage());
        }
    }
}
