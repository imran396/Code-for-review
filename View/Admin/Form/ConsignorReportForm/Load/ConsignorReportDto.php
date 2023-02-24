<?php
/**
 * SAM-9136: Apply DTOs for Consignor report page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ConsignorReportForm\Load;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ConsignorReportDto
 * @package Sam\View\Admin\Form\ConsignorReportForm\Load
 */
class ConsignorReportDto extends CustomizableClass
{
    public readonly ?int $customerNo;
    public readonly string $firstName;
    public readonly int $id;
    public readonly string $lastName;
    public readonly string $username;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $customerNo
     * @param string $firstName
     * @param int $id
     * @param string $lastName
     * @param string $username
     * @return $this
     */
    public function construct(
        ?int $customerNo,
        string $firstName,
        int $id,
        string $lastName,
        string $username
    ): static {
        $this->customerNo = $customerNo;
        $this->firstName = $firstName;
        $this->id = $id;
        $this->lastName = $lastName;
        $this->username = $username;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            Cast::toInt($row['customer_no'], Constants\Type::F_INT_POSITIVE),
            (string)$row['first_name'],
            (int)$row['id'],
            (string)$row['last_name'],
            (string)$row['username']
        );
    }
}
