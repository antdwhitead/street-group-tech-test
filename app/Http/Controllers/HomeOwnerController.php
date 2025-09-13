<?php

namespace App\Http\Controllers;

use App\Http\Requests\CsvUploadRequest;
use App\Services\HomeOwnerDataService;
use Inertia\Inertia;
use Inertia\Response;

class HomeOwnerController extends Controller
{
    public function __construct(
        private readonly HomeOwnerDataService $homeOwnerDataService
    ) {}

    public function upload(CsvUploadRequest $request): Response
    {
        $file = $request->file('csv');
        $tempPath = $file->getRealPath();

        $people = $this->homeOwnerDataService->parseCsv($tempPath);
        $peopleArray = array_map(fn ($person) => $person->toArray(), $people);

        return Inertia::render('HomeOwners/Results', [
            'people' => $peopleArray,
            'totalCount' => count($peopleArray),
        ]);
    }
}
