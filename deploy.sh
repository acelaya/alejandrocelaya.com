#!/bin/sh
set -e

######################
## LOCAL OPERATIONS ##
######################

deploycontent=$(readlink -f '../alejandrocelaya.com_deploy')
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
rm -rf public/css/animate.css
rm -rf public/css/bootstrap.css
rm -rf public/css/icomoon.css
rm -rf public/css/style.css
rm -rf public/js/bootstrap.min.js
rm -rf public/js/jquery.min.js
rm -rf public/js/jquery.easing.1.3.js
rm -rf public/js/jquery.waypoints.min.js
rm -rf public/js/main.js
rm -rf tests
rm -rf build
rm -rf bin/twig-gettext-extractor

#######################
## REMOTE OPERATIONS ##
#######################

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

# Restart remote services
ssh root@alejandrocelaya.com "service php7.0-fpm restart"
ssh root@alejandrocelaya.com "service nginx restart"
ssh root@alejandrocelaya.com "service redis restart"

# Delete deploy artifacts
ssh root@alejandrocelaya.com "rm $remotepath/data/cache/.gitignore"
ssh root@alejandrocelaya.com "rm -rf $remotepath/data/cache/*"
ssh root@alejandrocelaya.com "rm $remotepath/deploy.sh"

# Run long tasks
ssh root@alejandrocelaya.com "$remotepath/bin/cli website:long-tasks"

# Finally delete deployed content
rm -rf "$deploycontent"
