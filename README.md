## How To run this project

    1- Git clone https://github.com/ibrahemhamdy1/RobustaBusApp.git
    2- Make .env file 'cp .env.example .env'
    3- You need to create a database for this project and config the .env file to add the database
    4- Run  'npm install'
    5- run 'composer install'
    5- Setup the database and the test data run 'php artisan migrate --seed'

## App API
- # Look for a trip from a specific form time and from station to station
    - GET|HEAD | api/trips
        - `http://robustabusapp.test/api/trips?from_date=2021-03-14 09:20:20&from_station=Cairo&to_station=AlFayyum`
- # Reserve for a trip from a specific form time and from station to station
    - POST| api/trips
        -  `http://robustabusapp.test/api/trips?from_date=2021-03-14 09:20:20&from_station=Cairo&to_station=AlFayyum`

- # You could add this data to your postman to test
        - "from_date" : "2021-03-14 09:20:20"
        - "from_station" : "Cairo",
        - "to_station" : "AlFayyum"

