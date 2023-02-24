<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\OutbidBidder;

use Sam\Core\Service\CustomizableClass;
use Sam\Sms\Template\Placeholder\OutbidBidder\Internal\Load\DataProviderCreateTrait;
use Sam\Sms\Template\Placeholder\OutbidBidder\Internal\PlaceholderKey;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholder;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholderRendererInterface;
use User;

/**
 * Class OutbidBidderPlaceholderRenderer
 * @package Sam\Sms\Template\Placeholder\OutbidBidder
 */
class OutbidBidderPlaceholderRenderer extends CustomizableClass implements SmsTemplatePlaceholderRendererInterface
{
    use DataProviderCreateTrait;
    use OutbidBidderPlaceholderKeyProviderCreateTrait;

    protected User $outbidUser;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(User $outbidUser): static
    {
        $this->outbidUser = $outbidUser;
        return $this;
    }

    public function getApplicablePlaceholderKeys(): array
    {
        return $this->createOutbidBidderPlaceholderKeyProvider()->getKeys();
    }

    public function render(SmsTemplatePlaceholder $placeholder): string
    {
        return match ($placeholder->key) {
            PlaceholderKey::USER_CUSTOMER_NO => (string)$this->outbidUser->CustomerNo,
            PlaceholderKey::USER_ID => (string)$this->outbidUser->Id,
            PlaceholderKey::USER_PHONE => $this->createDataProvider()->loadUserPhone($this->outbidUser->Id),
            default => throw new \InvalidArgumentException("Not applicable placeholder '{$placeholder->key}'"),
        };
    }
}
