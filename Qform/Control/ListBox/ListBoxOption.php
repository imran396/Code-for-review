<?php
/**
 * Value-object for option item of select list
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

/**
 * Class ListBoxItem
 * @package Sam\Qform\Control\ListBox
 */
class ListBoxOption
{
    /** @var mixed */
    public mixed $value;
    /** @var string */
    public string $name;
    /** @var bool */
    public bool $isSelected = false;

    /**
     * @param mixed $value
     * @param string $name
     * @param bool $isSelected
     */
    public function __construct(mixed $value, string $name, bool $isSelected = false)
    {
        $this->value = $value;
        $this->name = $name;
        $this->isSelected = $isSelected;
    }
}
