<?php
/**
 * SAM-9135: Apply DTOs for buyer group list page at admin side
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

namespace Sam\View\Admin\Form\BuyerGroupListForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class BuyerGroupListDto
 * @package Sam\View\Admin\Form\BuyerGroupListForm\Load
 */
class BuyerGroupListDto extends CustomizableClass
{
    public readonly int $id;
    public readonly string $name;
    public readonly int $users;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $id
     * @param string $name
     * @param int $users
     * @return $this
     */
    public function construct(
        int $id,
        string $name,
        int $users
    ): static {
        $this->id = $id;
        $this->name = $name;
        $this->users = $users;
        return $this;
    }

    /**
     * @param array $row
     * @return static
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (int)$row['id'],
            (string)$row['name'],
            (int)$row['users']
        );
    }
}
