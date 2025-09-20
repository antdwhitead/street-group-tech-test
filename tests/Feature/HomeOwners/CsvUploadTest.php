<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

describe('CSV Upload', function () {
    it('validates required csv file', function () {
        $response = $this->post(route('homeowners.upload'), []);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['csv' => 'Please select a CSV file to upload.']);
    });

    it('validates file type', function () {
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->post(route('homeowners.upload'), [
            'csv' => $file,
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors('csv');
    });

    it('validates file size', function () {
        $file = UploadedFile::fake()->create('large.csv', 3000, 'text/csv');

        $response = $this->post(route('homeowners.upload'), [
            'csv' => $file,
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors('csv');
    });

    it('uploads and processes real CSV file successfully', function () {
        $csvPath = __DIR__.'/../../Data/example.csv';
        $file = new UploadedFile($csvPath, 'test.csv', 'text/csv', null, true);

        $response = $this->post(route('homeowners.upload'), [
            'csv' => $file,
        ]);

        $response->assertStatus(200)
            ->assertInertia(fn ($page) => $page->component('HomeOwners/Results')
                ->has('homeOwners', 18)
                ->where('totalCount', 18)
                ->where('homeOwners.0.title', 'Mr')
                ->where('homeOwners.0.first_name', 'John')
                ->where('homeOwners.0.last_name', 'Smith')
                ->where('homeOwners.1.title', 'Mrs')
                ->where('homeOwners.1.first_name', 'Jane')
                ->where('homeOwners.1.last_name', 'Smith')
            );
    });
});
