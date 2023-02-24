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

use Sam\Core\Service\CustomizableClass;

/**
 * Class CsvFileLoader
 * @package Sam\Translation\FileLoader
 */
class CsvFileLoader extends CustomizableClass implements TranslationFileLoaderInterface
{
    private const TRANSLATION_FILE_NAME_PATTERN = '%s.%s.csv';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function load(string $dir, string $language, string $domain): ?TranslationFile
    {
        $file = $this->detectFilePath($dir, $language, $domain);
        if (file_exists($file)) {
            $content = $this->readTranslationFile($file);
            return new TranslationFile($file, $language, $domain, $content);
        }
        return null;
    }

    /**
     * @param $dir
     * @param $language
     * @param $domain
     * @return string
     */
    private function detectFilePath($dir, $language, $domain): string
    {
        return $dir . '/' . sprintf(self::TRANSLATION_FILE_NAME_PATTERN, $domain, $language);
    }

    /**
     * @param string $file
     * @return array
     */
    private function readTranslationFile(string $file): array
    {
        $result = [];
        $fileResource = fopen($file, 'rb');
        while (($data = fgetcsv($fileResource)) !== false) {
            if (count($data) > 1) {
                $result[$data[0]] = $data[1];
            }
        }
        fclose($fileResource);
        return $result;
    }
}
