<?php
/**
 * SAM-9174: Apply DTO's for Location List page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LocationListForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LocationListDto
 * @package Sam\View\Admin\Form\LocationListForm\Load
 */
class LocationListDto extends CustomizableClass
{
    public readonly int $id;
    public readonly string $accountName;
    public readonly string $address;
    public readonly string $city;
    public readonly string $country;
    public readonly string $county;
    public readonly string $createdOn;
    public readonly string $logo;
    public readonly string $name;
    public readonly string $state;
    public readonly string $zip;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $id
     * @param string $accountName
     * @param string $address
     * @param string $city
     * @param string $country
     * @param string $county
     * @param string $createdOn
     * @param string $logo
     * @param string $name
     * @param string $state
     * @param string $zip
     * @return $this
     */
    public function construct(
        int $id,
        string $accountName,
        string $address,
        string $city,
        string $country,
        string $county,
        string $createdOn,
        string $logo,
        string $name,
        string $state,
        string $zip
    ): static {
        $this->id = $id;
        $this->accountName = $accountName;
        $this->address = $address;
        $this->city = $city;
        $this->country = $country;
        $this->county = $county;
        $this->createdOn = $createdOn;
        $this->logo = $logo;
        $this->name = $name;
        $this->state = $state;
        $this->zip = $zip;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            id: (int)$row['id'],
            accountName: (string)$row['account_name'],
            address: (string)$row['address'],
            city: (string)$row['city'],
            country: (string)$row['country'],
            county: (string)$row['county'],
            createdOn: (string)$row['created_on'],
            logo: (string)$row['logo'],
            name: (string)$row['name'],
            state: (string)$row['state'],
            zip: (string)$row['zip']
        );
    }
}
