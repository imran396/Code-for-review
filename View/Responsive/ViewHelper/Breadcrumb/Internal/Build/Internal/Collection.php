<?php
/**
 * SAM-4500: Front-end breadcrumb
 * https://bidpath.atlassian.net/browse/SAM-4500
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\ViewHelper\Breadcrumb\Internal\Build\Internal;

use Sam\Core\Service\CustomizableClass;

/**
 * Class Collection
 * @package Sam\View\Responsive\ViewHelper\Breadcrumb
 */
class Collection extends CustomizableClass
{
    protected array $data = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Crumb $crumb
     * @return $this
     */
    public function add(Crumb $crumb): static
    {
        $this->data[] = $crumb->toArray();
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
