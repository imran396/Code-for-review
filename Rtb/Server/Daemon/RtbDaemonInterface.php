<?php
/**
 * SAM-9446: SAM migration to PHP 8.1: RTBD adjustments
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Server\Daemon;

interface RtbDaemonInterface
{
    public function construct(): self;

    public function process(): void;

    public function getInfo(): array;

    public function setPidFileName(string $filename): self;

    public function getPidFileName(): string;

    public function setName(string $name): self;

    public function getName(): string;
}
