<?php
/**
 * General parent DTO for passing client's input data for entity creating/updating/validating.
 * Dto does not have to accurately describe the fields of the entity, it describes the incoming data from the external interface.
 * This object should contain only incoming data. Other configuration data is located in ConfigDto.
 *
 * Dto-Validator-Producer memo:
 * 1. Dto (data transfer object) is an object containing entity data, auction, lot, etc.
 * 2. Validator - validates data
 * 3. Producer - saves data into the database
 * Dto object is formed, than it's passed into Validator and if there are no errors it's passed into Producer which saves data into database:
 * if (Validator(Dto)) {
 *     Producer(Dto)
 * }
 *
 * The purpose is that: one code creates/updates entity everywhere in client code, implementation of DRY (don't repeat yourself) principle.
 * EntityMaker should be used to create/update account, auction, auctionLot, lot, lotCategory, user entities.
 *
 * SAM-10375: Input DTO adjustments and fixes for v3-7
 * SAM-8837: Lot item entity maker module structural adjustments for v3-5
 * SAM-3874 Refactor SOAP service and apply unit tests
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Sep 3, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Class Dto
 * @package Sam\EntityMaker\Base
 * @property int|string|null $id
 */
abstract class InputDto extends CustomizableClass
{
    /**
     * Store actual values of fields.
     * @var array
     */
    protected array $data = [];
    /**
     * Store previous values before modification of fields.
     * @var array
     */
    protected array $modified = [];

    // --- Mutation ---

    public function __unset(string $key): void
    {
        if (isset($this->$key)) {
            $this->modified[$key] = $this->$key;
        }
        unset($this->data[$key]);
    }

    /**
     * @param string $key
     * @param $value
     */
    public function __set(string $key, $value): void
    {
        if (isset($this->$key) && $this->$key !== $value) {
            $this->modified[$key] = $this->$key;
        }
        $this->data[$key] = $value;
    }

    /**
     * Assign array of values to Dto properties
     * @param array $fields
     * @return static
     */
    public function setArray(array $fields): static
    {
        foreach ($fields as $property => $value) {
            $property = lcfirst($property);
            $this->$property = $value;
        }
        return $this;
    }

    // --- Query ---

    public function __isset(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function __get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Get all array of Dto properties with values
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * Get fields
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->toArray());
    }

    /**
     * @return array
     */
    public function modified(): array
    {
        return $this->modified;
    }

    /**
     * All field names that presented in DTO
     * @return array
     */
    public function knownKeys(): array
    {
        return array_keys(array_merge($this->modified, $this->data));
    }
}
