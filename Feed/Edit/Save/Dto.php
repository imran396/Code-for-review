<?php
/**
 * SAM-4697: Feed entity editor
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 10, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Feed\Edit\Save;

use Sam\Core\Dto\StringDto;

/**
 * Class Dto
 * @package Sam\Feed\Edit
 * @property string|int $accountId
 * @property string $cacheTimeout
 * @property string $contentType
 * @property string|int $currencyId
 * @property string $encoding
 * @property string $escaping
 * @property string|int|null $feedId
 * @property string $feedType
 * @property string $fileName
 * @property string $footer
 * @property string $glue
 * @property string $header
 * @property string $isHideEmptyFields
 * @property string $isIncludeInReports
 * @property string $itemsPerPage
 * @property string $locale
 * @property string $name
 * @property string $repetition
 * @property string $slug
 */
class Dto extends StringDto
{
    /** @var string[] */
    protected array $availableFields = [
        'accountId',
        'cacheTimeout',
        'contentType',
        'currencyId',
        'encoding',
        'escaping',
        'feedId',
        'feedType',
        'fileName',
        'footer',
        'glue',
        'header',
        'isHideEmptyFields',
        'isIncludeInReports',
        'itemsPerPage',
        'locale',
        'name',
        'repetition',
        'slug',
    ];

    /**
     * Do NOT trim content fields
     * @var string[]
     */
    protected array $noTrimFields = [
        'footer',
        'glue',
        'header',
        'repetition',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Detects if it is supposed to be new or existing record.
     * Logic method in DTO.
     * @return bool
     */
    public function isNew(): bool
    {
        return !(int)$this->feedId;
    }
}

