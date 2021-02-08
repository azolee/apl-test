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


### License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
