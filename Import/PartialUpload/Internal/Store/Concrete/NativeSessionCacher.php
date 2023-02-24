<?php
/**
 * Adapter for caching with help of native php session storage
 *
 * SAM-6575: Lot Csv Import - Extract session operations to separate adapter
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\PartialUpload\Internal\Store\Concrete;

use Sam\Core\Service\CustomizableClass;
use Sam\Import\PartialUpload\Internal\Model\PartialUploadDto;

/**
 * Class NativeSessionCacher
 */
class NativeSessionCacher extends CustomizableClass implements CacherInterface
{
    protected string $type = '';

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return PartialUploadDto
     */
    public function get(): PartialUploadDto
    {
        $dto = $_SESSION[self::KEY][$this->type] ?? null;
        if (!is_a($dto, PartialUploadDto::class)) {
            $dto = PartialUploadDto::new();
        }
        return $dto;
    }

    /**
     * Check, if original user registered in session
     * @return bool
     */
    public function has(): bool
    {
        return isset($_SESSION[self::KEY][$this->type]);
    }

    /**
     * @param PartialUploadDto $dto
     */
    public function set(PartialUploadDto $dto): void
    {
        $_SESSION[self::KEY][$this->type] = $dto;
    }

    /**
     * @param string $type
     * @return static
     */
    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function delete(): void
    {
        unset($_SESSION[self::KEY][$this->type]);
    }
}
