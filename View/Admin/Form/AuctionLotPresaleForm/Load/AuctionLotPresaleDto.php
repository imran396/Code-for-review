<?php
/**
 * SAM-8314: Apply DTOs for loaded data at Auction Lot Presale page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotPresaleForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionLotPresaleDto
 * @package Sam\View\Admin\Form\AuctionLotPresaleForm\Load
 */
class AuctionLotPresaleDto extends CustomizableClass
{
    public readonly int $customerNo;
    public readonly int $id;
    public readonly string $bidderNum;
    public readonly string $firstName;
    public readonly string $lastName;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $id
     * @param int $customerNo
     * @param string $firstName
     * @param string $lastName
     * @param string $bidderNum
     * @return $this
     */
    public function construct(
        int $id,
        int $customerNo,
        string $firstName,
        string $lastName,
        string $bidderNum
    ): static {
        $this->id = $id;
        $this->customerNo = $customerNo;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->bidderNum = $bidderNum;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (int)$row['id'],
            (int)$row['customer_no'],
            (string)$row['first_name'],
            (string)$row['last_name'],
            (string)$row['bidder_num']
        );
    }
}
