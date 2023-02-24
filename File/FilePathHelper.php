<?php
/**
 * Search for path and cache result.
 *
 * @author        Igors Kotlevskis
 * @since         Dec 20, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\File;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;

/**
 * Class FilePathHelper
 * @package Sam\File
 */
class FilePathHelper extends CustomizableClass
{
    /**
     * @var bool[]
     */
    protected array $readableStatuses = [];
    protected array $stats = [
        'is_readable' => 0,
        'get_cached' => 0,
        'get_cached_files' => [],
    ];

    /**
     * Return instance of self
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Returns TRUE if the $filename is readable, or FALSE otherwise.
     * This function uses the PHP include_path, where PHP is_readable()
     * does not.
     *
     * @param string $filename
     * @return bool
     */
    public function isReadable(string $filename): bool
    {
        $filename = $this->normalize($filename);
        $isAbsolute = $this->isAbsolute($filename);
        if (
            $isAbsolute
            && $this->isReadableCached($filename)
        ) {
            // Return early if the filename is readable without needing the
            // include_path
            return true;
        }

        if (
            stripos(PHP_OS, 'WIN') === 0
            && preg_match('/^[a-z]:/i', $filename)
        ) {
            // If on windows, and path provided is clearly an absolute path,
            // return false immediately
            return false;
        }

        if (!$isAbsolute) {
            foreach ($this->explodeIncludePath() as $path) {
                if ($path === '.') {
                    if ($this->isReadableCached($filename)) {
                        return true;
                    }
                    continue;
                }
                $file = $path . '/' . $filename;
                if ($this->isReadableCached($file)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Explode an current include_path into an array.
     * Works around issues that occur when the path includes stream schemas.
     * @return array
     */
    public function explodeIncludePath(): array
    {
        $path = get_include_path();
        if (PATH_SEPARATOR === ':') {
            // On *nix systems, include_paths which include paths with a stream
            // schema cannot be safely explode'd, so we have to be a bit more
            // intelligent in the approach.
            $paths = preg_split('#:(?!//)#', $path) ?: [];
        } else {
            $paths = explode(PATH_SEPARATOR, $path);
            $paths = array_filter($paths);
        }
        return $paths;
    }

    /**
     * @param string $filename
     * @return bool
     */
    public function isReadableCached(string $filename): bool
    {
        if (!array_key_exists($filename, $this->readableStatuses)) {
            $this->readableStatuses[$filename] = is_readable($filename);
            $this->stats['is_readable']++;
        } else {
            $this->stats['get_cached']++;
            $this->stats['get_cached_files'][$filename] = isset($this->stats['get_cached_files'][$filename])
                ? $this->stats['get_cached_files'][$filename]++ : 0;
        }
        return $this->readableStatuses[$filename];
    }

    /**
     * @return array
     */
    public function getStats(): array
    {
        $this->stats['readable_statuses'] = $this->readableStatuses;
        return $this->stats;
    }

    /**
     * Convert windows path to unix
     * @param string $path
     * @return string
     */
    public function normalize(string $path): string
    {
        // $path = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
        $path = str_replace('\\', '/', $path);
        // $path = preg_replace('|(?<=.)/+|', '/', $path);
        // if (':' === substr($path, 1, 1)) {
        //     $path = ucfirst($path);
        // }
        return $path;
    }

    /**
     * Check $path is absolute, not relative
     * @param string $path
     * @return bool
     */
    public function isAbsolute(string $path): bool
    {
        $path = $this->normalize($path);
        $sysRoot = $this->normalize(path()->sysRoot());
        $is = str_starts_with($path, $sysRoot);
        return $is;
    }

    /**
     * Searches for file modification time and appends it as ?ts=<ts> query-string parameter.
     * @param string $fileUrlPath - relative path to file withing document root
     * @return string
     */
    public function appendUrlPathWithMTime(string $fileUrlPath): string
    {
        $fileRootPath = path()->docRoot() . $fileUrlPath;
        $modifyTimeParam = $this->findModifyTimeUrlParam($fileRootPath);
        if ($modifyTimeParam) {
            $fileUrlPath = UrlParser::new()->replaceParams($fileUrlPath, $modifyTimeParam);
        }
        return $fileUrlPath;
    }

    /**
     * Return modification time in array where key is parameter name and value is timestamp.
     * @param string $fileRootPath
     * @return array
     */
    public function findModifyTimeUrlParam(string $fileRootPath): array
    {
        if (!$fileRootPath) {
            return [];
        }

        $modifyTime = @filemtime(@realpath($fileRootPath));
        if ($modifyTime !== false) {
            return ['ts' => $modifyTime];
        }
        return [];
    }

    /**
     * Shorten file name, cutting name fragment without extension (ex. "File name...doc")
     * @param string $fileName
     * @param int $maxLength
     * @return string
     */
    public function shortenFileName(string $fileName, int $maxLength): string
    {
        $extension = substr($fileName, strrpos($fileName, '.') + 1);
        $nameNoExt = substr($fileName, 0, strrpos($fileName, '.'));
        $maxLength -= strlen($extension) - 1;
        if (strlen($nameNoExt) > $maxLength) {
            $nameNoExt = substr($nameNoExt, 0, $maxLength) . '..';
        }
        return $nameNoExt . '.' . $extension;
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function toFilename(mixed $value): string
    {
        if (is_object($value) && !$value->Id) {
            $filename = pathinfo($value->Source, PATHINFO_FILENAME);
        } elseif (is_object($value) && $value->Id) {
            $filename = strtolower((string)$value);
            $filename = preg_replace("/[^_0-9a-z.]+/i", "-", $filename);
            $filename = trim($filename, "-");
        } else {
            $filename = strtolower($value);
            $filename = preg_replace("/[^_0-9a-z.]+/i", "-", $filename);
            $filename = trim($filename, "-");
        }
        return $filename;
    }
}
