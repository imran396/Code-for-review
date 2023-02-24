<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\LotItem\Internal\Render;

use InvalidArgumentException;
use LotItem;
use Sam\Core\Service\CustomizableClass;
use Sam\Sms\Template\Placeholder\LotItem\Internal\PlaceholderKey;
use Sam\Sms\Template\Placeholder\LotItem\Internal\Render\Internal\Load\DataProviderAwareTrait;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholder;

/**
 * Class ConsignorPlaceholderRenderer
 * @package Sam\Sms\Template\Placeholder\LotItem\Internal\Render
 * @internal
 */
class ConsignorPlaceholderRenderer extends CustomizableClass implements PlaceholderRendererInterface
{
    use DataProviderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getApplicablePlaceholderKeys(): array
    {
        return [PlaceholderKey::CONSIGNOR];
    }

    public function render(SmsTemplatePlaceholder $placeholder, LotItem $lotItem): string
    {
        if ($placeholder->key !== PlaceholderKey::CONSIGNOR) {
            throw new InvalidArgumentException("Not applicable placeholder '{$placeholder->key}'");
        }

        return $this->getDataProvider()->loadUsername($lotItem->ConsignorId);
    }
}
