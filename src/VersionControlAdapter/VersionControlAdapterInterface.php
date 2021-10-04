<?php

namespace Icanhazstring\RandomIssuePicker\VersionControlAdapter;

use GuzzleHttp\Client;

interface VersionControlAdapterInterface
{
    public function __construct(Client $client);

    /**
     * @param array<string> $topics
     */
    public function findRandomRepository(string $language, array $topics): ?RepositoryModelInterface;

    public function findRandomIssueFromRepository(RepositoryModelInterface $repository, string $label): ?IssueModelInterface;
}
