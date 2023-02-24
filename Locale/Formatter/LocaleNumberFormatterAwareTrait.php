<?php
/**
 * SAM-5884: Admin side internationalization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 08, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Locale\Formatter;

/**
 * Trait LocaleNumberFormatterAwareTrait
 * @package Sam\Locale\Formatter
 */
trait LocaleNumberFormatterAwareTrait
{
    /**
     * @var LocaleNumberFormatter|null
     */
    protected ?LocaleNumberFormatter $localeNumberFormatter = null;

    /**
     * @return LocaleNumberFormatter
     */
    protected function getLocaleNumberFormatter(): LocaleNumberFormatter
    {
        if ($this->localeNumberFormatter === null) {
            $this->localeNumberFormatter = LocaleNumberFormatter::new();
        }
        return $this->localeNumberFormatter;
    }

    /**
     * @param LocaleNumberFormatter $localeNumberFormatter
     * @return static
     * @internal
     */
    public function setLocaleNumberFormatter(LocaleNumberFormatter $localeNumberFormatter): static
    {
        $this->localeNumberFormatter = $localeNumberFormatter;
        return $this;
    }
}
