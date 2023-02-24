<?php
/**
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Metadata;

/**
 * Database column type model
 *
 * Class ColumnType
 * @package Sam\Storage\Metadata
 */
class DatabaseColumnType
{
    private string $name;
    private ?string $length = null;
    private ?array $enumChoices = null;
    private ?bool $unsigned = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getLength(): ?string
    {
        return $this->length;
    }

    /**
     * @param string|null $length
     * @return static
     */
    public function setLength(?string $length): static
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getEnumChoices(): ?array
    {
        return $this->enumChoices;
    }

    /**
     * @param array|null $enumChoices
     * @return static
     */
    public function setEnumChoices(?array $enumChoices): static
    {
        $this->enumChoices = $enumChoices;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getUnsigned(): ?bool
    {
        return $this->unsigned;
    }

    /**
     * @param bool|null $unsigned
     * @return static
     */
    public function setUnsigned(?bool $unsigned): static
    {
        $this->unsigned = $unsigned;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBoolean(): bool
    {
        return $this->getLength() === '1'
            && in_array(
                $this->getName(),
                [
                    DatabaseColumnTypeName::TINYINT,
                    DatabaseColumnTypeName::BIT
                ],
                true
            );
    }

    /**
     * @return bool
     */
    public function isInteger(): bool
    {
        return match ($this->getName()) {
            DatabaseColumnTypeName::BIGINT,
            DatabaseColumnTypeName::INT,
            DatabaseColumnTypeName::MEDIUMINT,
            DatabaseColumnTypeName::SMALLINT,
            DatabaseColumnTypeName::TINYINT,
            DatabaseColumnTypeName::BIT => true,
            default => false,
        };
    }

    /**
     * @return bool
     */
    public function isFloat(): bool
    {
        return match ($this->getName()) {
            DatabaseColumnTypeName::FLOAT,
            DatabaseColumnTypeName::DECIMAL,
            DatabaseColumnTypeName::DOUBLE => true,
            default => false,
        };
    }

    public function isBlob(): bool
    {
        return match ($this->getName()) {
            DatabaseColumnTypeName::BLOB,
            DatabaseColumnTypeName::LONGBLOB,
            DatabaseColumnTypeName::MEDIUMBLOB,
            DatabaseColumnTypeName::TINYBLOB => true,
            default => false,
        };
    }

    /**
     * @return bool
     */
    public function isDate(): bool
    {
        return match ($this->getName()) {
            DatabaseColumnTypeName::DATE,
            DatabaseColumnTypeName::DATETIME,
            DatabaseColumnTypeName::TIME,
            DatabaseColumnTypeName::YEAR => true,
            default => false,
        };
    }

    /**
     * @return bool
     */
    public function isTimestamp(): bool
    {
        return $this->getName() === DatabaseColumnTypeName::TIMESTAMP;
    }

    public function isText(): bool
    {
        return match ($this->getName()) {
            DatabaseColumnTypeName::CHAR,
            DatabaseColumnTypeName::LONGTEXT,
            DatabaseColumnTypeName::MEDIUMTEXT,
            DatabaseColumnTypeName::TEXT,
            DatabaseColumnTypeName::TINYTEXT,
            DatabaseColumnTypeName::VARCHAR => true,
            default => false,
        };
    }
}
