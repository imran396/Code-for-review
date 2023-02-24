<?php
/**
 * SAM-5883: Develop the architecture for the admin side translation
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Translation;


use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Trait AdminTranslatorAwareTrait
 * @package Sam\Translation
 */
trait AdminTranslatorAwareTrait
{
    protected ?TranslatorInterface $adminTranslator = null;

    /**
     * @return TranslatorInterface
     */
    protected function getAdminTranslator(): TranslatorInterface
    {
        if ($this->adminTranslator === null) {
            $this->adminTranslator = AdminTranslator::new();
        }
        return $this->adminTranslator;
    }

    /**
     * @param TranslatorInterface $translator
     * @return static
     * @internal
     */
    public function setAdminTranslator(TranslatorInterface $translator): static
    {
        $this->adminTranslator = $translator;
        return $this;
    }
}
