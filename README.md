# Query Services CLI Application

This Laravel CLI application allows users to query service data based on country code and display a summary of services provided by each country.

## Installation

1. Clone this repository to your local machine:
git clone https://github.com/flamur002/

2. Navigate to the project directory:
cd query-services

## Running the Application

To query services based on a country code, run the following artisan command:
php artisan query:services {countryCode}

Replace `{countryCode}` with the desired country code (e.g., `GB`, `FR`).
This will also display a summary of services provided by each country.


## Testing

To run PHPUnit tests for this application, execute the following command:
php artisan test


## Additional Information

- The `services.csv` is located inside the "storage\app" directory.
- The '**QueryServices.php**' file that handles the custom artisan commands is located inside the "app\Console\Commands" directory.
- The '**QueryServicesTest.php**' file responsible for the tests is located inside the "tests\Feature" directory.



