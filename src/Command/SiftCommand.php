<?php
namespace Salines\Sift\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\ConnectionManager;

/**
 * Sift command.
 */
class SiftCommand extends Command
{
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser = parent::buildOptionParser($parser);
        
        $parser->addOptions([
            'table' => [
                'short' => 't',
                'help' => 'Sift single db table "bin/cake sift -t users"',
                'default' => 'all',
            ],
            'connection' => [
                'short' => 'c',
                'help' => 'Use other DB connection "bin/cake sift -c remote"',
                'default' => 'default',
            ]
        ]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $findTable = $args->getOption('table');
        $connection = $args->getOption('connection');

        $connections = ConnectionManager::get($connection);
        $tables = $connections->getSchemaCollection()->listTables();

        if ($tables && $findTable == 'all') {
            foreach ($tables as $table) {
                $this->getColumns($connections, $table, $io);
            }
        } elseif ($tables && $findTable != 'all') {
            $this->getColumns($connections, $findTable, $io);
        } else {
            $io->info('>> ' . __('Does not detect any db table'));
        }
    }

    /**
     * GetColumns function
     *
     * @param \Cake\Console\ConsoleIo $io The console io
     * @param \Cake\Datasource\ConnectionInterface $connections
     * @param string $table
     * @return void
     */
    private function getColumns($connections, $table, $io)
    {
        $countRecords =  $connections->newQuery()->select('count(*) AS count')->from($table)->execute()->fetchAll('assoc');
        $th = $table . substr(str_repeat(" ", 40), strlen($table));
        if (intval($countRecords[0]['count']) > 0) {
            $tableSchema = $connections->getSchemaCollection()->describe($table);
            $columns = $tableSchema->columns();
            $data = [];
            array_push($data, ['TABLE ' . $th]);
            foreach ($columns as $column) {
                if ($this->isEmpty($connections, $table, $column, $io)) {
                    array_push($data, ['- ' . $column]);      
                }
            }

            if (count($data) > 1) {
                $io->helper('Table')->output($data);
            }
        } else {
            $nodata = [];
            array_push($nodata, ['TABLE ' . $th]);
            array_push($nodata, ['>> ' . __('Empty table')]);
            $io->helper('Table')->output($nodata);
        }
    }

    /**
     * IsEmpty function
     *
     * @param \Cake\Datasource\ConnectionInterface $connections
     * @param string $table Table name 
     * @param string $column Column name
     * @return boolean
     */
    private function isEmpty($connections, $table, $column)
    {
        $count = $connections
            ->newQuery()
            ->select('count(*) AS count', $column)
            ->from($table)
            ->where([
                'OR' => [
                    sprintf('%s IS NOT NULL', $column),
                    sprintf('%s !=', $column) => ''
                ]        
            ])
            ->execute()
            ->fetchAll('assoc');
        return intval($count[0]['count']) === 0 ? true : false;
    }
}
