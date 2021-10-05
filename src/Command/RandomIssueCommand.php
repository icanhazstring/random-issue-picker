<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\Command;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Icanhazstring\RandomIssuePicker\VersionControlAdapter\Github;
use Icanhazstring\RandomIssuePicker\VersionControlAdapter\Gitlab;
use Icanhazstring\RandomIssuePicker\Writer\IssueWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RandomIssueCommand extends Command
{
    /** @var Client */
    private $client;

    /** @var SymfonyStyle */
    private $io;

    public function __construct()
    {
        parent::__construct('random:issue');

        $this->client = new Client();
    }

    protected function configure(): void
    {
        $this->setDescription('Select a random issue and show link and description.');

        $this->addOption(
            'topic',
            't',
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Which topic(s) should the repository have?',
            []
        );

        $this->addOption(
            'language',
            'l',
            InputOption::VALUE_OPTIONAL,
            'Which language do you look for e.g. php, js, java, default is php'
        );

        $this->addOption(
            'label',
            '',
            InputOption::VALUE_OPTIONAL,
            'Do you want issues for #hacktoberfest?'
        );

        $this->addOption(
            'source',
            's',
            InputOption::VALUE_REQUIRED,
            'Which source should be used? Currently github and gitlab are supported',
            'github'
        );
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        /** @var string|null $languageInput */
        $languageInput = $input->getOption('language');
        /** @var string|null $labelInput */
        $labelInput = $input->getOption('label');
        /** @var string[]|null topicsInput */
        $topicsInput = $input->getOption('topic');

        $language = $languageInput ?? 'php';
        $label = $labelInput ?? '';
        $topics = !empty($topicsInput) ? $topicsInput : ['hacktoberfest'];

        switch (strtolower($input->getOption('source'))) {
            case 'gitlab':
                $provider = new Gitlab($this->client, $_ENV['GITLAB_PAT'] ?? null);
                break;
            case 'github':
            default:
                $provider = new Github($this->client);
                break;
        }

        $randomRepository = $provider->findRandomRepository($language, $topics);
        if ($randomRepository === null) {
            $this->io->warning(
                sprintf(
                    'No repositories found with language "%s" and topics "%s"',
                    $language,
                    implode(',', $topics)
                )
            );

            return 0;
        }

        $randomIssue = $provider->findRandomIssueFromRepository($randomRepository, $label);

        if (!$randomIssue) {
            $this->io->warning("No issue was found with your language: $language and label: $label");

            return 0;
        }

        (new IssueWriter($output, $this->io, $randomIssue))->write();

        return 0;
    }
}
