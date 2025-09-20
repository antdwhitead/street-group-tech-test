<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CsvUploadRequest;
use App\Services\HomeOwnerDataService;
use Illuminate\Support\Arr;
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

        $result = $this->homeOwnerDataService->parseCsv($tempPath);
        $peopleArray = array_map(fn ($person) => $person->toArray(), Arr::get($result, 'people', []));

        return Inertia::render('HomeOwners/Results', [
            'people' => $peopleArray,
            'totalCount' => count($peopleArray),
            'statistics' => Arr::get($result, 'statistics', []),
        ]);
    }
}
