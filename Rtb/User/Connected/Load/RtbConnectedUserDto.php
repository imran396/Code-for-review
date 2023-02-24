<?php
/**
 * SAM-5752: Rtb connected user list builder
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\User\Connected\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class RtbConnectedUserDto
 */
class RtbConnectedUserDto extends CustomizableClass
{
    public string $bidderNum;
    public string $companyName;
    public string $firstName;
    public string $lastName;
    public int $userId;
    public string $username;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param mixed $bidderNum
     * @param mixed $companyName
     * @param mixed $firstName
     * @param mixed $lastName
     * @param mixed $userId
     * @param mixed $username
     * @return $this
     */
    public function construct(
        mixed $bidderNum,
        mixed $companyName,
        mixed $firstName,
        mixed $lastName,
        mixed $userId,
        mixed $username
    ): RtbConnectedUserDto {
        $this->bidderNum = trim((string)$bidderNum);
        $this->companyName = trim((string)$companyName);
        $this->firstName = trim((string)$firstName);
        $this->lastName = trim((string)$lastName);
        $this->userId = (int)$userId;
        $this->username = trim((string)$username);
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): RtbConnectedUserDto
    {
        return $this->construct(
            $row['bidder_num'],
            $row['company_name'],
            $row['first_name'],
            $row['last_name'],
            $row['user_id'],
            $row['username']
        );
    }
}
