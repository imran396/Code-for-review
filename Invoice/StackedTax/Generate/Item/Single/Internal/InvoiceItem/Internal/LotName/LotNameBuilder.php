<?php
/**
 * Builds lot name for invoiced item. Lot description may be used in case of INVOICE_ITEM_DESCRIPTION account setting is on.
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Item\Single\Internal\InvoiceItem\Internal\LotName;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class LotNameBuilder
 * @package Sam\Invoice\StackedTax\Generate\Item\Single\Internal\LotName
 */
class LotNameBuilder extends CustomizableClass
{
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $lotName
     * @param string $lotDescription
     * @param int $accountId
     * @return string
     */
    public function build(string $lotName, string $lotDescription, int $accountId): string
    {
        $output = '';
        if ($this->getSettingsManager()->get(Constants\Setting::INVOICE_ITEM_DESCRIPTION, $accountId)) {
            $output = strip_tags($lotDescription);
            $output = html_entity_decode($output, ENT_QUOTES, 'UTF-8');
            $output = htmlspecialchars_decode($output, ENT_QUOTES);
            $output = trim($output);
        }
        if ($output === '') {
            $output = $lotName;
        }
        return $output;
    }

}
