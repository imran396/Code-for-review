<?php
/**
 * SAM-9131: Apply DTOs for buyer group edit page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyerGroupEditForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class BuyerGroupEditDto
 * @package Sam\View\Admin\Form\BuyerGroupEditForm\Load
 */
class BuyerGroupEditDto extends CustomizableClass
{
    public readonly int $id;
    public readonly int $userId;
    public readonly string $addedOn;
    public readonly string $email;
    public readonly string $firstName;
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
     * @param string $addedOn
     * @param string $email
     * @param string $firstName
     * @param int $id
     * @param string $lastName
     * @param int $userId
     * @param string $username
     * @return $this
     */
    public function construct(
        string $addedOn,
        string $email,
        string $firstName,
        int $id,
        string $lastName,
        int $userId,
        string $username
    ): static {
        $this->addedOn = $addedOn;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->id = $id;
        $this->lastName = $lastName;
        $this->userId = $userId;
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
            (string)$row['added_on'],
            (string)$row['email'],
            (string)$row['first_name'],
            (int)$row['id'],
            (string)$row['last_name'],
            (int)$row['user_id'],
            (string)$row['username'],
        );
    }
}
