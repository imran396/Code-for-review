<?php
/**
 * SAM-9538: Decouple ACL checking logic from front controller
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Acl\Protect\Internal\Access;

use Sam\Core\Application\Ui\Ui;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AclControllerCheckingInput
 * @package Sam\Application\Acl\Protect\Internal\Access
 */
class AclControllerCheckingInput extends CustomizableClass
{
    public ?int $editorUserId;
    public int $systemAccountId;
    public Ui $ui;
    public string $controller;
    public string $action;
    public ?int $resourceEntityId;
    public string $requestMode;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @param Ui $ui
     * @param string $controller
     * @param string $action
     * @param int|null $resourceEntityId
     * @param string $requestMode
     * @return $this
     */
    public function construct(
        ?int $editorUserId,
        int $systemAccountId,
        Ui $ui,
        string $controller,
        string $action,
        ?int $resourceEntityId,
        string $requestMode
    ): static {
        $this->editorUserId = $editorUserId;
        $this->systemAccountId = $systemAccountId;
        $this->ui = $ui;
        $this->controller = $controller;
        $this->action = $action;
        $this->resourceEntityId = $resourceEntityId;
        $this->requestMode = $requestMode;
        return $this;
    }
}
