<?php
/**
 * SAM-8835: Apply DTOs for Auction Spending Report page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionSpendingReportForm\Load;


use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionSpendingReportDto
 * @package Sam\View\Admin\Form\AuctionSpendingReportForm\Load
 */
class AuctionSpendingReportDto extends CustomizableClass
{
    public readonly string $bidderNum;
    public readonly float $collected;
    public readonly string $firstName;
    public readonly string $lastName;
    public readonly ?float $maxOutstanding;
    public readonly ?float $spent;
    public readonly int $userId;
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
     * @param string $bidderNum
     * @param float $collected
     * @param string $firstName
     * @param string $lastName
     * @param float|null $maxOutstanding
     * @param float|null $spent
     * @param int $userId
     * @param string $username
     * @return $this
     */
    public function construct(
        string $bidderNum,
        float $collected,
        string $firstName,
        string $lastName,
        ?float $maxOutstanding,
        ?float $spent,
        int $userId,
        string $username
    ): static {
        $this->bidderNum = $bidderNum;
        $this->collected = $collected;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->maxOutstanding = $maxOutstanding;
        $this->spent = $spent;
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
            (string)$row['bidder_num'],
            (float)$row['collected'],
            (string)$row['fname'],
            (string)$row['lname'],
            Cast::toFloat($row['max_outstanding']),
            Cast::toFloat($row['spent']),
            (int)$row['user_id'],
            (string)$row['username'],
        );
    }
}
