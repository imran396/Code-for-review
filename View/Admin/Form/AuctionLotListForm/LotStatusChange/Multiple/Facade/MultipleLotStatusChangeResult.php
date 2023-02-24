<?php
/**
 * SAM-10177: Decouple the "Lot status change" function at the "Auction Lot List" page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Facade;

use Sam\Core\Service\CustomizableClass;

/**
 * Class MultipleLotStatusChangeResult
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Facade
 */
class MultipleLotStatusChangeResult extends CustomizableClass
{
    protected string $successMessage = '';
    protected string $errorMessage = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function setSuccessMessage(string $successMessage): static
    {
        $this->successMessage = $successMessage;
        return $this;
    }

    public function setErrorMessage(string $errorMessage): static
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function successMessage(): string
    {
        return $this->successMessage;
    }

    public function errorMessage(): string
    {
        return $this->errorMessage;
    }

    public function hasSuccess(): bool
    {
        return $this->successMessage !== '';
    }

    public function hasError(): bool
    {
        return $this->errorMessage !== '';
    }
}
