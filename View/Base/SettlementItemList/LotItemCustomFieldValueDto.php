<?php
/**
 * SAM-7975: Steffes 1.4 Additional Data Fields (Lienholders): Development and Coding, rendering structured html for statements
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           04-11, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Base\SettlementItemList;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotItemCustomFieldValueDto
 * @package Sam\View\Base\SettlementItemList
 */
class LotItemCustomFieldValueDto extends CustomizableClass
{
    public string $fieldName = '';
    public string $value = '';
    public string $cssClassName = '';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $value
     * @param string $cssClassName
     * @return $this
     */
    public function construct(string $fieldName, string $value, string $cssClassName): static
    {
        $this->fieldName = $fieldName;
        $this->value = $value;
        $this->cssClassName = $cssClassName;
        return $this;
    }
}
