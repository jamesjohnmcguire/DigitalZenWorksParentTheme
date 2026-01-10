CD %~dp0
CD ..
CD SourceCode

CALL composer validate --strict
CALL composer install --prefer-dist
ECHO outdated:
CALL composer outdated

ECHO Checking code styles...
php vendor\bin\phpcs -sp --standard=ruleset.xml .

@ECHO ON
CD SourceCode\themes\digitalzenworks-irdi
IF EXIST vendor.bak\NUL RD /S /Q vendor.bak
IF EXIST vendor.production\NUL RD /S /Q vendor.production

ECHO Backing up vendor
XCOPY /C /D /E /H /I /R /S /Y vendor vendor.bak >NUL

ECHO Installing vendor no dev
CALL composer install --no-dev --prefer-dist
@ECHO ON

CD vendor

DEL /S /Q *.md >NUL
CD ..

REN vendor vendor.production
REN vendor.bak vendor

:finish
