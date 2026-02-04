@ECHO OFF

CD %~dp0
CD ..

ECHO Checking composer...
CALL composer install --prefer-dist
CALL composer validate --strict
ECHO outdated:
CALL composer outdated --direct

ECHO .
ECHO Checking syntax...
CALL vendor\bin\parallel-lint --exclude .git --exclude vendor .

ECHO .
ECHO Code Analysis...
CALL vendor\bin\phpstan.phar.bat analyse

ECHO Checking code styles...
CALL vendor\bin\phpcs.bat -sp --standard=ruleset.xml SourceCode
CALL vendor\bin\phpcs.bat -sp --standard=ruleset.tests.xml Tests


ECHO Running Automated Tests
CALL vendor\bin\phpunit --configuration Tests\phpunit.xml

IF "%1"=="deploy" GOTO deploy
GOTO finish

:deploy

:finish
