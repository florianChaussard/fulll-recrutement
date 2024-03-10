<?php
/**
 * The following file is a command that answers the fizzbuzz.md demand
 *
 * It browses numbers from 1 to a given parameter $maxValue and
 * If the number is divisible by 3, displays "Fizz"
 * If the number is divisible by 5, displays "Buzz"
 * If both, displays "FizzBuzz"
 * Else, displays the number
 *
 * The var $argv contains the arguments given by the user when throwing the command
 * $argv[0] is the path to the file being executed
 * If any of the arguments given equals "help", the script will display the FizzBuzz rules before running it
 * Without this argument, the script will run without showing the rules
 *
 * If any of the arguments given equals to a >=1 number, the script will consider this is the maxValue wanted by the user.
 * If no such thing is found, the script will prompt the user to give a max value
 */

runCommand($argv);

/**
 * The command core
 * @param array $argv the command's arguments list
 * @return void
 */
function runCommand(array $argv):void
{
    if(in_array('help', $argv)){
        showFizzBuzzRules();
    }
    //look for a >=1 argument in the list
    foreach($argv as $argument){
        //if such a thing is found, run fizzBuzz without prompting the user
        if(is_numeric($argument) && $argument >= 1){
            runFizzBuzz(intval($argument));
            //end the command here
            exit();
        }
    }

    //no maxValue has been provided by the user, we prompt the user
    $maxValue = promptUserMaxValue();
    runFizzBuzz($maxValue);
}

/**
 * Displays the FizzBuzz rules in the command line
 * @return void
 */
function showFizzBuzzRules():void
{
    fwrite(STDOUT, "This command is meant to answer the FizzBuzz algorithm\n");
    fwrite(STDOUT, "It browses numbers from 1 to a given parameter and\n");
    fwrite(STDOUT, "If the number is divisible by 3, displays \"Fizz\"\n");
    fwrite(STDOUT, "If the number is divisible by 5, displays \"Buzz\"\n");
    fwrite(STDOUT, "If both, displays \"FizzBuzz\"\n");
    fwrite(STDOUT, "Else, displays the number\n\n");
    fwrite(STDOUT, "Using the argument \"help\" shows the current explanations.\n\n");
    fwrite(STDOUT, "If any of the arguments given equals to a >=1 number, the script will consider this is the maxValue wanted by the user.\n");
    fwrite(STDOUT, "If no such thing is found, the script will prompt the user to give a max value.\n");
}

/**
 * Browses numbers from 1 to $maxValue and displays text following the FizzBuzz rules
 * @param int $maxValue the max number we want to browse to
 * @return void
 */
function runFizzBuzz(int $maxValue){
    fwrite(STDOUT, "Running FizzBuzz algorithm from 1 to ".strval($maxValue)."\n");
    fwrite(STDOUT, "======================================================\n");
    for($i = 1; $i <= $maxValue; $i++) {
        //the value to display
        $displayValue = '';
        if($i % 3 === 0){
            $displayValue = 'Fizz';
        }
        if($i % 5 === 0){
            $displayValue .= 'Buzz';
        }
        if(empty($displayValue)) {
            $displayValue = strval($i);
        }
        fwrite(STDOUT, $displayValue."\n");
    }
}

/**
 * Asks the user for the max value to browse to
 * If the provided value is incorrect, it asks for it again
 * @return int
 */
function promptUserMaxValue():int{
    fwrite(STDOUT, "Please enter the max value to browse to (a >=1 number)\n");
    $providedValue = fgets(STDIN);
    //if the provided value is not a >=1 number
    if(!is_numeric($providedValue) || $providedValue < 1){
        fwrite(STDOUT, "This value is incorrect.\n");
        //prompt again
        return promptUserMaxValue();
    }
    else{ //the provided value is ok, we return it's int val
        return intval($providedValue);
    }
}



