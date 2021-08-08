# Howdy
This is a lazy one to one implementation of the dotnet solution but in PHP.  
It seems to work just fine, but I'm sure some PHP specific bugs might have 
made their way in.
# Tests
Since my main focus was on the dotnet solution, there is only one test in this project (sorry!).  
The test verifies that the total calculated price is correct based on the example in the exercise.
# How to
Use the below command to install composer dependencies and run the tests:
```bash
docker-compose run composer install && docker-compose run phpunit --testdox
```
