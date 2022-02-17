<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class WordleController extends Controller
{

    public function __invoke(Request $request)
    {
        $request->validate([
            'guess' => ['required', 'string', 'alpha', 'min:5', 'max:5']
        ]);

        $input = $request->only('guess', 'details', 'timezone');

        $todaysWordleNumber = Carbon::now($input['timezone'])
                                    ->diff(Carbon::parse("2021-06-19", $input['timezone']))
                                    ->days;


        $guess = strtolower($input['guess']);

        $wordListIndex = array_search(
            needle: $guess,
            haystack: Cache::get('wordList')
        );

        $badGuessIndex = array_search(
            needle: $guess,
            haystack: Cache::get('badGuessList')
        );

        $status = 'success';
        $statusMsg = strtoupper($guess)." is a valid guess!";

        if ($wordListIndex === false && $badGuessIndex === false)
        {
            $status = 'danger';
            $statusMsg = strtoupper($guess)." is not a word";
        }
        else if ($wordListIndex !== false && $wordListIndex < $todaysWordleNumber) {
                
            $status = 'warning';
            $statusMsg = strtoupper($guess)." was Wordle #".$wordListIndex;
        }
        else if (array_key_exists('details', $input))
        {
            if ($badGuessIndex !== false)
            {
                $status = 'warning';
                $statusMsg = strtoupper($guess)." is a valid guess, but not a good one";
            }
            else
            {
                $status = 'success';
                $statusMsg = strtoupper($guess)." is a good guess!";
            }
        }

        // $this->data['remember'] = array_key_exists('details', $input);

        return back()->with($status, $statusMsg)->withInput();
    }
}
