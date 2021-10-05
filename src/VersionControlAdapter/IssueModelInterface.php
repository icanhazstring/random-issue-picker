<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\VersionControlAdapter;

interface IssueModelInterface
{

    public function getUrl(): string;

    public function getTitle(): string;
    public function getBody(): string;

    public function getCreatedAt(): \DateTimeImmutable;

    public function getState(): string;

    /**
     * @return array<string>
     */
    public function getLabels(): array;
}
