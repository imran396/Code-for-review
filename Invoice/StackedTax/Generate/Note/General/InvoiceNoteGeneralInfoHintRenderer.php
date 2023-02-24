<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01.08.2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\StackedTax\Generate\Note\General;

use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceNoteGeneralInfoHintRenderer
 * @package Sam\Invoice\StackedTax\Generate\Note\General
 */
class InvoiceNoteGeneralInfoHintRenderer extends CustomizableClass
{
    public static array $placeholderKeys = [
        'first_name',
        'last_name',
        'username',
        'password',
        'invoice_total',
        'invoice_number',
        'invoice_url',
        'currency',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $html = '';
        foreach (self::$placeholderKeys as $view) {
            $html .= '{' . $view . '}' . '<br />';
        }
        return $html;
    }
}
