<?php
/**
 * SAM-4449: Language label translation modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/17/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lang;

/**
 * Trait TranslatorAwareTrait
 * @package Sam\Lang
 */
trait TranslatorAwareTrait
{
    protected ?TranslatorInterface $translator = null;

    /**
     * @return TranslatorInterface
     */
    protected function getTranslator(): TranslatorInterface
    {
        if ($this->translator === null) {
            $this->translator = Translator::new();
        }
        return $this->translator;
    }

    /**
     * @param TranslatorInterface $translator
     * @return static
     * @internal
     */
    public function setTranslator(TranslatorInterface $translator): static
    {
        $this->translator = $translator;
        return $this;
    }
}
