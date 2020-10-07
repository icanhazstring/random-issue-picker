<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\Request;

interface RequestInterface
{
    public const METHOD_GET = 'GET';

    public function getMethod(): string;

    public function getUrl(): string;

    /** @return array<string, array<string, int|string>> */
    public function getQueryParameters(): array;
}
