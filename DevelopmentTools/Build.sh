#!/bin/bash

cd "$(dirname "${BASH_SOURCE[0]}")"
cd ..

echo Checking composer...
composer install --prefer-dist
composer validate --strict
echo outdated:
composer outdated --direct

echo
echo Checking code syntax...
vendor/bin/parallel-lint --exclude .git --exclude vendor .

echo
echo Code Analysis...
vendor/bin/phpstan.phar analyse

echo
echo Checking code styles...
vendor/bin/phpcs -sp --standard=ruleset.xml --ignore=SourceCode/themes/digitalzenworks/Tests SourceCode SourceCode
vendor/bin/phpcs -sp --standard=ruleset.tests.xml Tests

vendor/bin/phpunit --configuration Tests/phpunit.xml

if [[ $1 == "release" ]] ; then
	echo "Release Is Set!"

	# rm -rf Documentation
	# phpDocumentor.phar --setting="graphs.enabled=true" -d SourceCode -t Documentation

	file="digitalzenworks.zip"

	if [ -f "$file" ] ; then
		echo "Removing existing zip file..."
		rm "$file"
	fi

	zip -r "$file" SourceCode/themes/digitalzenworks
	gh release create v$2 --notes $2 "$file"
	rm "$file"
fi
