<?php
/**
 * SAM-9486: Entity factory class generator
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\EntityFactory\Code\Internal\File;

use Sam\Core\Path\PathResolver;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\LocalFileManagerCreateTrait;

/**
 * Class EntityWriterRepositoryClassFileWriter
 * @package Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Code\Internal
 * @internal
 */
class EntityFactoryClassFileWriter extends CustomizableClass
{
    use LocalFileManagerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function write(string $qualifiedClassName, string $content, bool $rewrite = true): bool
    {
        $ds = DIRECTORY_SEPARATOR;
        $fileName = $this->convertQualifiedClassNameToFile($qualifiedClassName);
        $fileManager = $this->createLocalFileManager();
        if (
            !$rewrite
            && $fileManager->exist(PathResolver::CLASSES . $ds . $fileName)
        ) {
            return false;
        }
        $fileManager->createDirPath(path()->classes() . $ds . $fileName);
        $fileManager->write($content, PathResolver::CLASSES . $ds . $fileName);
        return true;
    }

    protected function convertQualifiedClassNameToFile(string $qualifiedClassName): string
    {
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $qualifiedClassName) . '.php';
        return $file;
    }
}
