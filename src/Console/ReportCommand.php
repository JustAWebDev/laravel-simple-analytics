<?php

namespace JustAWebDev\Analytics\Console;

use Illuminate\Console\Command;

class ReportCommand extends Command
{
    protected $signature = 'analytics:report';
    protected $description = 'Show basic analytics report';

    public function handle()
    {
        $this->info('Top Pages:');

        $pages = app('analytics')->topPages();

        foreach ($pages as $page) {
            $this->line("{$page->name} ({$page->total})");
        }

        return 0;
    }
}