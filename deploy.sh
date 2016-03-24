#!/bin/sh

## LOCAL OPERATIONS

deploycontent=$(readlink -f '../website_deploy')
projectdir=$(pwd)

# Copy everything to parent directory
rm -rf "$deploycontent"
cp -R . "$deploycontent"
cd "$deploycontent"

# Install dependencies
rm -rf vendor
rm -rf composer.lock
composer self-update
composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction

# Run Grunt tasks
rm -rf node_modules
npm install
grunt

# Remove everything that shouldn't be deployed
rm -rf .sass-cache
rm -rf node_modules
rm -rf .env*
rm -rf .git*
rm -rf .idea
rm -rf .travis.yml
rm -rf .scrutinizer.yml
rm -rf composer.*
rm -rf package.json
rm -rf README.md
rm -rf LICENSE
rm -rf Makefile
rm -rf Gruntfile.js
rm -rf config/autoload/*.local.php
rm -rf config/autoload/*.local.php.dist
rm -rf public/sass
rm -rf public/css/bootstrap.min.css
rm -rf public/js/bootstrap.min.js
rm -rf public/js/contact.js
rm -rf public/js/guiUtils.js
rm -rf public/js/jquery.knob.js
rm -rf public/js/jquery.min.js
rm -rf public/js/main.js
rm -rf public/js/skills.js
rm -rf tests
rm -rf build
rm -rf bin


## REMOTE OPERATIONS

# Deploy application
remotepath='/home/alejandro/apps/alejandrocelaya/website'
temp="$remotepath-temp"
now=`date +'%Y-%m-%d_%T'`
ssh root@alejandrocelaya.com "mkdir $temp"
rsync -avz --no-owner --no-group ./ root@alejandrocelaya.com:${temp}
ssh root@alejandrocelaya.com "mv $remotepath $remotepath-$now"
ssh root@alejandrocelaya.com "mv $temp $remotepath"

# Set write access
ssh root@alejandrocelaya.com "chown www-data:www-data $remotepath/data/cache"

# Delete deploy artifacts
ssh root@alejandrocelaya.com "rm $remotepath/data/cache/.gitignore"
ssh root@alejandrocelaya.com "rm $remotepath/deploy.sh"
