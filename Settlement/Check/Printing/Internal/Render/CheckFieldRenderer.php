<?php
/**
 * SAM-9890: Check Printing for Settlements: Implementation of printing content rendering
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Printing\Internal\Render;

use Sam\Core\Service\CustomizableClass;

/**
 * Class CheckFieldRenderer
 * @package Sam\Settlement\Check
 * @internal
 * #[Pure]
 */
class CheckFieldRenderer extends CustomizableClass
{
    protected string $fieldName;
    protected ?CheckFieldPosition $position;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $fieldName
     * @param CheckFieldPosition|null $position
     * @return $this
     * #[Pure]
     */
    public function construct(string $fieldName, ?CheckFieldPosition $position): static
    {
        $this->fieldName = $fieldName;
        $this->position = $position;
        return $this;
    }

    /**
     * @param int $checkIndex
     * @param string $content
     * @return string
     * #[Pure]
     */
    public function render(int $checkIndex, string $content): string
    {
        if (!$this->position) {
            log_debug("Coordinates for $this->fieldName check field is not specified");
            return '';
        }
        $position = $this->renderPosition($this->position, $checkIndex);
        return "<div class=\"stlm-check-{$this->fieldName}\" style=\"{$position}\">{$content}</div>";
    }

    protected function renderPosition(CheckFieldPosition $fieldPosition, int $checkIndex): string
    {
        return "left:{$fieldPosition->getXCoordinate()}px; top:{$fieldPosition->calcYCoordinate($checkIndex)}px;";
    }
}
