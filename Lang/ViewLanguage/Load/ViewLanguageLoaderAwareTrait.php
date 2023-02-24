<?php
/**
 * SAM-4675: View language loader and existence checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/5/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lang\ViewLanguage\Load;

/**
 * Trait ViewLanguageLoaderAwareTrait
 * @package Sam\Lang\ViewLanguage\Load
 */
trait ViewLanguageLoaderAwareTrait
{
    protected ?ViewLanguageLoader $viewLanguageLoader = null;

    /**
     * @return ViewLanguageLoader
     */
    protected function getViewLanguageLoader(): ViewLanguageLoader
    {
        if ($this->viewLanguageLoader === null) {
            $this->viewLanguageLoader = ViewLanguageLoader::new();
        }
        return $this->viewLanguageLoader;
    }

    /**
     * @param ViewLanguageLoader $viewLanguageLoader
     * @return static
     * @internal
     */
    public function setViewLanguageLoader(ViewLanguageLoader $viewLanguageLoader): static
    {
        $this->viewLanguageLoader = $viewLanguageLoader;
        return $this;
    }
}
