<?php

declare(strict_types=1);

namespace Kenny1911\Populate\ObjectAccessor\PropertiesExtractor;

class CallablePropertiesExtractor implements PropertiesExtractorInterface
{
    private $internal;

    private $callback;

    public function __construct(PropertiesExtractorInterface $internal, callable $callback)
    {
        $this->internal = $internal;
        $this->callback = $callback;
    }

    /**
     * @inheritDoc
     */
    public function getProperties($src): array
    {
        return array_values(
            array_filter($this->internal->getProperties($src), $this->callback)
        );
    }
}