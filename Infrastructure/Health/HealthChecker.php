<?php
/**
 * A basic health checker
 *
 * SAM-7956: Create a basic health check endpoint /health
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 31, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Health;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Infrastructure\Health\Internal\Validate\GeneralChecker;
use Sam\Infrastructure\Health\Internal\Validate\GeneralCheckerCreateTrait;
use Laminas\Diactoros\Response\TextResponse;
use Sam\Infrastructure\Http\Response\HttpResponseEmitter;

/**
 * Class HealthChecker
 * @package Sam\Infrastructure\Health
 */
class HealthChecker extends CustomizableClass
{
    use GeneralCheckerCreateTrait;
    use OptionalsTrait;

    public const OP_OUTPUT_MODE = 'outputMode'; // int
    public const OP_HEADERS = 'headers'; // string[]

    public const MODE_SHORT = 1;
    public const MODE_FULL = 2;

    protected const OUTPUT_MODE_DEF = self::MODE_SHORT;

    protected const HEADERS_DEF = [
        'Content-Type' => 'text/plain',
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
        'Pragma' => 'no-cache',
        'Expires' => '0',
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
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Check health, output and exit
     */
    public function run(): void
    {
        $generalChecker = $this->createGeneralChecker();
        if ($generalChecker->validate()) {
            $this->emitSuccess();
            return;
        }

        $this->emitFail($generalChecker);
    }

    /**
     * @param GeneralChecker $generalChecker
     */
    protected function emitFail(GeneralChecker $generalChecker): void
    {
        $outputMode = (int)$this->fetchOptional(self::OP_OUTPUT_MODE);
        $isFull = $outputMode === self::MODE_FULL;
        $text = 'FAIL' . ($isFull ? ': ' . $generalChecker->errorMessage() : '');
        $response = new TextResponse($text, 500, $this->headers());
        HttpResponseEmitter::new()->emit($response);
    }

    protected function emitSuccess(): void
    {
        $response = new TextResponse('OK', 200, $this->headers());
        HttpResponseEmitter::new()->emit($response);
    }

    protected function headers(): array
    {
        return (array)$this->fetchOptional(self::OP_HEADERS);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_OUTPUT_MODE] = $optionals[self::OP_OUTPUT_MODE] ?? self::OUTPUT_MODE_DEF;
        $optionals[self::OP_HEADERS] = $optionals[self::OP_HEADERS] ?? self::HEADERS_DEF;
        $this->setOptionals($optionals);
    }
}
