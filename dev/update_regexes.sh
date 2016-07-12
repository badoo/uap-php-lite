#!/bin/sh
set -ex

cd "$(git rev-parse --show-toplevel)"
git pull
git submodule foreach git pull
cd dev
php generate_ua.php ../prod/ua_regexes.php
cd ..
git commit -m "Update regexes" dev/spyc dev/uap-core prod/ua_regexes.php
#git push origin master

