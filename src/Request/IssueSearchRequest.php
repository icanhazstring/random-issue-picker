<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\Request;

use Icanhazstring\RandomIssuePicker\Model\SearchIssueModel;

class IssueSearchRequest implements RequestInterface
{
    /** @var int */
    private $page;

    /** @var int */
    private $resultsPerPage;

    /** @var string */
    private $label;

    /** @var string */
    private $repository;

    public function __construct(int $page = 1, int $resultsPerPage = 100, string $label = '', string $repository = '')
    {
        $this->page = $page;
        $this->resultsPerPage = $resultsPerPage;
        $this->label = $label;
        $this->repository = $repository;
    }

    public function getMethod(): string
    {
        return RequestInterface::METHOD_GET;
    }

    public function getUrl(): string
    {
        return 'https://api.github.com/search/issues';
    }

    public function getQueryParameters(): array
    {
        $queryString = '';
        if (!empty($this->repository)) {
            $queryString.= 'repo: ' . $this->repository . ' ';
        }
        if (!empty($this->label)) {
            $queryString.= 'label: ' . $this->label . ' ';
        }

        return [
            'query' => [
                'q' => $queryString . 'is:open is:issue sort:created-desc',
                'per_page' => $this->resultsPerPage,
                'page' => $this->page
            ]
        ];
    }

    public function getResponseModel(): string
    {
        return SearchIssueModel::class;
    }
}