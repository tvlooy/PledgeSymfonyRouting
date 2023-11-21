<?php

namespace Ctors\PledgeRoutingBundle\Attribute;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Pledge
{
    public function __construct(
        public readonly ?string $promises = null,
        public readonly ?string $execpromises = null,
    ) {
    }
}
