<?php

namespace App\Http\Controllers;

use App\RecentSearch;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;

class HomeController extends Controller
{
    private $lastSearchs;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->updateRecentSearch();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home')->with(['lastSearchs' => $this->lastSearchs]);
    }

    public function search(Request $request)
    {
        if (isset($request->first))
        {
            $wasSearched = false;
            
            foreach ($this->lastSearchs as $search)
            {
                if ($search->search == $request->search) {
                    $wasSearched = true;
                    break;
                }
            }

            if (!$wasSearched) {
                $recentSearch = new RecentSearch();
                $recentSearch->search = $request->search;
                $recentSearch->save();
                $this->updateRecentSearch();
            }
        }

        $offset = (isset($request->offset))
                            ? $request->offset
                            : 0;

        $books = $this->getBooks($request->search, $offset);

        return view('home')->with([
            'books' => $books,
            'search' => $request->search,
            'offset' => $offset,
            'lastSearchs' => $this->lastSearchs
        ]);
    }

    private function getBooks($searchText, $offset)
    {

        $cacheKey = $searchText.'-'.$offset;

        return \Cache::remember($cacheKey, 1440, function () use ($searchText, $offset) {
            $client = new Client(config('services.google-api.books.options'));
            
            $options = ['query' => [
                            'q' => $searchText,
                            'maxResults' => env('GOOGLE_PAGINATION', 20),
                            'startIndex' => $offset
                            ]
                        ];
    
            $result = $client->get('volumes', $options);
    
            return json_decode($result->getBody()->getContents());
        });
    }

    private function updateRecentSearch()
    {
        $this->lastSearchs = RecentSearch::orderBy('created_at', 'DESC')
            ->limit(env('SEARCH_QUANTITY', 10))
            ->get();
    }
}
