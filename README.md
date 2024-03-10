Fulll - Hiring process answers
======

This repository is meant to be Florian Chaussard's answers to [Fulll](https://fulll.fr) hiring process.
I developped my answers to folders Algo and Backend using PHP 8.3. I wish i could have used Symfony 6.4 for it would have made things way faster and easier but the rules were clear : no framework

PHP libraries manager : Composer 

JS libraries manager : Yarn

# Installation
* vendors

For the project to run correctly after the first download, you need to run commands ``composer install`` in /Backend/PHP/Boilerplate dir and ``yarn install`` in Backend/Node/Boilerplate dir

* database

# FizzBuzz
My answer for the demand detailled in [FizzBuzz.md](fizzbuzz.md) is located in [/Algo/FizzBuzzCommand.php](FizzBuzzCommand.php)

To play it, you can open a command line and run ``php ./Algo/FizzBuzzCommand.php`` if you're located in the project root dir.

I added 2 parameters options : 

* if you add "help" after the command ``php ./Algo/FizzBuzzCommand.php`` the command will display informations about what FizzBuzz is
* if you add a number >= 1 as in ``php ./Algo/FizzBuzzCommand.php 8.96`` the command will consider the number 8.96 as the max value the user wants to browse to. It will only consider the integer part (8.96 => 8) and thus run FizzBuzz from 1 to 8
* Of course you can add both arguments in the same time

You can chose not to add a max value directly when running the command, in that case the command will prompt the user for this information


# Fleet management
//todo

# Code quality tools
//todo
