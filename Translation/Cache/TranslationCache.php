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

namespace Sam\Translation\Cache;

/**
 * Class TranslationCache
 * @package Sam\Translation\Cache
 */
class TranslationCache
{
    private int $creationTime;
    private array $sourceFiles;
    private array $cachedMessages;

    /**
     * TranslationCache constructor.
     * @param int $creationTime
     * @param array $sourceFiles
     * @param array $cachedMessages
     */
    public function __construct(int $creationTime, array $sourceFiles, array $cachedMessages)
    {
        $this->creationTime = $creationTime;
        $this->sourceFiles = $sourceFiles;
        $this->cachedMessages = $cachedMessages;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->cachedMessages;
    }

    /**
     * @return bool
     */
    public function isFresh(): bool
    {
        $sourceFilesModificationTime = array_map(
            static function (string $filepath) {
                return file_exists($filepath) ? filemtime($filepath) : time();
            },
            $this->sourceFiles
        );

        return $this->creationTime >= max($sourceFilesModificationTime);
    }
}
