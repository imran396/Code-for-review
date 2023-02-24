<?php
/**
 * SAM-8842: Apply DTOs for bid history sc page at admin side
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

namespace Sam\View\Admin\Form\BidHistoryScForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class BidHistoryScDto
 * @package Sam\View\Admin\Form\BidHistoryScForm\Load
 */
class BidHistoryScDto extends CustomizableClass
{
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
     * @param string $bidderNum
     * @param string $firstName
     * @param int $id
     * @param string $lastName
     * @return $this
     */
    public function construct(
        string $bidderNum,
        string $firstName,
        int $id,
        string $lastName
    ): static {
        $this->bidderNum = $bidderNum;
        $this->firstName = $firstName;
        $this->id = $id;
        $this->lastName = $lastName;
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
            (string)$row['first_name'],
            (int)$row['id'],
            (string)$row['last_name']
        );
    }
}
