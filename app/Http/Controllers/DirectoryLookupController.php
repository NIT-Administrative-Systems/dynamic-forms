<?php

namespace App\Http\Controllers;

use Northwestern\SysDev\SOA\DirectorySearch;

class DirectoryLookupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(DirectorySearch $directoryApi, string $search)
    {
        $person = null;
        $searchType = $this->guessType($search);
        $data = $directoryApi->lookup($search, $searchType, 'basic');

        if ($data) {
            $person = [
                'netid' => $data['uid'],
                'email' => $data['mail'],
                'name' => $data['displayName'][0],
                'title' => $data['nuAllTitle'][0],
            ];
        }

        return response()->json([
            'display' => $search,
            'searchType' => $searchType,
            'person' => $person,
        ])->setStatusCode($data ? 200 : 404);
    }

    /**
     * Guess the DirectorySearch lookup type for a given value.
     */
    protected function guessType(string $search): string
    {
        if (str_contains($search, '@')) {
            return 'mail';
        }

        if (preg_match('/^[0-9]{7}$/', $search)) {
            return 'emplid';
        }

        return 'netid';
    }
}
