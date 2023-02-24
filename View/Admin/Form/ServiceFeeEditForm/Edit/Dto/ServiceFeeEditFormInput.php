<?php
/**
 * SAM-11110: Stacked Tax. New Invoice Edit page: Service Fee Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Class ServiceFeeEditFormInputDto
 * @package Sam\View\Admin\Form\ServiceFeeEditForm\Edit
 */
class ServiceFeeEditFormInput extends CustomizableClass
{
    public readonly ?int $invoiceAdditionalId;
    public readonly ?int $taxSchemaId;
    public readonly ?int $type;
    public readonly int $editorUserId;
    public readonly int $invoiceAccountId;
    public readonly int $invoiceId;
    public readonly string $amount;
    public readonly string $language;
    public readonly string $name;
    public readonly string $note;
    public readonly bool $isReadOnlyDb;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        string $amount,
        string $name,
        string $note,
        ?int $taxSchemaId,
        ?int $type,
        ?int $invoiceAdditionalId,
        int $invoiceId,
        int $invoiceAccountId,
        int $editorUserId,
        string $language,
        bool $isReadOnlyDb = false
    ): static {
        $this->amount = $amount;
        $this->editorUserId = $editorUserId;
        $this->invoiceAccountId = $invoiceAccountId;
        $this->invoiceAdditionalId = $invoiceAdditionalId;
        $this->invoiceId = $invoiceId;
        $this->isReadOnlyDb = $isReadOnlyDb;
        $this->language = $language;
        $this->name = $name;
        $this->note = $note;
        $this->taxSchemaId = $taxSchemaId;
        $this->type = $type;
        return $this;
    }
}
