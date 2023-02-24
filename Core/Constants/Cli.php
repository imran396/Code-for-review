<?php

namespace Sam\Core\Constants;

/**
 * Class Cli
 * @package Sam\Core\Constants
 */
class Cli
{
    public const EXIT_SUCCESS = 0;
    public const EXIT_GENERAL_ERROR = 1;
    public const EXIT_INCORRECT_USAGE = 2;
    public const EXIT_NON_EXECUTABLE_COMMAND = 126;
    public const EXIT_COMMAND_NOT_FOUND = 127;
}
