<?php
/**
 * Memcached implementation for QFormStateHandler
 * Access is serialized to prevent race conditions
 *
 * TODO: initialize memcache connection
 *
 * @property Memcached Memcached
 * @author tom
 *
 * @package com.swb.sam
 *
 */

namespace Sam\Qform\Longevity;

use Memcached;
use QBaseClass;
use QCryptography;
use QCryptographyException;
use QUndefinedPropertyException;
use ReflectionClass;
use Sam\Infrastructure\Storage\PhpSessionStorage;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * Class MemcachedFormStateHandler
 * @package Sam\Qform\Longevity
 */
class MemcachedFormStateHandler extends QBaseClass
{

    // store instances
    private static $_instance;

    /**
     * Max lock time for the form and session in seconds
     *
     * @var int
     */
    public static int $MaxLockTime = 60;

    /**
     * Wait time in between each qformstate lock check in ms
     *
     * @var int
     */
    public static int $LockCheckWait = 15;

    public static int $FormStateTimeout = 21600; //60*60*6

    /**
     * @var Memcached|null
     */
    protected ?Memcached $memcached = null;

    protected $pid;

    /**
     * Hide the constructor from direct access
     * Use ::new() of the extending class instead
     *
     */
    protected function __construct()
    {
    }

    /**
     * Singleton classes are not supposed to be cloned!
     */
    private function __clone()
    {
    }

    /**
     * Return singleton instance of MemcachedFormStateHandler
     * @return self
     */
    public static function new(): self
    {
        if (!isset(self::$_instance)) {
            $cfg = ConfigRepository::getInstance();
            self::$_instance = new self();
            self::$_instance->pid = uniqid('', true);
            self::$_instance->memcached = new Memcached();
            self::$_instance->memcached->addServers($cfg->get('core->app->memcached->servers')->toArray());
        }
        return self::$_instance;
    }

    /**
     * Save the Qform_state
     *
     * @param string $strFormState
     * @param bool $blnBackButtonFlag
     * @return string page id
     */
    public static function Save($strFormState, $blnBackButtonFlag): string
    {
        $fltTime = $fltTime2 = $fltTime3 = microtime(true);
        $strStats = 'Original size: ' . strlen($strFormState) . ' bytes';

        // Compress (if available)
        if (function_exists('gzcompress')) {
            $strFormState = gzcompress($strFormState, 9);
            $strStats .= ', compressed: ' . strlen($strFormState) . ' bytes';
            $fltTime2 = microtime(true);
            $strStats .= ' (' . round(($fltTime2 - $fltTime) * 1000, 1) . 'ms)';
        }

        $cfg = ConfigRepository::getInstance();
        if ($cfg->get('core->app->qform->encryptionKey') !== null) {
            // Use QCryptography to Encrypt
            $objCrypto = new QCryptography($cfg->get('core->app->qform->encryptionKey'), true);
            $strFormState = $objCrypto->Encrypt($strFormState);
            $strStats .= ', encrypted: ' . strlen($strFormState) . ' bytes';
            $fltTime3 = microtime(true);
            $strStats .= ' (' . round(($fltTime3 - $fltTime2) * 1000, 1) . 'ms)';
        }

        // Figure Out Session Id (if applicable)
        $strSessionId = PhpSessionStorage::new()->getSessionId();

        // Calculate a new unique Page Id
        $strPageId = md5(microtime());

        // Figure Out Key
        $strKey = sprintf(
            '%s%s_%s',
            self::keyNamePrefix(),
            $strSessionId,
            $strPageId
        );
        log_debug(composeSuffix(['Key' => $strKey]));

        $instance = self::new();
        $m = $instance->memcached;

        // save data
        $m->set($strKey, $strFormState, self::$FormStateTimeout);
        $fltTime4 = microtime(true);
        $strStats .= ', write to memcached: ' . round(($fltTime4 - $fltTime3) * 1000, 1) . 'ms';

        $m->quit();

        $fltTime5 = microtime(true);
        $strStats .= ', cleanup: ' . round(($fltTime5 - $fltTime4) * 1000, 1) . 'ms';
        $strStats .= ', total: ' . round(($fltTime5 - $fltTime) * 1000, 1) . 'ms';
        log_debug($strStats);

        // Return the Page Id
        // Because of the MD5-random nature of the Page ID, there is no need/reason to encrypt it
        return $strPageId;
    }

    /**
     * @param string $strPostDataState
     * @return string|null
     * @throws QCryptographyException
     */
    public static function Load($strPostDataState): ?string
    {
        $fltTime = microtime(true);
        $stats = '';

        // Pull Out strPageId
        $strPageId = $strPostDataState;

        // Figure Out Session Id (if applicable)
        $strSessionId = PhpSessionStorage::new()->getSessionId();

        // Figure Out Key
        $strKey = sprintf(
            '%s%s_%s',
            self::keyNamePrefix(),
            $strSessionId,
            $strPageId
        );
        log_debug(composeSuffix(['Key' => $strKey]));

        $instance = self::new();
        $m = $instance->memcached;

        if (!($serializedForm = $m->get($strKey))) {
            $resultCode = $m->getResultCode();
            if ($resultCode === Memcached::RES_NOTFOUND) {
                $serializedForm = null;
            } else {
                log_warning('Failed to retrieve FormState for' . composeSuffix([$strKey => $resultCode]));
                $serializedForm = null;
            }
        }
        $fltTime4 = $fltTime5 = microtime(true);
        $stats .= ', get: ' . strlen((string)$serializedForm) . ' bytes (' . round(($fltTime4 - $fltTime) * 1000, 1) . 'ms)';

        $m->quit();

        $cfg = ConfigRepository::getInstance();
        if ($cfg->get('core->app->qform->encryptionKey') !== null && $serializedForm) {
            // Use QCryptography to Decrypt
            $objCrypto = new QCryptography($cfg->get('core->app->qform->encryptionKey'), true);
            $serializedForm = $objCrypto->Decrypt($serializedForm);
            $fltTime5 = microtime(true);
            $stats .= ', decrypted: ' . strlen($serializedForm) . ' bytes';
            $stats .= ' (' . round(($fltTime5 - $fltTime4) * 1000, 1) . 'ms)';
        }

        // Un-compress (if available)
        if (function_exists('gzcompress') && $serializedForm) {
            $serializedForm = gzuncompress($serializedForm);
            $fltTime6 = microtime(true);
            $stats .= ', uncompressed: ' . strlen($serializedForm) . ' bytes';
            $stats .= ' (' . round(($fltTime6 - $fltTime5) * 1000, 1) . 'ms)';
        }

        $fltTime7 = microtime(true);
        $stats .= ', total: ' . round(($fltTime7 - $fltTime) * 1000, 1) . 'ms';
        log_debug($stats);

        return $serializedForm;
    }

    /**
     * Override method to perform a property "Get"
     * This will get the value of $name
     *
     * @param string $name Name of the property to get
     * @return mixed
     * @throws QUndefinedPropertyException
     */
    public function __get($name)
    {
        switch ($name) {
            case 'Memcached':
                return $this->memcached;
            case 'Pid':
                return $this->pid;
            default:
                $reflection = new ReflectionClass($this);
                throw new QUndefinedPropertyException("GET", $reflection->getName(), $name);
        }
    }

    protected static function keyNamePrefix(): string
    {
        return ConfigRepository::getInstance()->get('core->app->qform->memcachedFormStateHandler->keyNamePrefix');
    }
}
