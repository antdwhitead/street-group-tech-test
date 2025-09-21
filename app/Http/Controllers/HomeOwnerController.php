<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CsvUploadRequest;
use App\Models\HomeOwnerModel;
use App\Services\HomeOwnerDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;

class HomeOwnerController extends Controller
{
    public function __construct(
        private readonly HomeOwnerDataService $homeOwnerDataService
    ) {}

    public function index(Request $request): Response
    {
        $query = HomeOwnerModel::query();

        if ($request->filled('search')) {
            $query->search($request->string('search'));
        }

        if ($request->filled('title')) {
            $query->where('title', $request->string('title'));
        }

        $homeOwners = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('HomeOwners/Index', [
            'homeOwners' => $homeOwners,
            'filters' => [
                'search' => $request->string('search'),
                'title' => $request->string('title'),
            ],
        ]);
    }

    public function upload(CsvUploadRequest $request): Response
    {
        $file = $request->file('csv');
        $tempPath = $file->getRealPath();

        $result = $this->homeOwnerDataService->parseCsv($tempPath);
        $homeOwnersArray = array_map(fn ($homeOwner) => $homeOwner->toArray(), Arr::get($result, 'homeOwners', []));

        return Inertia::render('HomeOwners/Results', [
            'homeOwners' => $homeOwnersArray,
            'totalCount' => count($homeOwnersArray),
            'statistics' => Arr::get($result, 'statistics', []),
        ]);
    }
}
