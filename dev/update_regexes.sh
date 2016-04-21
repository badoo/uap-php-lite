#!/bin/sh
if [ "`dirname $0`" = "`pwd`" ]
then
	cd ..
fi

git pull --quiet
git submodule --quiet foreach git pull --quiet
php ./dev/generate_ua.php ./prod/ua_regexes.php
git commit --quiet -a -m "Update regexes" && git push --quiet origin master

