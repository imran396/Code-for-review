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
 * Class TranslationFile
 * @package Sam\Translation\Compiler\FileLoader
 */
class TranslationFile
{
    public readonly string $filePath;
    public readonly string $language;
    public readonly string $domain;
    public readonly array $messages;

    /**
     * TranslationFile constructor.
     * @param string $filePath
     * @param string $language
     * @param string $domain
     * @param array $messages
     */
    public function __construct(string $filePath, string $language, string $domain, array $messages)
    {
        $this->filePath = $filePath;
        $this->language = $language;
        $this->domain = $domain;
        $this->messages = $messages;
    }
}
