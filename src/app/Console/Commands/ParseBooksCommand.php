<?php

namespace App\Console\Commands;

use App\Services\BookParserServiceInterface;
use Illuminate\Console\Command;

class ParseBooksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:parse {url? : The URL to parse books from}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse books from a JSON URL and update the database';

    /**
     * Execute the console command.
     */
    public function handle(BookParserServiceInterface $bookParserService)
    {
        $url = $this->argument('url') ?? 'https://raw.githubusercontent.com/bvaughn/infinite-list-reflow-examples/refs/heads/master/books.json';

        $this->info("Parsing books from: {$url}");

        try {
            $data = $bookParserService->parseFromUrl($url);
            $this->info("Found " . count($data) . " books in the JSON data");

            $this->info("Updating database...");
            $results = $bookParserService->updateOrCreateBooks($data);

            $this->info("Successfully processed " . count($results['success']) . " books");

            if (!empty($results['errors'])) {
                $this->warn("Encountered " . count($results['errors']) . " errors:");
                foreach ($results['errors'] as $error) {
                    $this->error("ISBN: {$error['isbn']} - Error: {$error['error']}");
                }
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to parse books: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
