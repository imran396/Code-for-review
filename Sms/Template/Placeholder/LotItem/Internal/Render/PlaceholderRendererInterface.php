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

use LotItem;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholder;

/**
 * Interface PlaceholderRendererInterface
 * @package am\Sms\Template\Placeholder\LotItem\Render
 * @internal
 */
interface PlaceholderRendererInterface
{
    /**
     * @return string[]
     */
    public function getApplicablePlaceholderKeys(): array;

    /**
     * @param SmsTemplatePlaceholder $placeholder
     * @param LotItem $lotItem
     * @return string
     */
    public function render(SmsTemplatePlaceholder $placeholder, LotItem $lotItem): string;
}
