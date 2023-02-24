<?php
/**
 * Common interface for all cachers (array, file, session)
 *
 * SAM-6575: Lot Csv Import - Extract session operations to separate adapter
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\PartialUpload\Internal\Store\Concrete;

use Sam\Core\Constants;
use Sam\Import\PartialUpload\Internal\Model\PartialUploadDto;

interface CacherInterface
{
    /**
     * @var string we store original data under this key
     */
    public const KEY = Constants\SessionCache::PARTIAL_UPLOAD;

    /**
     * Read original data from storage
     * @return PartialUploadDto
     */
    public function get(): PartialUploadDto;

    /**
     * Check if original data exists in storage
     * @return bool
     */
    public function has(): bool;

    /**
     * Write original data to storage
     * @param PartialUploadDto $dto
     */
    public function set(PartialUploadDto $dto): void;

    /**
     * @param string $type
     * @return self
     */
    public function setType(string $type): self;

    /**
     * Remove original data from storage
     */
    public function delete(): void;
}
