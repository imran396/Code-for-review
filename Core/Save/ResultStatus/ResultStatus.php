<?php
/**
 * SAM-5845: Adjust ResultStatusCollector
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 09, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Save\ResultStatus;

use Closure;
use Sam\Core\Save\ResultStatus\Exception\InvalidType;
use Sam\Core\Service\CustomizableClass;

/**
 * Result status value object
 *
 * Class ResultStatus
 * @package Sam\Core\Save\ResultStatus
 */
class ResultStatus extends CustomizableClass
{
    private int $type;
    private int $code;
    private string|Closure $message;
    private array $payload;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * ResultStatus constructor.
     * @param int $type
     * @param int $code
     * @param string|Closure $message
     * @param array $payload
     * @return static
     */
    public function construct(
        int $type,
        int $code,
        string|Closure $message,
        array $payload = []
    ): static {
        $this->assertType($type);
        $this->type = $type;
        $this->code = $code;
        $this->message = $message;
        $this->payload = $payload;
        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        if (is_callable($this->message)) {
            return ($this->message)($this);
        }
        return $this->message;
    }

    public function setMessage(string|Closure $message): static
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @return bool
     */
    public function isInfo(): bool
    {
        return $this->getType() === ResultStatusConstants::TYPE_INFO;
    }

    /**
     * @return bool
     */
    public function isWarning(): bool
    {
        return $this->getType() === ResultStatusConstants::TYPE_WARNING;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->getType() === ResultStatusConstants::TYPE_ERROR;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->getType() === ResultStatusConstants::TYPE_SUCCESS;
    }

    /**
     * @return string
     */
    public function typeName(): string
    {
        return $this->nameByType($this->getType());
    }

    /**
     * @param int $type
     * @return string
     */
    public function nameByType(int $type): string
    {
        return ResultStatusConstants::TYPE_NAMES[$type] ?? '';
    }

    /**
     * @param int $type
     */
    protected function assertType(int $type): void
    {
        if (!isset(ResultStatusConstants::TYPE_NAMES[$type])) {
            throw InvalidType::withDefaultMessage($type);
        }
    }
}
