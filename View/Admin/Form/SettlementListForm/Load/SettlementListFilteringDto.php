<?php
/**
 * SAM-9176: Apply DTO's for Settlement List page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementListForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementListFilteringDto
 * @package Sam\View\Admin\Form\SettlementListForm\Load
 */
class SettlementListFilteringDto extends CustomizableClass
{
    /** @var int */
    public int $id = 0;
    /** @var string */
    public string $username = '';

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $id
     * @param string $username
     * @return $this
     */
    public function construct(int $id, string $username): static
    {
        $this->id = $id;
        $this->username = $username;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct((int)$row['id'], (string)$row['username']);
    }
}
