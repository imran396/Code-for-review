<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 * SAM-7961: Move \Simple_Db to Sam\Storage namespace
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Database;


use mysqli;
use mysqli_result;
use RuntimeException;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Log\Support\SupportLoggerAwareTrait;

/**
 * Simple light weight mysqli DB class to be used when QCodo is not used
 *
 * Class SimpleMysqliDatabase
 * @package Sam\Storage\Database
 */
class SimpleMysqliDatabase extends CustomizableClass
{
    use OptionalsTrait;
    use SupportLoggerAwareTrait;

    public const OP_DB_SERVER = 'server';
    public const OP_DB_USERNAME = 'username';
    public const OP_DB_PASSWORD = 'password';
    public const OP_DB_NAME = 'database';
    public const OP_DB_ENCODING = 'encoding';
    public const OP_DEBUG_LEVEL = 'debugLevel';

    protected static ?mysqli $db = null;
    /**
     * @var bool
     */
    protected bool $isProfilingEnabled;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $isProfilingEnabled
     * @param array $optionals = [
     *      self::OP_DB_SERVER => (string),
     *      self::OP_DB_USERNAME => (string),
     *      self::OP_DB_PASSWORD => (string),
     *      self::OP_DB_NAME => (string),
     *      self::OP_DB_ENCODING => (string),
     *      self::OP_DEBUG_LEVEL => (int),
     * ]
     * @return static
     */
    public function construct(bool $isProfilingEnabled = false, array $optionals = []): static
    {
        $this->isProfilingEnabled = $isProfilingEnabled;
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param string|int|float|bool|null $input
     * @return string
     */
    public function escape(string|int|float|bool|null $input): string
    {
        if ($input === null) {
            return 'NULL';
        }

        if (is_bool($input)) {
            return $input ? '1' : '0';
        }

        if (is_int($input) || is_float($input)) {
            return sprintf('%s', $input);
        }

        return sprintf("'%s'", $this->connect()->real_escape_string($input));
    }

    /**
     * @param string $query
     * @return bool|mysqli_result
     */
    public function query(string $query)
    {
        $db = $this->connect();
        // Perform the Query
        $dbResult = $db->query($query);

        // Mysqli Error: MySQL server has gone away, give it another try
        if (
            $db->errno === 2006
            || $db->errno === 2013
        ) {
            $this->getSupportLogger()->always('MySQL server has gone away, trying to reconnect');
            $db = $this->connect();
            $dbResult = $db->query($query);
        }

        if ($db->error) {
            $message = 'MySQLi error: ' . $db->error . '(' . $db->errno . '): ' . $query;
            $this->getSupportLogger()->always($message);
            throw new RuntimeException($message);
        }

        return $dbResult;
    }

    /**
     * Close db connection
     */
    public function close(): void
    {
        if (!self::$db) {
            return;
        }

        @self::$db->close();
        self::$db = null;
    }

    /**
     * Connect to the db
     * @return mysqli
     */
    protected function connect(): mysqli
    {
        if (!self::$db) {
            $tmpTs = microtime(true);
            $server = $this->fetchOptional(self::OP_DB_SERVER);
            $username = $this->fetchOptional(self::OP_DB_USERNAME);
            $password = $this->fetchOptional(self::OP_DB_PASSWORD);
            $database = $this->fetchOptional(self::OP_DB_NAME);
            self::$db = @new mysqli($server, $username, $password, $database);
            if (self::$db->connect_error) {
                $message = 'DB connection failed: ' . self::$db->connect_error;
                if ($this->fetchOptional(self::OP_DEBUG_LEVEL) >= 0) {
                    $this->getSupportLogger()->always($message);
                }
                throw new RuntimeException($message);
            }
            if ($this->isProfilingEnabled) {
                $this->getSupportLogger()->debug('connect to db: ' . ((microtime(true) - $tmpTs) * 1000) . 'ms');
            }
            $encoding = (string)$this->fetchOptional(self::OP_DB_ENCODING);
            if ($encoding !== '') {
                self::$db->query("SET NAMES " . $encoding);
            }
        }
        return self::$db;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_DB_SERVER] = $optionals[self::OP_DB_SERVER]
            ?? static fn() => ConfigRepository::getInstance()->get('core->db->server');
        $optionals[self::OP_DB_USERNAME] = $optionals[self::OP_DB_USERNAME]
            ?? static fn() => ConfigRepository::getInstance()->get('core->db->username');
        $optionals[self::OP_DB_PASSWORD] = $optionals[self::OP_DB_PASSWORD]
            ?? static fn() => ConfigRepository::getInstance()->get('core->db->password');
        $optionals[self::OP_DB_NAME] = $optionals[self::OP_DB_NAME]
            ?? static fn() => ConfigRepository::getInstance()->get('core->db->database');
        $optionals[self::OP_DB_ENCODING] = $optionals[self::OP_DB_ENCODING]
            ?? static fn() => ConfigRepository::getInstance()->get('core->db->encoding');
        $optionals[self::OP_DEBUG_LEVEL] = $optionals[self::OP_DEBUG_LEVEL]
            ?? static fn() => ConfigRepository::getInstance()->get('core->general->debugLevel');
        $this->setOptionals($optionals);
    }
}
