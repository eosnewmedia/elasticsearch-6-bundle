<?php
declare(strict_types=1);

namespace Enm\Bundle\Elasticsearch\Command;

use Enm\Elasticsearch\DocumentManagerInterface;
use Enm\Elasticsearch\Exception\ElasticsearchException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class AbstractIndexCommand extends Command
{
    /**
     * @var DocumentManagerInterface
     */
    private $elasticsearch;

    /**
     * @var string[]
     */
    private $types;

    /**
     * @param string $command
     * @param DocumentManagerInterface $elasticsearch
     * @param string[] $types
     * @throws \Exception
     */
    public function __construct(string $command, DocumentManagerInterface $elasticsearch, array $types)
    {
        parent::__construct('enm:elasticsearch:index:' . $command);
        $this->elasticsearch = $elasticsearch;
        $this->types = $types;

        $this->addOption('type', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY);
    }

    /**
     * @return DocumentManagerInterface
     */
    protected function documentManager(): DocumentManagerInterface
    {
        return $this->elasticsearch;
    }

    /**
     * @param InputInterface $input
     * @return array
     * @throws \Exception
     */
    protected function getTypes(InputInterface $input): array
    {
        $types = [];
        foreach ((array)$input->getOption('type') as $type) {
            $types[] = $this->classNameForType($type);
        }

        return $types;
    }

    /**
     * @param string $type
     * @return string
     * @throws ElasticsearchException
     */
    private function classNameForType(string $type): string
    {
        if (\class_exists($type)) {
            return $type;
        }

        if (\in_array($type, $this->types, true)) {
            return \array_search($type, $this->types, true);
        }

        throw new ElasticsearchException('Invalid type given!');
    }
}
