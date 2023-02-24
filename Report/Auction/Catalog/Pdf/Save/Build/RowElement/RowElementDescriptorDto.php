<?php
/**
 * SAM-6260: PDF catalog configuration option for Lot name, lot description, font size
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           07-08, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Report\Auction\Catalog\Pdf\Save\Build\RowElement;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Describes
 * element name, font size, font face, font style, content
 * for concrete row element in pdf catalog.
 *
 * Class ColumnElementDescriptorDto
 * @package Sam\Report\Auction\Catalog\Pdf
 */
class RowElementDescriptorDto extends CustomizableClass
{
    public readonly string $elementName;
    public readonly int $fontSize;
    public readonly string $fontFamily;
    public readonly string $fontStyle;
    public readonly string $content;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * ColumnElementDescriptorDto public constructor.
     * @param string $elementName
     * @param string $content
     * @param int $fontSize
     * @param string $fontFamily
     * @param string $fontStyle
     * @return $this
     */
    public function construct(
        string $elementName,
        string $content,
        int $fontSize = 6,
        string $fontFamily = Constants\Pdf::FONT_DEFAULT,
        string $fontStyle = ''
    ): static {
        $this->elementName = $elementName;
        $this->content = $content;
        $this->fontSize = $fontSize;
        $this->fontFamily = $fontFamily;
        $this->fontStyle = $fontStyle;
        return $this;
    }
}
