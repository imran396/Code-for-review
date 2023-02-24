<?php
/**
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           7/6/2016
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Response;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * Class JsonResponse
 * @package Sam\Application\Controller\Response
 */
class JsonResponse implements JsonSerializable, IteratorAggregate
{
    private string|array $payload;
    private array $debugMessages = [];
    private array $infoMessages = [];
    private array $warnMessages = [];
    private array $errMessages = [];
    private array $successMessages = [];

    /**
     * JsonResponse constructor.
     * @param string|array $payload
     * @param array $errors
     */
    public function __construct(string|array $payload = [], array $errors = [])
    {
        $this->payload = $payload;
        $this->addErr($errors);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return ArrayIterator|Traversable
     */
    public function getIterator(): ArrayIterator|Traversable
    {
        return new ArrayIterator($this->toArray());
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $isSuccess = $this->isSuccessful();

        $data = [];

        $data['success'] = $isSuccess;
        $data['msg'] = $this->getAllMessages();

        if ($isSuccess) {
            $data['payload'] = $this->payload;
        }

        return $data;
    }

    /**
     * @return bool
     */
    protected function isSuccessful(): bool
    {
        $isSuccess = empty($this->warnMessages) && empty($this->errMessages);
        return $isSuccess;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return $this->warnMessages || $this->errMessages;
    }

    /**
     * @return array{debug?:string[], info?:string[], warn?:string[], errors?:string[], success?:string[]}
     */
    protected function getAllMessages(): array
    {
        $errors = [];
        if ($this->debugMessages) {
            $errors['debug'] = $this->debugMessages;
        }
        if ($this->infoMessages) {
            $errors['info'] = $this->infoMessages;
        }
        if ($this->warnMessages) {
            $errors['warn'] = $this->warnMessages;
        }
        if ($this->errMessages) {
            $errors['errors'] = $this->errMessages;
        }
        if ($this->successMessages && $this->isSuccessful()) {
            $errors['success'] = $this->successMessages;
        }
        return $errors;
    }

    /**
     * @param string|string[] $debugMessages
     * @return static
     */
    public function addDebug(string|array $debugMessages): static
    {
        $this->addMessages($this->debugMessages, $debugMessages);
        return $this;
    }

    /**
     * @param string|string[] $infoMessages
     * @return static
     */
    public function addInfo(string|array $infoMessages): static
    {
        $this->addMessages($this->infoMessages, $infoMessages);
        return $this;
    }

    /**
     * @param string|string[] $warnMessages
     * @return static
     */
    public function addWarn(string|array $warnMessages): static
    {
        $this->addMessages($this->warnMessages, $warnMessages);
        return $this;
    }

    /**
     * @param string|string[] $successMessages
     * @return static
     */
    public function addSuccess(string|array $successMessages): static
    {
        $this->addMessages($this->successMessages, $successMessages);
        return $this;
    }

    /**
     * @param string|array<int|string|float|null> $errMessages
     * @return static
     */
    public function addSuccessArray(string|array $errMessages): static
    {
        $this->successMessages[] = $errMessages;
        return $this;
    }

    /**
     * @param string|string[] $errMessages
     * @return static
     */
    public function addErr(string|array $errMessages): static
    {
        $this->addMessages($this->errMessages, $errMessages);
        return $this;
    }

    /**
     * @param array $errMessages
     * @return static
     */
    public function addErrArray(array $errMessages): static
    {
        $this->errMessages[] = $errMessages;
        return $this;
    }

    /**
     * @param array $messageArr
     * @param string|string[] $messages
     */
    protected function addMessages(array &$messageArr, string|array $messages): void
    {
        if (is_array($messages)) {
            foreach ($messages as $message) {
                $message = (string)$message;
                if ($message) {
                    $messageArr[] = $message;
                }
            }
        } else {
            $message = $messages;
            if ($message) {
                $messageArr[] = $message;
            }
        }
    }

    /**
     * @param array $debugMessages
     * @return static
     */
    public function setDebugMessages(array $debugMessages): static
    {
        $this->debugMessages = $debugMessages;
        return $this;
    }

    /**
     * @return static
     */
    public function clearAllMessages(): static
    {
        $this->debugMessages = [];
        $this->infoMessages = [];
        $this->warnMessages = [];
        $this->errMessages = [];
        $this->successMessages = [];
        return $this;
    }

    /**
     * @return static
     */
    public function clearPayload(): static
    {
        $this->payload = [];
        return $this;
    }

    /**
     * @return static
     */
    public function clearAll(): static
    {
        $this->clearPayload();
        $this->clearAllMessages();
        return $this;
    }

    /**
     * @return string|array
     */
    public function getPayload(): string|array
    {
        return $this->payload;
    }

    /**
     * @param array|string $payload
     * @return static
     */
    public function setPayload(string|array $payload): static
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)json_encode($this);
    }
}
