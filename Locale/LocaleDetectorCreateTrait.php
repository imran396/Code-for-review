<?php
/**
 * SAM-5884: Admin side internationalization
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Locale;

/**
 * Trait LocaleDetectorCreateTrait
 * @package Sam\Locale
 */
trait LocaleDetectorCreateTrait
{
    protected ?LocaleDetector $localeDetector = null;

    /**
     * @return LocaleDetector
     */
    protected function createLocaleDetector(): LocaleDetector
    {
        return $this->localeDetector ?: LocaleDetector::new();
    }

    /**
     * @param LocaleDetector $localeDetector
     * @return static
     * @internal
     */
    public function setLocaleDetector(LocaleDetector $localeDetector): static
    {
        $this->localeDetector = $localeDetector;
        return $this;
    }
}
