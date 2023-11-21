<?php

namespace Ctors\PledgeRoutingBundle\Attribute;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Unveil
{
    public function __construct(
        public readonly ?string $path = null,
        public readonly ?string $permissions = null,
    ) {
    }
}
