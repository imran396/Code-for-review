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

namespace Sam\Lang\ViewLanguage\Validate;

/**
 * Trait ViewLanguageExistenceCheckerAwareTrait
 * @package Sam\Lang\ViewLanguage\Validate
 */
trait ViewLanguageExistenceCheckerAwareTrait
{
    protected ?ViewLanguageExistenceChecker $viewLanguageExistenceChecker = null;

    /**
     * @return ViewLanguageExistenceChecker
     */
    protected function getViewLanguageExistenceChecker(): ViewLanguageExistenceChecker
    {
        if ($this->viewLanguageExistenceChecker === null) {
            $this->viewLanguageExistenceChecker = ViewLanguageExistenceChecker::new();
        }
        return $this->viewLanguageExistenceChecker;
    }

    /**
     * @param ViewLanguageExistenceChecker $viewLanguageExistenceChecker
     * @return static
     * @internal
     */
    public function setViewLanguageExistenceChecker(ViewLanguageExistenceChecker $viewLanguageExistenceChecker): static
    {
        $this->viewLanguageExistenceChecker = $viewLanguageExistenceChecker;
        return $this;
    }
}
