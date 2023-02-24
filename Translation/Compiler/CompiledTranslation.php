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

namespace Sam\Translation\Compiler;

use RuntimeException;
use Sam\Translation\Compiler\FileLoader\TranslationFile;

/**
 * Class CompiledTranslation
 * @package Sam\Translation
 */
class CompiledTranslation
{
    private string $language;
    private string $domain;
    private array $messages;
    private array $sourceFiles;

    /**
     * @param TranslationFile $translationFile
     * @return self
     */
    public static function createFromTranslationFile(TranslationFile $translationFile): self
    {
        $instance = new self();
        $instance->language = $translationFile->language;
        $instance->domain = $translationFile->domain;
        $instance->messages = $translationFile->messages;
        $instance->sourceFiles = [$translationFile->filePath];
        return $instance;
    }

    /**
     * @param TranslationFile $translationFile
     * @return static
     */
    public function translationFileApplied(TranslationFile $translationFile): static
    {
        if (in_array($translationFile->filePath, $this->getSourceFiles(), true)) {
            throw new RuntimeException(sprintf('Content of file %s is already applied', $translationFile->filePath));
        }
        $instance = clone $this;
        $instance->messages = array_replace($instance->messages, $translationFile->messages);
        $instance->sourceFiles[] = $translationFile->filePath;
        return $instance;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return array
     */
    public function getSourceFiles(): array
    {
        return $this->sourceFiles;
    }

    private function __construct()
    {
    }
}
