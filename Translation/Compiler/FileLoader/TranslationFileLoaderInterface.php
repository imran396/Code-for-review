<?php
/**
 * SAM-5883: Develop the architecture for the admin side translation
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Translation\Compiler\FileLoader;

/**
 * Interface TranslationFileLoaderInterface
 * @package Sam\Translation\FileLoader
 */
interface  TranslationFileLoaderInterface
{
    /**
     * @param string $dir
     * @param string $language
     * @param string $domain
     * @return TranslationFile|null TranslationFileContent
     */
    public function load(string $dir, string $language, string $domain): ?TranslationFile;
}
