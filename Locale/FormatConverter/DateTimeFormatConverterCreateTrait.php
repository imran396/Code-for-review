<?php
/**
 * SAM-5884: Admin side internationalization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Locale\FormatConverter;


/**
 * Trait DateTimeFormatConverterCreateTrait
 * @package Sam\Locale\FormatConverter
 */
trait DateTimeFormatConverterCreateTrait
{
    /**
     * @var DateTimeFormatConverter|null
     */
    protected ?DateTimeFormatConverter $dateTimeFormatConverter = null;

    /**
     * @return DateTimeFormatConverter
     */
    protected function createDateTimeFormatConverter(): DateTimeFormatConverter
    {
        return $this->dateTimeFormatConverter ?: DateTimeFormatConverter::new();
    }

    /**
     * @param DateTimeFormatConverter $dateTimeFormatConverter
     * @return static
     * @internal
     */
    public function setDateTimeFormatConverter(DateTimeFormatConverter $dateTimeFormatConverter): static
    {
        $this->dateTimeFormatConverter = $dateTimeFormatConverter;
        return $this;
    }
}
