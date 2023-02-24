<?php
/**
 * SAM-7841: Refactor BidPath_Stream
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Video;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * Class VideoStream
 * @package Sam\Rtb\Video
 */
class VideoStream extends CustomizableClass
{
    use OptionalsTrait;

    public const OP_ENABLED = 'enabled';
    public const OP_CLIENT_ID = 'clientId';
    public const OP_PLAYER_URL = 'playerUrl';
    public const OP_PLAYER_WIDTH = 'playerWidth';
    public const OP_PLAYER_HEIGHT = 'playerHeight';
    public const OP_ENCRYPTION_KEY = 'encryptionKey';
    public const OP_ENCRYPTION_VECTOR = 'encryptionVector';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Get Html to render player
     *
     * @param string $streamDisplay 1: av, 2: v, 3: a
     * @param int $auctionId auction.id
     * @return string player html
     */
    public function getPlayerHtml(string $streamDisplay, int $auctionId): string
    {
        if (!$this->fetchOptional(self::OP_ENABLED)) {
            return '';
        }

        $output = sprintf(
            '<iframe id="bidpathplayer" class="bidpathplayertype-%s" allow="autoplay; fullscreen" allowfullscreen="allowfullscreen" src="%s" sandbox="allow-scripts allow-same-origin"></iframe>',
            $streamDisplay,
            $this->makePlayerUrl($streamDisplay, $auctionId)
        );
        $output = str_replace('=', '~', $output);
        return $output;
    }

    /**
     * @param $streamDisplay 1: av, 2: v, 3: a
     * @param int $auctionId
     * @return string
     */
    protected function makePlayerUrl(string $streamDisplay, int $auctionId): string
    {
        $request = http_build_query(
            [
                'ClientID' => $this->fetchOptional(self::OP_CLIENT_ID),
                'LiveEventId' => $auctionId,
                'Width' => $this->fetchOptional(self::OP_PLAYER_WIDTH),
                'Height' => $this->fetchOptional(self::OP_PLAYER_HEIGHT),
                'RequiredAuth' => 0,
                'StreamType' => $streamDisplay, //1 audio-video, 2 video only, 3 audio only
                'StreamProtocolType' => 2
            ]
        );
        log_debug(composeLogData(['Request' => $request]));
        $encryptedRequest = $this->encryptRequest($request);
        $base64encodedRequest = base64_encode($encryptedRequest);
        log_debug(composeLogData(['Encrypted & Base64 Encoded' => $base64encodedRequest]));
        $url = sprintf('%s?qs=%s', $this->fetchOptional(self::OP_PLAYER_URL), urlencode($base64encodedRequest));
        return $url;
    }

    /**
     * @param string $request
     * @return string
     */
    protected function encryptRequest(string $request): string
    {
        // convert to binary string
        $keyBin = pack('H*', $this->fetchOptional(self::OP_ENCRYPTION_KEY));
        $vectorBin = pack('H*', $this->fetchOptional(self::OP_ENCRYPTION_VECTOR));

        // PKCS#7 padding
        //Hardcoded 16, cause there is no functions in openssl library to get block size
        $block = 16;
        $len = strlen($request);
        $padding = $block - ($len % $block);
        $paddedRequest = $request . str_repeat(chr($padding), $padding);

        /** @noinspection EncryptionInitializationVectorRandomnessInspection */
        $encrypted = openssl_encrypt(
            $paddedRequest,
            'aes-256-cbc',
            $keyBin,
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            $vectorBin
        );
        return $encrypted;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_ENABLED] = $optionals[self::OP_ENABLED]
            ?? static function (): bool {
                return ConfigRepository::getInstance()->get('core->vendor->bidpathStreaming->enabled');
            };
        $optionals[self::OP_CLIENT_ID] = $optionals[self::OP_CLIENT_ID]
            ?? static function (): string {
                return ConfigRepository::getInstance()->get('core->vendor->bidpathStreaming->clientId');
            };
        $optionals[self::OP_PLAYER_URL] = $optionals[self::OP_PLAYER_URL]
            ?? static function (): string {
                return ConfigRepository::getInstance()->get('core->vendor->bidpathStreaming->playerUrl');
            };
        $optionals[self::OP_PLAYER_WIDTH] = $optionals[self::OP_PLAYER_WIDTH]
            ?? static function (): string {
                return ConfigRepository::getInstance()->get('core->vendor->bidpathStreaming->playerWidth');
            };
        $optionals[self::OP_PLAYER_HEIGHT] = $optionals[self::OP_PLAYER_HEIGHT]
            ?? static function (): string {
                return ConfigRepository::getInstance()->get('core->vendor->bidpathStreaming->playerHeight');
            };
        $optionals[self::OP_ENCRYPTION_KEY] = $optionals[self::OP_ENCRYPTION_KEY]
            ?? static function (): string {
                return ConfigRepository::getInstance()->get('core->vendor->bidpathStreaming->encryptionKey');
            };
        $optionals[self::OP_ENCRYPTION_VECTOR] = $optionals[self::OP_ENCRYPTION_VECTOR]
            ?? static function (): string {
                return ConfigRepository::getInstance()->get('core->vendor->bidpathStreaming->encryptionVector');
            };
        $this->setOptionals($optionals);
    }
}
