<?php

namespace Icanhazstring\RandomIssuePicker\VersionControlAdapter;

interface VersionControlAdapterInterface
{
    /**
     * @param array<string> $topics
     */
    public function findRandomRepository(string $language, array $topics): ?RepositoryModelInterface;

    public function findRandomIssueFromRepository(RepositoryModelInterface $repository, string $label): ?IssueModelInterface;
}
