<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\VersionControlAdapter;

interface RequestInterface
{
    public const METHOD_GET = 'GET';

    public function getMethod(): string;

    public function getUrl(): string;

    /** @return array<string, mixed> */
    public function getQueryParameters(): array;
}
