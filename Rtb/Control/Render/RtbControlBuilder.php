<?php
/**
 * SAM-6489: Rtb console control rendering at server side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 06, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Control\Render;

use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class RtbControlBuilder
 * @package Sam\Rtb\Control\Render
 * @method array buildButton(string $controlId, array $attributes = [])
 * @method array buildCheckbox(string $controlId, array $attributes = [])
 * @method array buildDiv(string $controlId, array $attributes = [])
 * @method array buildHidden(string $controlId, array $attributes = [])
 * @method array buildIframe(string $controlId, array $attributes = [])
 * @method array buildImg(string $controlId, array $attributes = [])
 * @method array buildLink(string $controlId, array $attributes = [])
 * @method array buildRadio(string $controlId, array $attributes = [])
 * @method array buildScript(string $controlId, array $attributes = [])
 * @method array buildSelect(string $controlId, array $attributes = [])
 * @method array buildSpan(string $controlId, array $attributes = [])
 * @method array buildTable(string $controlId, array $attributes = [])
 * @method array buildText(string $controlId, array $attributes = [])
 * @method array buildTextarea(string $controlId, array $attributes = [])
 * @method array buildUl(string $controlId, array $attributes = [])
 */
class RtbControlBuilder extends CustomizableClass
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
     * @param string $methodName
     * @param array $arguments
     * @return array
     */
    public function __call(string $methodName, array $arguments = []): array
    {
        if (preg_match('/build(\w+)/', $methodName, $matches)) {
            $type = strtolower($matches[1]);
            if (in_array($type, Constants\RtbConsole::CONTROL_TYPES, true)) {
                return $this->build($arguments[0], $type, $arguments[1] ?? []);
            }
            throw new InvalidArgumentException("Unknown rtb control type \"{$type}\"");
        }
        throw new InvalidArgumentException("Unknown invoked method \"{$methodName}\"");
    }

    /**
     * @param string $controlId
     * @param string $type
     * @param array $controlParams
     * @return array
     */
    public function build(string $controlId, string $type, array $controlParams = []): array
    {
        return array_merge(
            [
                'type' => $type,
                'id' => $controlId,
            ],
            $controlParams
        );
    }
}
