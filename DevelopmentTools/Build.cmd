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
CALL vendor\bin\phpunit --config Tests\phpunit.xml

IF "%1"=="deploy" GOTO deploy
GOTO finish

:deploy
CD SourceCode
IF EXIST vendor.bak\NUL RD /S /Q vendor.bak
IF EXIST vendor.production\NUL RD /S /Q vendor.production

ECHO Backing up vendor
XCOPY /C /D /E /H /I /R /S /Y vendor vendor.bak >NUL

ECHO Installing vendor no dev
CALL composer install --no-dev --prefer-dist
@ECHO ON

CD vendor

DEL /S /Q *.md >NUL 2>NUL
CD ..

REN vendor vendor.production
REN vendor.bak vendor

:finish
