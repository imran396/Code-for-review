<?php
/**
 * SAM-8843: Apply DTOs for buyer group add user page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyerGroupAddUserForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class BuyerGroupAddUserDto
 * @package Sam\View\Admin\Form\BuyerGroupAddUserForm\Load
 */
class BuyerGroupAddUserDto extends CustomizableClass
{
    public readonly string $email;
    public readonly string $firstName;
    public readonly int $id;
    public readonly string $lastName;
    public readonly string $username;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $email
     * @param string $firstName
     * @param int $id
     * @param string $lastName
     * @param string $username
     * @return $this
     */
    public function construct(
        string $email,
        string $firstName,
        int $id,
        string $lastName,
        string $username
    ): static {
        $this->email = $email;
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
            (string)$row['email'],
            (string)$row['first_name'],
            (int)$row['id'],
            (string)$row['last_name'],
            (string)$row['username']
        );
    }
}
