<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class QueryServices extends Command
{
    protected $signature = 'query:services {countryCode}';
    protected $description = 'Query services based on country code and display summary';

    public function handle()
    {
        $fileName = "services.csv";

        if(Storage::exists($fileName)){
            $countryCode = strtoupper($this->argument('countryCode')); //Getting the "countryCode" argument from the command
            $services = $this->parseCsv($fileName);  // Retrieving the services and storing them into the $services variable

            //Terminating the execution if the csv file is empty
            if (empty($services)) {
                $this->info("Error: csv file is empty.");
                exit(1);
            }
            //Getting all the services whose country code is the same as the country code passed from the command (case insensitive)
            $countryServices = collect($services)->filter(function ($service) use ($countryCode) { 
                return strtoupper($service['Country']) === $countryCode;
            })->all();

            //Displaying all the services offered in the chosen country
            if(count($countryServices)>0){
                $this->displayServices($countryServices);
            }
            else{
                $this->info("No services found for the specified country code.\n");
            }
            //Displaying all the countries and the number of services that is offered in each of them
            $this->displaySummary($services);
        }else{
            $this->info("Error: csv file not found.");
        }
    }

    private function parseCsv($fileName){

        $csvData = Storage::get($fileName); //Getting all the contents of the csv file
        $lines = explode("\n", $csvData);
        $header = str_getcsv(array_shift($lines)); //Getting all the headers from the first line
        $services = [];

        //Looping through each the lines, and adding the data into the services array
        foreach ($lines as $line) {
            $fields = str_getcsv($line);
            $service = [];
            foreach ($fields as $key => $value) {
                $service[$header[$key]] = $value;
            }
            $services[] = $service;
        }
        return $services;
    }

    private function displayServices($services)
    {
        $this->info("Services provided in the specified country:");
        //Printing each service using a foreach loop
        foreach ($services as $service) {
            $this->line(" - {$service['Service']}, provided by {$service['Centre']}");
        }
        $this->line('');
    }

    private function displaySummary($services)
    {
        $summary = collect($services)->groupBy(function ($item) {
            return strtoupper($item['Country']);
        })->map(function ($group) {
            return count($group);
        });

        //Displaying all the country codes along with the number of services that they offer in that particular country
        $this->info("Summary of services by country:");
        foreach ($summary as $country => $count) {
            $this->line(" - $country: $count services");
        }
    }
}
