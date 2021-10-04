<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\VersionControlAdapter\Gitlab\Request;

use Icanhazstring\RandomIssuePicker\VersionControlAdapter\RequestInterface;

class IssueSearchRequest implements RequestInterface
{
    /** @var int */
    private $page;

    /** @var int */
    private $resultsPerPage;

    /** @var string */
    private $label;

    /** @var string */
    private $repositoryId;

    public function __construct(int $page = 1, int $resultsPerPage = 100, string $label = '', string $repositoryId = '')
    {
        $this->page = $page;
        $this->resultsPerPage = $resultsPerPage;
        $this->label = $label;
        $this->repositoryId = $repositoryId;
    }

    public function getMethod(): string
    {
        return RequestInterface::METHOD_GET;
    }

    public function getUrl(): string
    {
        return 'https://gitlab.com/api/v4/projects/' . $this->repositoryId . '/issues';
    }

    /**
     * @return array<string, string|int>
     */
    public function getQueryParameters(): array
    {
        return [
            'per_page' => $this->resultsPerPage,
            'page' => $this->page,
            'state' => 'opened',
            'labels' => $this->label
        ];
    }
}
