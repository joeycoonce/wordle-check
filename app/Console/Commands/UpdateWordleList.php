<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Console\Command;

class UpdateWordleList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:wordle-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates wordle list from NYT';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Updating Wordle Lists...');

        $fileContents = file_get_contents('https://www.nytimes.com/games/wordle/main.bd4cb59c.js');
        $maPos = strpos(
            haystack: $fileContents, 
            needle: 'Ma='
        ) + 4;

        $Ma = substr($fileContents, $maPos, strpos(
            haystack: $fileContents, 
            needle: '],',
            offset: $maPos
        ) - $maPos);

        $oaPos = strpos(
            haystack: $fileContents, 
            needle: 'Oa='
        ) + 4;

        $Oa = substr($fileContents, $oaPos, strpos(
            haystack: $fileContents, 
            needle: '],',
            offset: $oaPos
        ) - $oaPos);  

        Cache::put('wordList', explode( ',', str_replace('"', '', $Ma)));
        Cache::put('badGuessList', explode( ',', str_replace('"', '', $Oa)));
        Log::info('Success');

        return 0;
    }
}
