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
 * Trait LocaleProviderCreateTrait
 */
trait LocaleProviderCreateTrait
{
    /**
     * @var LocaleProvider|null
     */
    protected ?LocaleProvider $localeProvider = null;

    /**
     * @return LocaleProvider
     */
    protected function createLocaleProvider(): LocaleProvider
    {
        return $this->localeProvider ?: LocaleProvider::new();
    }

    /**
     * @param LocaleProvider $provider
     * @return static
     * @internal
     */
    public function setLocaleProvider(LocaleProvider $provider): static
    {
        $this->localeProvider = $provider;
        return $this;
    }
}
