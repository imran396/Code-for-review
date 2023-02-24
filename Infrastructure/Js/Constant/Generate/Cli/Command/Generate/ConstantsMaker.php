<?php
/**
 * SAM-8867: Modularize JS constants generation script
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Js\Constant\Generate\Cli\Command\Generate;


use Laminas\Json\Json;
use ReflectionClass;
use RuntimeException;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepository;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConstantsMaker
 * @package Sam\Infrastructure\Js\Constant\Generate\Cli\Command\Generate
 */
class ConstantsMaker extends CustomizableClass
{
    protected ?OutputInterface $output = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getOutput(): OutputInterface
    {
        if ($this->output === null) {
            throw new RuntimeException('Output not set');
        }
        return $this->output;
    }

    public function setOutput(OutputInterface $output): static
    {
        $this->output = $output;
        return $this;
    }

    public function handle(string $jsConstantDirRootPath, string $phpConstantDirRootPath): void
    {
        $classNamesWithNamespaces = $this->generateClasses($phpConstantDirRootPath, $jsConstantDirRootPath);
        foreach ($classNamesWithNamespaces as $relativePath => $classNames) {
            $this->generateIndexFile($classNames, realpath($jsConstantDirRootPath . $relativePath));
        }
    }

    /**
     * Generate JS constant classes and return their names.
     * @param string $phpConstantDirRootPath
     * @param string $jsConstantDirRootPath
     * @return string[][]
     */
    protected function generateClasses(string $phpConstantDirRootPath, string $jsConstantDirRootPath): array
    {
        $phpFiles = $this->detectPhpFileRootPaths($phpConstantDirRootPath);

        $classNames = [];
        foreach ($phpFiles as $phpFile) {
            $phpFileRelativePath = $this->getPhpFileRelativePath($phpFile, $phpConstantDirRootPath);
            [$namespace, $class] = $this->detectNamespaceAndClassFromFile($phpFile);
            if (
                !$class
                || !$namespace
            ) {
                $this->output("Class name or namespace was not found in file " . $phpFile);
                continue;
            }
            $classNameWithNamespace = $namespace . "\\" . $class;
            if (in_array($classNameWithNamespace, Config::DENIED_CLASSES)) {
                $this->output("Class " . $classNameWithNamespace . " in denied list");
                continue;
            }

            $jsPath = $jsConstantDirRootPath . $phpFileRelativePath;
            if (
                !is_dir($jsPath)
                && !@mkdir($jsPath, ConfigRepository::getInstance()->get('core->filesystem->folderPermissions'), true)
                && !is_dir($jsPath)
            ) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $jsPath));
            }
            chmod($jsPath, 0755);
            $jsContent = $this->generateJsContent($classNameWithNamespace);
            $relativeJsFileName = $phpFileRelativePath . '/' . $class;
            if ($jsContent) {
                $classNames[$phpFileRelativePath][] = $class;
                $isClassCreated = file_put_contents($jsPath . '/' . $class . ".ts", $jsContent);
                if ($isClassCreated) {
                    $this->output("Created class " . $relativeJsFileName);
                } else {
                    $this->output("Can't created class " . $relativeJsFileName, false);
                }
            } else {
                $this->output("Empty PHP class " . $relativeJsFileName, false);
            }
        }
        return $classNames;
    }

    protected function generateJsContent(string $classNameWithNamespace): string
    {
        $reflectClass = new ReflectionClass($classNameWithNamespace);
        $comment = $this->makeClassComment() . "\n\n";
        $jsContent = $this->convertPhpValues($reflectClass->getConstants());
        $jsContent .= $this->convertPhpValues($reflectClass->getStaticProperties());
        $result = $jsContent ? $comment . $jsContent : '';
        return $result;
    }

    protected function getPhpFileRelativePath(string $phpFileRootPath, string $phpConstantDirRootPath): string
    {
        $relativeFilePath = substr($phpFileRootPath, strlen($phpConstantDirRootPath));
        $pathParts = explode('/', $relativeFilePath);
        array_pop($pathParts);
        $phpFileRelativePath = implode('/', $pathParts);
        return $phpFileRelativePath;
    }

    protected function makeClassComment(): string
    {
        $comment = <<<Comment
/**
 * Please, do NOT change file content. The content was generated automatically by bin/generate/php_to_js_constants.php
 */
Comment;
        return $comment;
    }

    protected function convertPhpValues(array $phpValues): string
    {
        $jsContent = '';
        foreach ($phpValues as $name => $value) {
            $jsContent .= "export const " . $name . " = " . $this->prepareValueForJs($value) . ";\n";
        }
        return $jsContent;
    }

    protected function prepareValueForJs($value): string
    {
        $encoded = Json::encode($value);
        $jsValue = Json::prettyPrint($encoded, ['indent' => '  ']);
        return (string)preg_replace("/\"/", "'", $jsValue);
    }

    protected function generateIndexFile(array $classNames, string $jsConstantsPath): void
    {
        $importJs = '';
        $exportJs = "export {\n";
        $importTemplate = "import * as %s from './%s';\n";
        $comment = <<<Comment
/**
 * Index file for all constants
 * Export file of the package Constant (Barrel file https://angular.io/docs/ts/latest/glossary.html#barrel)
 */
Comment;

        sort($classNames);
        foreach ($classNames as $className) {
            $importJs .= sprintf($importTemplate, $className, $className);
            $exportJs .= $this->generateSpaces(2) . $className . ",\n";
        }

        $exportJs .= "};";
        $jsContent = $comment . "\n\n" . $importJs . "\n\n" . $exportJs;
        $this->createFile($jsConstantsPath . "/index.ts", $jsContent);
    }

    protected function createFile(string $path, string $content): void
    {
        $isFileCreated = file_put_contents($path, $content);
        if ($isFileCreated) {
            $this->output("Created Index for " . $path);
        } else {
            $this->output("Can't create Index for " . $path, false);
        }
    }

    protected function output(string $msg, bool $ok = true): void
    {
        $message = ($ok ? "[ok] " : "[error] ") . $msg;
        $this->getOutput()->writeln($message);
    }

    protected function generateSpaces(int $amount): string
    {
        return str_repeat(" ", $amount);
    }

    /**
     * Read directory for php file paths in it.
     * @param string $dirPath
     * @return array
     */
    protected function detectPhpFileRootPaths(string $dirPath): array
    {
        $fileRootPaths = [];
        $files = scandir($dirPath, SCANDIR_SORT_NONE);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $fileRootPath = $dirPath . '/' . $file;
                if (is_dir($fileRootPath)) {
                    $fileRootPaths = array_merge($fileRootPaths, $this->detectPhpFileRootPaths($fileRootPath));
                } else {
                    $fileRootPaths[] = $fileRootPath;
                }
            }
        }
        return $fileRootPaths;
    }

    /**
     * Determine namespace and class by absolute file path.
     * @param string $fileRootPath
     * @return string[]
     */
    protected function detectNamespaceAndClassFromFile(string $fileRootPath): array
    {
        $contents = file_get_contents($fileRootPath);
        $tokens = token_get_all($contents);

        $namespace = $class = $stopAt = '';

        foreach ($tokens as $token) {
            $currentLex = is_array($token) ? $token[1] : $token;
            if (
                !empty($stopAt)
                && $stopAt === $currentLex
            ) {
                if ($namespace && $class) {
                    break;
                }
                $stopAt = null;
            }

            if (is_array($token)) {
                if ($stopAt === ';') {
                    $namespace .= trim($token[1]);
                } elseif ($stopAt === '{') {
                    $class .= trim($token[1]);
                }

                if ($token[0] === T_NAMESPACE) {
                    $stopAt = ';';
                } elseif ($token[0] === T_CLASS) {
                    $stopAt = '{';
                }
            }
        }

        return [$namespace, $class];
    }
}
