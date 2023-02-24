<?php
/**
 * Class for storing messages
 *
 * SAM-2016: Performance - Success messages
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Jun 14, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\Messenger;

use Sam\Application\ApplicationAwareTrait;
use Sam\Application\UserDataStorage\UserDataStorageCreateTrait;
use Sam\Core\Service\CustomizableClass;
use stdClass;

/**
 * Class Service
 * @package Sam\Qform\Messenger
 */
class AdminMessenger extends CustomizableClass
{
    use ApplicationAwareTrait;
    use UserDataStorageCreateTrait;

    /** @var array */
    protected array $messageObjects = [];

    /** @var string[] */
    protected array $classes = [
        'warning' => 'alert',
        'success' => 'alert alert-success',
        'error' => 'alert alert-error',
        'info' => 'alert alert-info',
        'note' => 'alert alert-info',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->loadFromStorage();
        return $this;
    }

    /**
     * @return string
     */
    public function getSessionKey(): string
    {
        return $this->getApplication()->ui()->isWebAdmin()
            ? 'msg-admin'
            : 'msg-frontend';
    }

    /**
     * @return bool
     */
    public function hasMessages(): bool
    {
        return !empty($this->messageObjects);
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        $messageObjects = $this->messageObjects;
        $this->messageObjects = []; // clear
        $this->clearFromStorage();
        foreach ($messageObjects as $messageObj) {
            $messageObj->Ttl--;
            if ($messageObj->Ttl > 0) {
                $this->messageObjects[] = $messageObj;
            }
        }
        if (count($this->messageObjects)) {
            $this->saveInStorage();
        }
        return $messageObjects;
    }

    /**
     * Register message
     * @param string $message
     * @param string $type [error, info, success, warning]
     * @param int $ttl How many times we should keep message in session
     */
    public function addMessage(string $message, string $type = 'info', int $ttl = 1): void
    {
        $messageObj = new stdClass();
        $messageObj->CssClass = $this->classes[$type];
        $messageObj->Message = $message;
        $messageObj->Type = ucfirst($type);
        $messageObj->Ttl = $ttl;
        $this->messageObjects[] = $messageObj;
        if ($ttl > 0) {
            $this->saveInStorage();
        }
    }

    public function addSuccess(string $message, int $ttl = 1): void
    {
        $this->addMessage($message, 'success', $ttl);
    }

    public function addError(string $message, int $ttl = 1): void
    {
        $this->addMessage($message, 'error', $ttl);
    }

    public function addWarning(string $message, int $ttl = 1): void
    {
        $this->addMessage($message, 'warning', $ttl);
    }

    public function addInfo(string $message, int $ttl = 1): void
    {
        $this->addMessage($message, 'info', $ttl);
    }

    public function addNote(string $message, int $ttl = 1): void
    {
        $this->addMessage($message, 'note', $ttl);
    }

    /**
     * Register array of messages
     * @param string[] $messages
     * @param string $type
     * @param int $ttl
     */
    public function addMessages(array $messages, string $type = 'info', int $ttl = 1): void
    {
        foreach ($messages ?: [] as $message) {
            $this->addMessage($message, $type, $ttl);
        }
    }

    /**
     * Check if message already registered in messenger queue
     * @param string $message
     * @param string|null $type null for search message among all types
     * @return bool
     */
    public function isRegistered(string $message, ?string $type = null): bool
    {
        $isFound = false;
        if ($this->messageObjects) {
            foreach ($this->messageObjects as $messageObj) {
                if ((
                        $type === null
                        && $messageObj->Message === $message
                    ) || (
                        $type === $messageObj->Type
                        && $messageObj->Message === $message
                    )
                ) {
                    $isFound = true;
                }
            }
        }
        return $isFound;
    }

    /**
     * Snapshot messages without removing viewed messages
     * @return array
     */
    public function snapshotMessages(): array
    {
        return $this->messageObjects;
    }

    /**
     * Save messages in cookie or regular session storage
     */
    protected function saveInStorage(): void
    {
        $this->createUserDataStorage()->set($this->getSessionKey(), $this->messageObjects);
    }

    /**
     * Remove messages from cookie or regular session storage
     */
    protected function clearFromStorage(): void
    {
        $this->createUserDataStorage()->remove($this->getSessionKey());
    }

    /**
     * Load messages from cookie or regular session storage
     */
    protected function loadFromStorage(): void
    {
        $this->messageObjects = [];
        $messages = $this->createUserDataStorage()->get($this->getSessionKey());
        if ($messages) {
            foreach ($messages as $message) {
                $this->messageObjects[] = (object)$message;
            }
        }
    }
}
