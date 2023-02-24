<?php
/**
 * Building methods for ListBoxSimpleItem array
 *
 * SAM-6907: Simplify QListBox initialization operations
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\Control\ListBox;

use Sam\Core\Service\CustomizableClass;

/**
 * Class ListBoxSimpleItemArrayBuilder
 * @package Sam\Qform\Control\ListBox
 */
class ListBoxOptionArrayBuilder extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        return $this;
    }

    /**
     * Produce array of ListBoxSimpleItem by associative array,
     * where key is a value of list option, and value is name of list option.
     * @param array $array
     * @param mixed $selectedValue value that should be selected.
     * @return ListBoxOption[]
     */
    public function buildByAssociativeArray(array $array, mixed $selectedValue = null): array
    {
        $options = [];
        foreach ($array as $value => $name) {
            $options[] = new ListBoxOption($value, $name, $value === $selectedValue);
        }
        return $options;
    }
}
