<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder;

use Sam\Core\Service\CustomizableClass;

/**
 * Class SmsTemplatePlaceholder
 * @package Sam\Sms\Template\Placeholder
 */
class SmsTemplatePlaceholder extends CustomizableClass
{
    public readonly string $view;
    public readonly string $key;
    public readonly ?string $clarification;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(string $view, string $key, ?string $clarification): static
    {
        $this->view = $view;
        $this->key = $key;
        $this->clarification = $clarification;
        return $this;
    }
}
