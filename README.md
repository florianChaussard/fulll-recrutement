Fulll - Hiring process answers
======

This repository is meant to be Florian Chaussard's answers to [Fulll](https://fulll.fr) hiring process.
I developped my answers to folders Algo and Backend using PHP 8.3. I wish i could have used Symfony 6.4 for it would have made things way faster and easier but the rules were clear : no framework

PHP libraries manager : Composer 

JS libraries manager : Yarn

# Installation
* .env.local

The very first thing you need to do is to create a .env.local file at the root of the project. This file is not versionned and is meant to contain the real values of environnement dependant variables. The sole variable used here is the database_url, which contains the way to connect to your db. Of course, no such thing as db username or db password should appear in git repository, thus the use of .env.local.
 
* vendors

For the project to run correctly after the first download, you need to run commands ``composer install`` in /Backend/PHP/Boilerplate dir and ``yarn install`` in Backend/Node/Boilerplate dir

* database

The project uses a database to persist our fleets, vehicles and location created by command line (view Fleet management part of this document). Once you have set a correct database url in you .env.local file, locate yourself in /Backend/PHP/Boilerplate folder and run commands ``php bin/console doctrine:database:create`` and ``php bin/console doctrine:schema:update -f``

With that done, you will have a database with 3 empty tables : fleet, vehicle and location and you will be ready to run the Fleet Management part 


# FizzBuzz
My answer for the demand detailled in [/Algo/FizzBuzz.md](/Algo/fizzbuzz.md) is located in [/Algo/FizzBuzzCommand.php](/Algo/FizzBuzzCommand.php)

To play it, you can open a command line and run ``php ./Algo/FizzBuzzCommand.php`` if you're located in the project root dir.

I added 2 parameters options : 

* if you add "help" after the command ``php ./Algo/FizzBuzzCommand.php`` the command will display informations about what FizzBuzz is
* if you add a number >= 1 as in ``php ./Algo/FizzBuzzCommand.php 8.96`` the command will consider the number 8.96 as the max value the user wants to browse to. It will only consider the integer part (8.96 => 8) and thus run FizzBuzz from 1 to 8
* Of course you can add both arguments in the same time

You can chose not to add a max value directly when running the command, in that case the command will prompt the user for this information


# Fleet management
My answer for the demand detailled in [/Backend/PHP/ddd-and-cqrs-intermediare-senior.md](/Backend/PHP/ddd-and-cqrs-intermediare-senior.md) is located in the [/Backend/PHP/Boilerplate](/Backend/PHP/Boilerplate) folder.

* step 1

For the step 1, all you have to do is run command ``vendor/behat/behat/bin/behat`` if you're located in /Backend/PHP/Boilerplate folder. It will run all the tests located in /Backend/PHP/Boilerplate/features/*.feature files by using the code located in /Backend/PHP/Boilerplate/bootstrap/FeatureContext.php

I tried to create a context file per each feature file but it required a behat.yaml config file which would then be used explicitly in the command ``vendor/behat/behat/bin/behat``, and that was not the demand : i was meant to run the tests using solely this command without parameters

Among the 6 scenarios, you will see 4 running ok and 2 failing : it's on purpose. One of the rule is you're not allowed to register the same vehicle in the same fleet twice so, when the test tries to do so, it shows an error... which is what we expect !

Everything for this step has been hand-coded, i didn't use any framework or library for this step. I did install symfony 6.4 for step 2 though 

In the folder App, i put helpers that are meant to ease fleet and vehicle manipulation. I wanted them to contain frequently used code lines as functions. FleetHelper is relative to fleets, and vehicleHelper is relative to vehicles. If the frequently used function is 1 line long (like creating a new fleet), i let it in the context file

In the folder Domain, i put my objects declaration for fleet, vehicle and location. I did not use an object to represent users for the only thing i would use them for is their username, to know whose fleets belong to. If the need for user informations was bigger, i would have created an User object and used it within Fleet, instead of a string username field 

I did not use Infra folder for this step, for nothing is persisted

I chose to keep step 1 and step 2 alive at the same time so the recruiter at Fulll can see i did everything as they wanted. I did not want my implementation of step 2 to destroy what i did in step 1.

