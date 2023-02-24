<?php
/**
 * SAM-9875: Implement a code generator for read repository classes
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\Internal\ClassFile;

use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PsrPrinter;
use Sam\Core\Path\PathResolver;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\LocalFileManagerCreateTrait;

/**
 * Class ClassFileWriter
 * @package Sam\Infrastructure\Generate\EntityGenerator\Internal\ClassFile
 * @internal
 */
class ClassFileWriter extends CustomizableClass
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

    /**
     * Save generated class to the file
     *
     * @param string $qualifiedClassName
     * @param PhpFile $phpFile
     * @param bool $rewrite
     * @return bool
     */
    public function write(string $qualifiedClassName, PhpFile $phpFile, bool $rewrite = true): bool
    {
        $ds = DIRECTORY_SEPARATOR;
        $fileName = $this->convertFullyQualifiedClassNameToFileName($qualifiedClassName);
        $fileManager = $this->createLocalFileManager();
        if (
            !$rewrite
            && $fileManager->exist(PathResolver::CLASSES . $ds . $fileName)
        ) {
            return false;
        }
        $content = (new PsrPrinter())->printFile($phpFile);
        $fileManager->createDirPath(path()->classes() . $ds . $fileName);
        $fileManager->write($content, PathResolver::CLASSES . $ds . $fileName);
        return true;
    }

    protected function convertFullyQualifiedClassNameToFileName(string $qualifiedClassName): string
    {
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $qualifiedClassName) . '.php';
        return $file;
    }
}
