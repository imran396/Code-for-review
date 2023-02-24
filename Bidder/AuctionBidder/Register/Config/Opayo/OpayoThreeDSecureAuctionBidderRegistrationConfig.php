<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Oct 2, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Register\Config\Opayo;

use Sam\Core\Service\CustomizableClass;


class OpayoThreeDSecureAuctionBidderRegistrationConfig extends CustomizableClass
{
    /**
     * True, when Opayo billing transaction completed with success,
     * False, when Opayo billing transaction was failed.
     * @var bool
     */
    public readonly bool $hasSuccess;
    /**
     * The error message is captured from billing response.
     * @var string
     */
    public readonly string $errorMessage;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(bool $hasSuccess, string $errorMessage): static
    {
        $this->hasSuccess = $hasSuccess;
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function constructSuccess(): static
    {
        return $this->construct(true, '');
    }

    public function constructFail(string $errorMessage): static
    {
        return $this->construct(false, $errorMessage);
    }
}
