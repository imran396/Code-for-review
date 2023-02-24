<?php
/**
 * SAM-5716: Extract auction bidder validation and building logic from "Add New Bidder" form
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AddNewBidderForm\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Class BidderAddressDto
 * @package Sam\View\Admin\Form\AddNewBidderForm\Dto
 */
class BidderAddressDto extends CustomizableClass
{
    public readonly string $firstName;
    public readonly string $lastName;
    public readonly string $country;
    public readonly string $city;
    public readonly string $state;
    public readonly string $zip;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        string $firstName,
        string $lastName,
        string $country,
        string $city,
        string $state,
        string $zip
    ): static {
        $this->firstName = trim($firstName);
        $this->lastName = trim($lastName);
        $this->country = trim($country);
        $this->city = trim($city);
        $this->state = trim($state);
        $this->zip = trim($zip);
        return $this;
    }
}
