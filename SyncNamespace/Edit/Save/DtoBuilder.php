<?php
/**
 * SAM-5826: Decouple SyncNamespace Editor to classes and add unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Feb 19, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SyncNamespace\Edit\Save;

use Sam\Core\Service\CustomizableClass;
use Laminas\Diactoros\ServerRequest;
use Sam\Core\Constants\Admin\SyncNamespaceListFormConstants;
use Sam\Core\Data\TypeCast\Cast;

/**
 * Class DtoBuilder
 * @package Sam\SyncNamespace\Edit
 */
class DtoBuilder extends CustomizableClass
{

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param ServerRequest $psrRequest
     * @return Dto
     */
    public function fromPsrRequest(ServerRequest $psrRequest): Dto
    {
        $body = $psrRequest->getParsedBody();
        $name = Cast::toString($body[SyncNamespaceListFormConstants::CID_TXT_NAME] ?? null);
        $id = Cast::toString($body[SyncNamespaceListFormConstants::CID_HID_SYNC_NAMESPACE_ID] ?? null);
        $dto = Dto::new()
            ->setName($name)
            ->setSyncNamespaceId($id);
        return $dto;
    }
}
