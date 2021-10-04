<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\VersionControlAdapter;

interface RepositoryModelInterface
{
    public function getFullName(): string;

    public function hasOpenIssues(): bool;
}
