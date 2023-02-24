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

use JsonException;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SmsActionQueueData
 * @package Sam\Sms\ActionQueue
 */
class SmsActionQueueData extends CustomizableClass
{
    public readonly int $accountId;
    public readonly string $message;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(int $accountId, string $message): static
    {
        $this->accountId = $accountId;
        $this->message = $message;
        return $this;
    }

    public function serialize(): string
    {
        return json_encode(
            [
                'accountId' => $this->accountId,
                'message' => $this->message,
            ],
            JSON_THROW_ON_ERROR
        );
    }

    public function deserialize(string $json): static
    {
        try {
            $data = json_decode($json, true, 2, JSON_THROW_ON_ERROR);
            return self::new()->construct((int)$data['accountId'], (string)$data['message']);
        } catch (JsonException) { //For consistency with legacy  TODO: Remove at v3.7
            $data = @unserialize($json);
            return self::new()->construct((int)$data['AccountId'], (string)$data['Message']);
        }
    }
}