* step 2

For the step 2, you have several commands you may use in the order you want. Now, the fleet, vehicles and locations are persisted in the database we created in the Installation part of this document

You can run ``php bin/console fleet:create <username>`` to create a new fleet for the user <username>. Creates the fleet, saves it in the database and displays it's id

``php bin/console vehicle:create <plateNumber>`` to create a new vehicle with this plateNumber. Note : a plate number is not mandatory in the Vehicle object and is different from vehicle unique identifiant; it is possible to create a vehicle without a plate number. The command does not allow that though. It creates a vehicle, sets it's plate number, saves it in the database and displays it's id

``php bin/console location:create <longitude> <latitude> <name (optional)>`` to create a new location with those values as longitude and latitude. The name parameter is not mandatory, i just thought it would be more handy in a real application to display "your vehicle is parked at the wharehouse" than "your vehicle is parked and longitude 5 and latitude 43". The command creates the location, sets the longitude and latitude, saves it in database and displays it's id. Note : if the longitude and latitude given are not floats (integers ok), it will show an error message and won't create the location

``php bin/console vehicle:park <plateNumber> <longitude> <latitude>`` to park a vehicle at the given location. First we look for a vehicle with that plate number in the DB and a location with those longitude and latitude. If no vehicle or location is found, the command shows an error message. Else, the command sets the location as the current vehicle location, saves this information in the database and shows success message

``php bin/console fleet:register:vehicle <fleetId> <plateNumber>`` to register a vehicle in a fleet. If the fleeId param is not an integer, the commands shows an error. First we look for a fleet with that id in the database, and a vehicle with that plate number. If any of those are not found, the command shows an error message. Then, we try to add the vehicle to the fleet. If vehicle is already part of given fleet, the command shows an error. Else, the vehicle and fleet are persisted in the database, and success message is shown

``php bin/console fleet:localize:vehicle <fleetId> <plateNumber>`` to get given vehicle's location (longitude and latitude). If the fleetId param is not an integer, the commands shows an error. First we look for a fleet with that id in the database and a vehicle with that plate number. If either of those is not found, the command shows an error. Then we make sure the vehcle is part of the fleet. Then we make check if the vehicle has a known location, and if so, we display the longitude and latitude of said location. if any of the previous steps is not met, the command stops and shows an error.

I chose to use Symfony 6.4 for this step to use the doctrine ORM in order to manage the database and the bon/console CLI. I think a whole Symfony application is too much for what was asked but since i already work with Symfony daily, i thought it would be faster than learning to use a new CLI librairy. I tried to use Minicli but the tutorial i followed didn't use objects nor functions, i didn't want to code like that.

I used the command ``php bin/console make:entity`` to create my fleet, vehicle and location entities, they are located in the src/Entity folder. With each entity came a repository, located in the src/Repository folder

In the folder Manager, i created a manager for each entity. A manager is supposed to do the calculing before giving the result to the calling controller but in this application, it only is middleman for services

In the Folder Infra, i put services for each entity. A service is what interrogates the database using the EntityManager instance and repositories

The folder Controller is empty but is mandatory for a correct Symfony application



# Code quality tools

I developped all my answer using PHPStorm, which comes with built-in code sniffer. I configured it to follow PSR-2 rules and developped everyting following the notes the IDE generates

I work daily with SonarCube but i thought it would be overkill to install something that big for an application this small



# Continuous Delivery process

I ordered my git repository as follows : the master branch contains only the code that has been checked, the dev branch may be updated frequently. I put my development commits in the dev branch, read it again and again to make sure i didn't leave anything that shouldn't be in production server and when i was satisfied with my dev branch status, i made a merge request to put the code on the master branch.

In a daily basis, i would require my PR to be read by another developper and only merge it when he has approved it. Approval would be mandatory and i wouldn't be able to approve myself.

We can consider the merge in the master branch to be a production deployment. We can catch the fact that the master branch has been updated to automatically run ``git fetch`` command on the prod server. That way, the code is up to date. We can also add ``composer install`` command if necessary, then the only thing we would have to do manually is update .env.local file

















