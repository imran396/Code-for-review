<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           26.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Generate\Note\General;

use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceNoteGeneralInfoHintRenderer
 * @package Sam\Invoice\Legacy\Generate\Note\General
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
