<?php
/**
 * SAM-7670: Make responsive Translator independent of web application context
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Language\Detect;

/**
 * Trait ApplicationLanguageDetectorCreateTrait
 * @package Sam\Application\Language
 */
trait ApplicationLanguageDetectorCreateTrait
{
    protected ?ApplicationLanguageDetector $applicationLanguageDetector = null;

    /**
     * @return ApplicationLanguageDetector
     */
    protected function createApplicationLanguageDetector(): ApplicationLanguageDetector
    {
        return $this->applicationLanguageDetector ?: ApplicationLanguageDetector::new();
    }

    /**
     * @param ApplicationLanguageDetector $applicationLanguageDetector
     * @return $this
     * @internal
     */
    public function setApplicationLanguageDetector(ApplicationLanguageDetector $applicationLanguageDetector): static
    {
        $this->applicationLanguageDetector = $applicationLanguageDetector;
        return $this;
    }
}
