<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/1/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lang;

/**
 * Trait PreferredLanguageDetectorAwareTrait
 * @package Sam\Lang
 */
trait PreferredLanguageDetectorAwareTrait
{
    /**
     * @var PreferredLanguageDetector|null
     */
    protected ?PreferredLanguageDetector $preferredLanguageDetector = null;

    /**
     * @return PreferredLanguageDetector
     */
    protected function getPreferredLanguageDetector(): PreferredLanguageDetector
    {
        if ($this->preferredLanguageDetector === null) {
            $this->preferredLanguageDetector = PreferredLanguageDetector::new();
        }
        return $this->preferredLanguageDetector;
    }

    /**
     * @param PreferredLanguageDetector $preferredLanguageDetector
     * @return static
     * @internal
     */
    public function setPreferredLanguageDetector(PreferredLanguageDetector $preferredLanguageDetector): static
    {
        $this->preferredLanguageDetector = $preferredLanguageDetector;
        return $this;
    }
}
