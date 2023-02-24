<?php
/**
 * SAM-6585: Refactor auction custom field management to separate module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Ivan Zgoniaiko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionCustomFieldEditForm\Dto;

use Sam\Core\Dto\StringDto;

/**
 * Class AuctionCustomFieldEditFormDto
 * @package Sam\View\Admin\Form\AuctionCustomFieldEditForm\Dto
 *
 * @property string $adminList
 * @property string $clone
 * @property string|int $id
 * @property string $name
 * @property string $order
 * @property string $parameters
 * @property string $publicList
 * @property string $required
 * @property string $type
 */
class AuctionCustomFieldEditFormDto extends StringDto
{
    /** @var string[] */
    protected array $availableFields = [
        'adminList',
        'clone',
        'id',
        'name',
        'order',
        'parameters',
        'publicList',
        'required',
        'type',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function getAvailableFields(): array
    {
        return $this->availableFields;
    }
}
