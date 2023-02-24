<?php
/**
 * SAM-6928: Sales staff user assignment and filtering control adjustments at the "User Edit" and the "Sales Report" pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @since           02-04, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\AddedBy\Autocomplete\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepository;

/**
 * Class UserAddedByDto
 * @package Sam\User\AddedBy\Load\Internal\Load
 */
class UserAddedByDto extends CustomizableClass
{
    public readonly int $id;
    public readonly string $username;
    public readonly string $firstName;
    public readonly string $lastName;
    public readonly bool $isSalesStaff;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $id
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     * @param bool $isSalesStaff
     * @return $this
     */
    public function construct(
        int $id,
        string $username,
        string $firstName,
        string $lastName,
        bool $isSalesStaff
    ): static {
        $this->id = $id;
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->isSalesStaff = $isSalesStaff;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): UserAddedByDto
    {
        return $this->construct(
            (int)$row['id'],
            (string)$row['username'],
            (string)$row['first_name'],
            (string)$row['last_name'],
            (bool)$row[UserReadRepository::ALIAS_IS_SALES_STAFF]
        );
    }
}
