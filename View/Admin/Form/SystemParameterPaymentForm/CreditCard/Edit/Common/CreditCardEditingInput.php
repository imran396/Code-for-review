<?php
/**
 * SAM-10335: Allow to adjust CC surcharge per account: Implementation (Dev)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SystemParameterPaymentForm\CreditCard\Edit\Common;

use Sam\Core\Service\CustomizableClass;


class CreditCardEditingInput extends CustomizableClass
{
    public CreditCardEditingMode $mode;
    public ?int $creditCardId;
    public string $name;
    public string $surcharge;
    public int $systemAccountId;
    public ?int $editorUserId;


    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param CreditCardEditingMode $mode
     * @param int|null $creditCardId
     * @param string $name
     * @param string $surcharge
     * @param int $systemAccountId
     * @param int|null $editorUserId
     * @return $this
     */
    public function construct(
        CreditCardEditingMode $mode,
        ?int $creditCardId,
        string $name,
        string $surcharge,
        int $systemAccountId,
        ?int $editorUserId
    ): static {
        $this->mode = $mode;
        $this->creditCardId = $creditCardId;
        $this->name = $name;
        $this->surcharge = $surcharge;
        $this->systemAccountId = $systemAccountId;
        $this->editorUserId = $editorUserId;
        return $this;
    }
}

