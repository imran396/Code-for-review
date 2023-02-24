<?php
/**
 * SAM-4465 : Refactor reminder classes
 * https://bidpath.atlassian.net/browse/SAM-4465
 *
 * @author        Imran Rahman
 * @version       SVN: $Id: $
 * @since         Sept 30, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 */

namespace Sam\Reminder\Concrete\Registration\Internal\Process;

use Auction;
use Email_Template;
use Exception;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Reminder\Common\Dto\ProcessingInput;
use Sam\Reminder\Common\Dto\ProcessingResult;
use Sam\Reminder\Common\Expiry\Validate\ExpirationDateChecker;
use Sam\Reminder\Common\Render\Renderer;
use Sam\Reminder\Concrete\Registration\Internal\Load\DataProviderCreateTrait;
use User;

/**
 * Class Processor
 * @package Sam\Reminder\Concrete\Registration
 */
class Processor extends CustomizableClass
{
    use AuctionRendererAwareTrait;
    use DataProviderCreateTrait;

    /** @var int|null */
    protected ?int $lastProcessedAuctionId = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param ProcessingInput $input
     * @return ProcessingResult
     */
    public function process(ProcessingInput $input): ProcessingResult
    {
        $result = ProcessingResult::new();
        $dataProvider = $this->createDataProvider();
        $templateName = Constants\EmailKey::REGISTRATION_REMINDER;
        $name = Renderer::new()->makeNameFromEmailKey($templateName);

        $hasLastRun = ExpirationDateChecker::new()->checkLastRun(
            $input->currentDateUtc,
            $input->lastRunDateUtc,
            $input->scriptInterval,
            $name
        );
        if (!$hasLastRun) {
            return $result->enableProcessed(false);
        }

        $currentDateUtcIso = $input->currentDateUtc->format(Constants\Date::ISO);
        $result->lastRunUtc = $input->currentDateUtc;
        try {
            $lastRunWithoutScriptInterval = ExpirationDateChecker::new()->getLastRun(
                $input->currentDateUtc,
                $input->lastRunDateUtc,
                $input->scriptInterval
            );
            $data = $dataProvider->loadReminderData($lastRunWithoutScriptInterval, $currentDateUtcIso, $input->scriptInterval);
            foreach ($data as $row) {
                $auction = $dataProvider->loadAuction((int)$row['auc_id']);
                if (!$auction) {
                    log_error('Available auction cannot be found by id' . composeSuffix(['a' => $row['auc_id']]));
                    continue;
                }

                $user = $dataProvider->loadUser((int)$row['user_id']);
                if (!$user) {
                    log_error('Available user cannot be found by id' . composeSuffix(['u' => $row['user_id']]));
                    continue;
                }

                $editorUserId = $dataProvider->loadEditorUserId();
                $this->createReminder($auction, $user, $templateName, $editorUserId, $result);
            }
        } catch (Exception $e) {
            log_error("Error while processing " . $name . " reminders: " . $e->getMessage());
        }
        return $result->enableProcessed(true);
    }

    /**
     * Determine who needs to be reminded, create emails and drop in action queue
     * @param Auction $auction
     * @param User $user
     * @param string $templateName
     * @param int $editorUserId
     * @param ProcessingResult $result
     */
    protected function createReminder(
        Auction $auction,
        User $user,
        string $templateName,
        int $editorUserId,
        ProcessingResult $result
    ): void {
        if (!$user->Email) {
            $saleNo = $this->getAuctionRenderer()->renderSaleNo($auction);
            $logData = [
                'username' => $user->Username,
                'u' => $user->Id,
                'auction#' => $saleNo,
                'a' => $auction->Id,
                'acc' => $auction->AccountId,
            ];
            log_info('Ignoring user because of missing email' . composeSuffix($logData));
            return;
        }
        $emailManager = Email_Template::new()->construct(
            $auction->AccountId,
            $templateName,
            $editorUserId,
            [$user, $auction],
            $auction->Id
        );
        if ($emailManager->EmailTpl->Disabled) {
            $logData = [
                'acc' => $auction->AccountId,
                'i' => $auction->Id,
            ];
            $message = Renderer::new()->makeNameFromEmailKey($templateName) . " reminder email is disabled";
            log_info($message . composeSuffix($logData));
            return;
        }
        $emailManager->addToActionQueue(Constants\ActionQueue::LOW);
        // Update process stats
        $result->countRemindedUsers++;
        if ($this->lastProcessedAuctionId !== $auction->Id) {
            $result->countAuctions++;
        }
        $this->lastProcessedAuctionId = $auction->Id;
        $saleNo = $this->getAuctionRenderer()->renderSaleNo($auction);
        $logData = [
            'username' => $user->Username,
            'u' => $user->Id,
            'email' => $user->Email,
            'auction# ' => $saleNo,
            'a' => $auction->Id,
            'acc' => $auction->AccountId,
        ];
        log_info('Created registration reminder for user' . composeSuffix($logData));
    }
}
