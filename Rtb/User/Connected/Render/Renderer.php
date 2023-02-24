<?php
/**
 * SAM-5752: Rtb connected user list builder
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\User\Connected\Render;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;

/**
 * Class RtbConnectedUserRenderer
 * @package Sam\Rtb\User\Connected
 */
class Renderer extends CustomizableClass
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
     * @param string $bidderNo
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     * @param string|null $companyName
     * @return string
     */
    public function makeUserLine(
        string $bidderNo,
        string $firstName,
        string $lastName,
        string $username,
        ?string $companyName = null
    ): string {
        $fullName = UserPureRenderer::new()->makeFullName($firstName, $lastName);
        return sprintf(
            '%s-%s (%s%s%s)',
            $bidderNo,
            $fullName,
            $username,
            empty($companyName) ? '' : ', ',
            $companyName
        );
    }

    /**
     * @param string $url
     * @param string $text
     * @param array $attributes
     * @return string
     */
    public function makeLink(string $url, string $text, array $attributes = []): string
    {
        $attributes = array_replace(['target' => '_blank'], $attributes);
        $attributes['href'] = $url;
        $link = sprintf('<a %s>%s</a>', $this->composeLinkAttributes($attributes), $text);
        return $link;
    }

    /**
     * @param array $attributes
     * @return string
     */
    protected function composeLinkAttributes(array $attributes): string
    {
        $formattedAttributes = [];
        foreach ($attributes as $attribute => $value) {
            $formattedAttributes[] = sprintf('%s="%s"', $attribute, $value);
        }
        return implode(' ', $formattedAttributes);
    }
}
