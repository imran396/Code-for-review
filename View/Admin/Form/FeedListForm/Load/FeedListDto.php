<?php
/**
 * SAM-8838: Apply DTOs for feed list management at admin side
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

namespace Sam\View\Admin\Form\FeedListForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class FeedListDto
 * @package Sam\View\Admin\Form\FeedListForm\Load
 */
class FeedListDto extends CustomizableClass
{
    public readonly string $accountName;
    public readonly int $escaping;
    public readonly string $feedType;
    public readonly int $id;
    public readonly string $name;
    public readonly string $slug;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $accountName
     * @param int $escaping
     * @param string $feedType
     * @param int $id
     * @param string $name
     * @param string $slug
     * @return $this
     */
    public function construct(
        string $accountName,
        int $escaping,
        string $feedType,
        int $id,
        string $name,
        string $slug
    ): static {
        $this->accountName = $accountName;
        $this->escaping = $escaping;
        $this->feedType = $feedType;
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (string)$row['account_name'],
            (int)$row['escaping'],
            (string)$row['feed_type'],
            (int)$row['id'],
            (string)$row['name'],
            (string)$row['slug'],
        );
    }
}
