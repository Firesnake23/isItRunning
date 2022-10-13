# Is It Running

## Table of Contents
- [Table of contents](#table-of-contents)
- [About](#about)
- [Installation](#installation)
- [Write your own CheckRunner](#write-you-own-checkrunner)

## About
Is it running is a monitoring System for websites. It allows you to monitor
several environments with predefined checks. How these environments are addressed, is completely up to you.

In every Environment you can define variables, which then in turn can be used by the individual checks.

## Installation

The Software uses doctrine. To be able to use doctrine you have to set it up.
Here is a link to their [documentation](https://www.doctrine-project.org/projects/doctrine-orm/en/2.13/tutorials/getting-started.html#getting-started-with-doctrine).

The Software also provides a few cli commands. In order for them to work, you must create
a `bootstrap.php` file. It must be located in `workdir/bootstrap.php`.  
This file must include the Autoloader of composer and, provide the method
```
    public function getEntityManager(): EntityManager; 
```

Once that is done, all you have to do is set up the database and create the tables. You will
find how that is done in the documentation of Doctrine.

## Write you own CheckRunner

The CheckRunner is the main performer of this software.
In the CheckRunner you can examine the response of the curlResponse
and determine if the check is successful or failed. This class is an example of a check runner.<br>
`firesnake\isItRunning\entities\CheckableEnvironment\HttpStatusTest`

Each CheckRunner must implement the interface `firesnake\isItRunning\entities\CheckableEnvironment\CheckRunner`

You must however add your own CheckRunner in the PerformerRegistry. You can access
the registry via an instance of the `firesnake\isItRunning\IsItRunning` class.

The Comment of the checkRunner is only used if the test fails.

## How to use

 1. Create an Environment
 2. Create a Check and configure it how you need it
 3. Wait on the dashboard (It does refresh itself every minute)

You can use the EnvironmentVariables for the url of a Check. This syntax must be fulfilled:
`{{variableName}}rest-of-url`<br>
Where you place the variable is up to you. If needed, multiple variables can be used.