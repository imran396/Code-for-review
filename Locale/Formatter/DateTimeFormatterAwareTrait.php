<?php
/**
 * SAM-5884: Admin side internationalization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 07, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Locale\Formatter;

/**
 * Trait DateTimeFormatterAwareTrait
 * @package Sam\Locale\Formatter
 */
trait DateTimeFormatterAwareTrait
{
    /**
     * @var DateTimeFormatter|null
     */
    protected ?DateTimeFormatter $dateTimeFormatter = null;

    /**
     * @return DateTimeFormatter
     */
    protected function getDateTimeFormatter(): DateTimeFormatter
    {
        if ($this->dateTimeFormatter === null) {
            $this->dateTimeFormatter = DateTimeFormatter::new();
        }
        return $this->dateTimeFormatter;
    }

    /**
     * @param DateTimeFormatter $dateTimeFormatter
     * @return static
     * @internal
     */
    public function setDateTimeFormatter(DateTimeFormatter $dateTimeFormatter): static
    {
        $this->dateTimeFormatter = $dateTimeFormatter;
        return $this;
    }
}
