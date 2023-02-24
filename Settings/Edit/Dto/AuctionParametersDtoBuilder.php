<?php
/**
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Edit\Dto;

use Laminas\Diactoros\ServerRequest;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\Edit\Mutual\AuctionParametersMutualContext;

/**
 * Class AuctionParametersDtoBuilder
 * @package Sam\Settings\Edit\Dto
 */
class AuctionParametersDtoBuilder extends CustomizableClass
{
    private AuctionParametersMutualContext $context;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AuctionParametersMutualContext $context
     * @return static
     */
    public function construct(AuctionParametersMutualContext $context): static
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @param ServerRequest $psrRequest
     * @param array $requestToPropertyMap
     * @return AuctionParametersDto
     */
    public function applyPsrRequestDataToContextDto(ServerRequest $psrRequest, array $requestToPropertyMap): AuctionParametersDto
    {
        $dto = $this->context->getDto();
        $postFields = $psrRequest->getParsedBody();
        $this->initBooleanProperties($requestToPropertyMap);
        foreach ($postFields as $fieldName => $value) {
            $property = $requestToPropertyMap[$fieldName] ?? null;
            if ($property) {
                $dto->{$property} = $value;
            }
        }
        return $dto;
    }

    /**
     * @param array $expectedProperties
     */
    private function initBooleanProperties(array $expectedProperties): void
    {
        $dto = $this->context->getDto();
        foreach ($expectedProperties as $property) {
            if (Constants\Setting::$typeMap[$property]['type'] === Constants\Type::T_BOOL) {
                $dto->{$property} = '0';
            }
        }
    }

    /**
     * @param string $option
     * @param string $value
     * @return AuctionParametersDto
     */
    public function applyCliOptionValueToContextDto(string $option, string $value): AuctionParametersDto
    {
        $dto = $this->context->getDto();
        $dto->{$option} = $value;
        return $dto;
    }

    /**
     * @return AuctionParametersMutualContext
     */
    public function getContext(): AuctionParametersMutualContext
    {
        return $this->context;
    }
}
