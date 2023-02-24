<?php
/**
 * <Description of class>
 *
 * SAM-11048: Replace GET->POST at Admin Manage invoice item Qcodo delete operation code to JavaScript code.
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 06, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceListForm\Delete;

use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Delete\Multiple\MultipleInvoiceDeleterCreateTrait;
use Sam\Qform\Messenger\AdminMessengerCreateTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class InvoiceListFormInvoiceDeleter
 * @package Sam\View\Admin\Form\InvoiceListForm\Delete
 */
class InvoiceListDeleter extends CustomizableClass
{
    use AdminMessengerCreateTrait;
    use EditorUserAwareTrait;
    use MultipleInvoiceDeleterCreateTrait;
    use SystemAccountAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        return $this;
    }

    /**
     * @param bool $shouldUnsoldLot
     * @param int[] $invoiceIds
     */
    public function deleteMultipleInvoice(array $invoiceIds, bool $shouldUnsoldLot): void
    {
        $multipleDeleter = $this->createMultipleInvoiceDeleter()
            ->construct(
                $invoiceIds,
                $shouldUnsoldLot,
                $this->getSystemAccountId(),
                $this->getEditorUserId()
            );
        if (!$multipleDeleter->validate()) {
            $this->createAdminMessenger()->addError($multipleDeleter->getErrorMessage());
            return;
        }
        $multipleDeleter->delete();
        $invoiceIdList = implode(',', $multipleDeleter->getDeletedNumbers());
        $this->createAdminMessenger()->addSuccess('Invoice [' . $invoiceIdList . '] has been deleted.');
    }

}
