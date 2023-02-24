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
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Sms\Template\Placeholder\LotItem\Internal\PlaceholderKey;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholder;

/**
 * Class NoPlaceholderRenderer
 * @package Sam\Sms\Template\Placeholder\LotItem\Internal\Render
 * @internal
 */
class NoPlaceholderRenderer extends CustomizableClass implements PlaceholderRendererInterface
{
    use LotRendererAwareTrait;

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
        return [PlaceholderKey::ITEM_NO];
    }

    public function render(SmsTemplatePlaceholder $placeholder, LotItem $lotItem): string
    {
        if ($placeholder->key !== PlaceholderKey::ITEM_NO) {
            throw new InvalidArgumentException("Not applicable placeholder '{$placeholder->key}'");
        }

        return $this->getLotRenderer()->renderItemNo($lotItem);
    }
}
