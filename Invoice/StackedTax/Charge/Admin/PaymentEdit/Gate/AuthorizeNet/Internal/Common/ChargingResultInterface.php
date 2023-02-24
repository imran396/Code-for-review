<?php
/**
 * SAM-10915: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Authorize.Net invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 1, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\Internal\Common;

interface ChargingResultInterface
{
    public function addSuccess(int $code, ?string $message = null): self;

    public function addError(int $code, ?string $message = null): self;

    public function hasError(): bool;

    public function errorCodes(): array;

    public function errorMessage(string $glue = "\n"): string;

    public function hasSuccess(): bool;

    public function hasTransactionStatusHeldForReview(): bool;

    public function setNoteInfo(array $noteInfo): self;

    public function statusMessage(): string;
}
