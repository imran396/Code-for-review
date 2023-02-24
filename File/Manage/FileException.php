<?php

namespace Sam\File\Manage;

use Exception;

/**
 * Exceptions when working with file
 */
class FileException extends Exception
{
    public const INVALID_PATH = 'Invalid Path: %s';
    public const INVALID_PATH_NO = 1;
    public const FILE_NOT_FOUND = 'File not found: %s';
    public const FILE_NOT_FOUND_NO = 2;
    public const FILE_DELETE = 'Failed to delete file: %s';
    public const FILE_DELETE_NO = 3;
    public const FILE_WRITE = 'Failed to write file: %s';
    public const FILE_WRITE_NO = 4;
    public const FILE_READ = 'Failed to read file: %s';
    public const FILE_READ_NO = 5;
    public const DIR_CREATE = 'Failed to create directory: %s';
    public const DIR_CREATE_NO = 6;
    public const SOURCE_FILE_LOST = 'Uploaded temporary file lost %s, which intended for coping to %s';
    public const SOURCE_FILE_LOST_NO = 7;
    public const FILE_COPY = 'Failed to copy or move file %s to %s';
    public const FILE_COPY_NO = 8;
    public const ALLOWED_FOLDER_NOT_INIT = "AllowedPaths needs to be initialized";
    public const ALLOWED_FOLDER_NOT_INIT_NO = 9;
    public const DENIED_REGEX_NOT_INIT = "DeniedRegEx needs to be initialized";
    public const DENIED_REGEX_NOT_INIT_NO = 10;

    public static array $errorMessages = [
        self::INVALID_PATH_NO => self::INVALID_PATH,
        self::FILE_NOT_FOUND_NO => self::FILE_NOT_FOUND,
        self::FILE_DELETE_NO => self::FILE_DELETE,
        self::FILE_WRITE_NO => self::FILE_WRITE,
        self::FILE_READ_NO => self::FILE_READ,
        self::DIR_CREATE_NO => self::DIR_CREATE,
        self::SOURCE_FILE_LOST_NO => self::SOURCE_FILE_LOST,
        self::FILE_COPY_NO => self::FILE_COPY,
        self::ALLOWED_FOLDER_NOT_INIT_NO => self::ALLOWED_FOLDER_NOT_INIT,
        self::DENIED_REGEX_NOT_INIT_NO => self::DENIED_REGEX_NOT_INIT,
    ];
}
