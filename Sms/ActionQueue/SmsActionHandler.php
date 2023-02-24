<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\ActionQueue;

use ActionQueue;
use Exception;
use Sam\ActionQueue\Base\ActionQueueHandlerInterface;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Net\HttpClientCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class SmsActionHandler
 * @package Sam\Sms\ActionQueue
 */
class SmsActionHandler extends CustomizableClass implements ActionQueueHandlerInterface
{
    use HttpClientCreateTrait;
    use SettingsManagerAwareTrait;

    protected const TIMEOUT = 5;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Send email
     * @param ActionQueue $actionQueue
     * @return bool
     * @see ActionQueueHandlerInterface::process()
     */
    public function process(ActionQueue $actionQueue): bool
    {
        $data = SmsActionQueueData::new()->deserialize($actionQueue->Data);

        if (!$this->isTextMsgEnabled($data->accountId)) {
            log_info("Sending text message was not enabled on account" . composeSuffix(['acc' => $data->accountId]));
            return false;
        }

        $attemptNum = $actionQueue->Attempts + 1;
        try {
            $this->send($data);
            return true;
        } catch (Exception) {
            log_warning('Attempt failed to send sms' . composeSuffix(['att' => $attemptNum]));
            return false;
        }
    }

    protected function send(SmsActionQueueData $data): void
    {
        $postData = $this->makePostData($data);
        $postUrl = $this->getTextMsgApiUrl($data->accountId);
        log_info(
            "Sending post data: {$postData}"
            . composeSuffix(
                [
                    'accountId' => $data->accountId,
                    'POSTingUrl' => $postUrl
                ]
            )
        );
        $result = $this->createHttpClient()
            ->post($postUrl, $postData, [], self::TIMEOUT)
            ->getBody()
            ->getContents();
        log_debug("Result: {$result}");
    }

    protected function makePostData(SmsActionQueueData $data): string
    {
        $postVar = $this->getTextMsgApiPostVar($data->accountId);
        return urlencode($postVar) . '=' . urlencode($data->message);
    }

    protected function isTextMsgEnabled(int $accountId): bool
    {
        return (bool)$this->getSettingsManager()->get(Constants\Setting::TEXT_MSG_ENABLED, $accountId);
    }

    public function getTextMsgApiPostVar(int $accountId): string
    {
        return $this->getSettingsManager()->get(Constants\Setting::TEXT_MSG_API_POST_VAR, $accountId);
    }

    public function getTextMsgApiUrl(int $accountId): string
    {
        return (string)$this->getSettingsManager()->get(Constants\Setting::TEXT_MSG_API_URL, $accountId);
    }
}
