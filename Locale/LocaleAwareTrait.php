<?php
/**
 * SAM-5884: Admin side internationalization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 07, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Locale;

/**
 * Trait LocaleAwareTrait
 * @package Sam\Locale
 */
trait LocaleAwareTrait
{
    /**
     * @var string|null
     */
    protected ?string $locale = null;

    /**
     * @return string
     */
    public function getLocale(): string
    {
        if ($this->locale === null) {
            $this->locale = LocaleDetector::new()->getLocale();
        }
        return $this->locale;
    }

    /**
     * @param string $locale
     * @return static
     */
    public function setLocale(string $locale): static
    {
        $this->locale = $locale;
        return $this;
    }
}
