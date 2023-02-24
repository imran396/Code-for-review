<?php
/**
 * SAM-4697: Feed entity editor
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           3/22/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feed\Edit\Save;

use Laminas\Diactoros\ServerRequest;
use Sam\Core\Constants\Admin\FeedEditFormConstants;
use Sam\Core\Dto\StringDtoBuilderBase;
use Sam\Core\Constants;

/**
 * Class DtoBuilder
 * @package
 */
class DtoBuilder extends StringDtoBuilderBase
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Build DTO from PSR request data.
     * Don't trim header, footer, glue, repetition.
     * @param $psrRequest ServerRequest
     * @return Dto
     */
    public function fromPsrRequest(ServerRequest $psrRequest): Dto
    {
        $body = $psrRequest->getParsedBody();
        $dto = Dto::new();
        $dto->contentType = $this->readString(FeedEditFormConstants::CID_TXT_CONTENT_TYPE, $body);
        $dto->fileName = $this->readString(FeedEditFormConstants::CID_TXT_FILENAME, $body);
        $dto->footer = $this->readString(FeedEditFormConstants::CID_TXT_FOOTER, $body, Constants\Type::F_STRING);
        $dto->glue = $this->readString(FeedEditFormConstants::CID_TXT_GLUE, $body, Constants\Type::F_STRING);
        $dto->header = $this->readString(FeedEditFormConstants::CID_TXT_HEADER, $body, Constants\Type::F_STRING);
        $dto->isHideEmptyFields = $this->readCheckbox(FeedEditFormConstants::CID_CHK_HIDE_EMPTY_FIELDS, $body);
        $dto->isIncludeInReports = $this->readCheckbox(FeedEditFormConstants::CID_CHK_INCLUDE_IN_REPORTS, $body);
        $dto->itemsPerPage = $this->readString(FeedEditFormConstants::CID_TXT_ITEMS_PER_PAGE, $body);
        $dto->name = $this->readString(FeedEditFormConstants::CID_TXT_NAME, $body);
        $dto->repetition = $this->readString(FeedEditFormConstants::CID_TXT_REPETITION, $body, Constants\Type::F_STRING);
        $dto->slug = $this->readString(FeedEditFormConstants::CID_TXT_SLUG, $body);
        return $dto;
    }
}
