<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class CountController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'pattern' => ['nullable', 'string', 'regex:/(^([a-zA-Z\*]*)$)/u', 'min:5', 'max:5']
        ]);

        $input = $request->only('pattern', 'included', 'excluded', 'wordle', 'list');

        // dd($input);
        $regex = $input['pattern'] ? strtolower($input['pattern']) : '';

        $includedStr = $input['included'] ? preg_replace("/[^a-z]+/", "", strtolower($input['included'])) : "";
        $excludedStr = $input['excluded'] ? preg_replace("/[^a-z]+/", "", strtolower($input['excluded'])) : "";
        $includedLetters = strlen($includedStr) ? array_unique(str_split($includedStr)) : [];
        $excludedLetters = strlen($excludedStr) ? array_unique(str_split($excludedStr)) : [];
        // dd(explode(',', $request->exclude));

        $todaysWordleNumber = Carbon::now()
                                    ->diff(Carbon::parse("2021-06-19"))
                                    ->days;


        if (array_key_exists('wordle', $input))
        {
            $guesses = array_slice(Cache::get('wordList'), $todaysWordleNumber);
        }
        else
        {
            $guesses = array_merge(array_slice(Cache::get('wordList'), $todaysWordleNumber), Cache::get('badGuessList'));
        }
        // $guesses = Cache::get('badGuessList');

        $guesses = array_filter($guesses, function($word) use ($excludedLetters, $includedLetters, $regex) {
            if (count($excludedLetters))
            {
                if(count(array_intersect(str_split($word), $excludedLetters)) > 0)
                {
                    return false;
                }
            }

            if (count($includedLetters))
            {
                foreach ($includedLetters as $letter) {
                    if (strpos($word, $letter) === false)
                    {
                        return false;
                    }
                }
                // if(count(array_intersect(str_split($word), $includedLetters)) == 0)
                // {
                //     return false;
                // }
            }

            if(strlen($regex))
            {
                for ($i = 0; $i < 5; $i++) {
                    if (ctype_alpha($regex[$i]) && $regex[$i] !== $word[$i])
                    {
                        return false;
                    }
                }
            }
            // foreach ($excludedLetters as $letter)
            // {
            //     if (str_contains(haystack: $word, needle: $letter)) { return false;}
            // }
            // return 0 == count(array_intersect(str_split($word), $excludedLetters));
            // return !str_contains($word, 'b')
            //     && !str_contains($word, 'r')
            //     && !str_contains($word, 'a')
            //     && !str_contains($word, 'v');
            // return $word[1] === 'o';
            //     // && $word[4] !== 'd'
            //     // && !str_contains($word, 'h')
            //     // && !str_contains($word, 'u')
            //     // && !str_contains($word, 'n')
            //     // && !str_contains($word, 'i')
            //     // && !str_contains($word, 'c')
            //     // && !str_contains($word, 'h')
            //     && str_contains($word, 'd');
            return true;
        });

        if (count($guesses) == 0)
        {
            return back()->with('danger', 'Number of guesses: '.count($guesses))->withInput();
        }
        // dd($request->list);
        if (array_key_exists('list', $input)) {
            sort($guesses);
            return back()->with('success', 'Guesses: '.implode(', ', $guesses))->withInput();
        }


        return back()->with('success', 'Number of guesses: '.count($guesses))->withInput();
    }
}
