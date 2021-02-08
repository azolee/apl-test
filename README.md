## APL nextED aptitude test


### The Goal 

Create a small sample application using common APL tools & languages. The application will need to import a large CSV file (100k records) into a queue, where a background worker will process each individual record.

### Requirements

- Use a public Git repository
- Set up a Homestead & VirtualBox VM environment for the application
- Use the native Laravel framework as much as possible (Models, Routes, Config, Storage, DB)
- Use Laravel Queue (your choice of queue)
- Use MySQL as the destination database
    - Create a DB migration to setup a Users table
    - Use the native Laravel timestamps
    - Use the SoftDelete feature
- Write a script to create a CSV file with 100k records containing a list of users
    - Names, emails, passwords and phone numbers (can be fully randomized)
    - Include a “deleted” column, with a few records having a value set
- Create a CSV file importer, that will accept the generated CSV file and insert the records into the Queue
- Create a Console Command that processes the records in the queue, and update the  database with the data.
- Setup PHP Unit / Functional Tests on the importer and queue worker.

### Requirements
- _Homestead_, _Valet_ or any other local setup.
- Laravel 8.x

### Local setup 
- Clone this repository
- Download and install `Homestead` and `VirtualBox`
- Install the dependencies from the project folder 

```
composer install
```

- Generate the Homestead configuration file by running the 

```
php vendor/bin/homestead make
```

or by copying the attached _Homestead.yaml_ file to your project directory and run 

```
vagrant up
```

- In order to connect to your virtual machine, run:

```
vagrant ssh
```
- Go to your project folder listed in the Homestead.yaml file and create the `.env` file, than update the database connection data

```
copy .env.example .env
```

- Run the migration

```
php artisan migrate
```

- Add the Jobs table:

```
php artisan queue:table
```

### Application Usage

#### Generate users to file

This application can generate users in `csv`, `json` format.

To generate a list of users, use the following command:

```
php artisan generate:users {format} {count=10} {filename=users}
```

This command will generate a users.csv file containing 100000 users to the default storage directory.

Currently there are two formats supported: json and csv. For more info, see the `app/Extension/HandleItemsList` and `app/Extension/HandleItemsList` folders for more info about further format implementations. 

__Example:__

This will generate a users.csv file with 100000 user data
```
php artisan generate:users csv 100000

```
This command will generate a users.json file with 100000 users data.
```
php artisan generate:users json 100000

```


#### Load file into users table

Start the workers:

```
php artisan queue:work --queue=users
``` 

To load a file into the users table, run the below command

```
php artisan load:users {format=csv} {filename=users}
```
__Example:__

This will load the users.csv file into the users table
```
php artisan load:users csv users

```
This will load the users.json file into the users table
```
php artisan generate:users json 100000

```

### Testing the app

In order to properly run tests, the .env.testing file has to be completed.
After the above is completed, run:

```
php artisan test
```

### Author

[Andras Zoltan Gyarfas](https://github.com/azolee) - Zoli