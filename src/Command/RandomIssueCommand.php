<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\Command;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Icanhazstring\RandomIssuePicker\Model\SearchIssueModel;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\Table;

class RandomIssueCommand extends Command
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        parent::__construct('random:issue');
        $this->client = new Client();
    }

    protected function configure(): void
    {
        $this->setDescription('Select a random issue and show link and description.');

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
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $randomPageIndex = random_int(1, 10);
        $randomIssueIndex = random_int(0, 99);

        /** @var string|null $languageInput */
        $languageInput = $input->getOption('language');
        /** @var string|null  $labelInput */
        $labelInput = $input->getOption('label');

        $language = $languageInput ?? 'php';
        $label = $labelInput ?? 'good first issue';

        $res = $this->client->request('GET', 'https://api.github.com/search/issues', [
            'query' => [
                'q' => 'is:open is:issue language:' . $language . ' label:"' . $label . '" sort:created-desc',
                'per_page' => 100,
                'page' => $randomPageIndex
            ]
        ]);

        $serializer = SerializerBuilder::create()->build();
        /** @var SearchIssueModel $searchIssueModel */
        $searchIssueModel = $serializer->deserialize((string)$res->getBody(), SearchIssueModel::class, 'json');

        if (empty($searchIssueModel->getItems())) {
            $io->warning("No issue was found with your language: $language and label: $label");
            return 0;
        }

        $randomIssue = $searchIssueModel->getItems()[$randomIssueIndex];

        $randomIssueTitle = $randomIssue->getTitle();

        // cut off title at 90 characters
        if (strlen($randomIssue->getTitle()) > 90) {
            $randomIssueTitle = substr($randomIssue->getTitle(), 0, 90) . '...';
        }

        $randomIssueBody = $randomIssue->getBody();

        // cut off body at 300 characters
        if (strlen($randomIssue->getBody()) > 300) {
            $randomIssueBody = substr($randomIssue->getBody(), 0, 300) . '...';
        }

        // output details heading
        $io->writeln([
            "",
            "<comment> Details:</>",
            sprintf("<comment> %s</>", str_repeat("-", 60))
        ]);

        // create a table for displaying the title and link
        $table = new Table($output);
        $table->setHeaders([
            // label in white text, title in green text
            ['<fg=white>Title:</>', sprintf('<info>%s</>', $randomIssueTitle)]
        ]);
        $table->setRows([
            ['Link:', sprintf("<href=%s>%s</>", $randomIssue->getUrl(), $randomIssue->getUrl())]
        ]);
        $table->setStyle('box');
        // render table
        $table->render();

        // print body only if it is not empty
        if (strlen($randomIssue->getBody()) > 0) {
            // output issue heading
            $io->writeln([
                "",
                "<comment> Issue:</>",
                sprintf("<comment> %s</>", str_repeat("-", 60))
            ]);
            // wrap lines that are longer than 70 characters
            $randomIssueBodyLines = wordwrap($randomIssueBody, 70, "\n", true);
            // indent all lines with two spaces
            $randomIssueBodyLines = preg_replace("/(^|\n)/", "$1  ", $randomIssueBodyLines);
            // render body
            $io->writeln(strval($randomIssueBodyLines));
        }

        $io->newLine(1);

        return 0;
    }
}
