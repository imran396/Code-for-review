<?php
/**
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @since           Oct 17, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Qform\Component;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\CustomizableClassInterface;

/**
 * Class AuctionCustomFieldComponentBuilder
 * @package Sam\CustomField\Auction\Qform\Component
 */
class AuctionCustomFieldComponentBuilder extends CustomizableClass
{
    /** @var string[] */
    protected array $componentClasses = [
        Constants\CustomField::TYPE_INTEGER => IntegerEdit::class,
        Constants\CustomField::TYPE_DECIMAL => DecimalEdit::class,
        Constants\CustomField::TYPE_TEXT => TextEdit::class,
        Constants\CustomField::TYPE_PASSWORD => PasswordEdit::class,
        Constants\CustomField::TYPE_SELECT => SelectionEdit::class,
        Constants\CustomField::TYPE_DATE => DateEdit::class,
        Constants\CustomField::TYPE_FULLTEXT => FulltextEdit::class,
        Constants\CustomField::TYPE_RICHTEXT => RichtextEdit::class,
        Constants\CustomField::TYPE_CHECKBOX => CheckboxEdit::class,
        Constants\CustomField::TYPE_LABEL => LabelEdit::class,
        Constants\CustomField::TYPE_FILE => FileEdit::class,
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
     * Create component object by custom field type
     *
     * @param int $fieldType
     * @return BaseEdit
     */
    public function createComponent(int $fieldType): BaseEdit
    {
        /** @var string|CustomizableClassInterface $class */
        $class = $this->componentClasses[$fieldType];
        $component = call_user_func([$class, 'new']);
        return $component;
    }
}
