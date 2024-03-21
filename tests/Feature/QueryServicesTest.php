<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class QueryServicesTest extends TestCase
{
    //Test querying services by country code
    public function testQueryServicesByCountryCode()
    {
        $this->artisan('query:services FR')
             ->expectsOutput('Services provided in the specified country:')
             ->assertExitCode(0);
    }

    //Test summary of services by country
    public function testSummaryOfServicesByCountry()
    {
        $this->artisan('query:services GB')
             ->expectsOutput('Summary of services by country:')
             ->assertExitCode(0);
    }

    //Test querying services with an invalid country code
    public function testQueryServicesWithInvalidCountryCode()
    {
        $this->artisan('query:services INVALID')
             ->expectsOutput('No services found for the specified country code.')
             ->assertExitCode(0);
    }

    //Test querying services with lower case country code
    public function testQueryServicesWithLowerCaseCountryCode()
    {
        $this->artisan('query:services it')
             ->expectsOutput('Services provided in the specified country:')
             ->assertExitCode(0);
    }

    //Test querying services with mixed case country code
    public function testQueryServicesWithMixedCaseCountryCode()
    {
        $this->artisan('query:services De')
             ->expectsOutput('Services provided in the specified country:')
             ->assertExitCode(0);
    }

    //Test querying services when 'services.csv' file is missing
    public function testQueryServicesWithMissingCsvFile()
    {
        // Renaming 'services.csv' to 'temp.csv'
        Storage::disk('local')->move('services.csv', 'temp.csv');

        $this->artisan('query:services PT')
            ->expectsOutput('Error: csv file not found.')
            ->assertExitCode(0);

        // Renaming 'temp.csv' back to 'services.csv'
        Storage::disk('local')->move('temp.csv', 'services.csv');
    }
}
