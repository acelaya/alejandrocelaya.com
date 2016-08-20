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
rm -rf .*
rm -rf composer.*
rm -rf package.json
rm -rf README.md
rm -rf LICENSE
rm -rf Makefile
rm -rf Gruntfile.js
rm -rf config/autoload/*.local.php{,.dist}
rm -rf public/css/{animate,bootstrap,icomoon,style}.css
rm -rf public/js/{bootstrap.min,jquery.min,jquery.easing.1.3,jquery.waypoints.min,main}.js
rm -rf tests
rm -rf build
rm -rf bin

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

# Restart nginx and php-fpm
ssh root@alejandrocelaya.com "service php7.0-fpm restart"
ssh root@alejandrocelaya.com "service nginx restart"

# Delete deploy artifacts
ssh root@alejandrocelaya.com "rm $remotepath/data/cache/.gitignore"
ssh root@alejandrocelaya.com "rm $remotepath/deploy.sh"

# Finally delete deployed content
rm -rf "$deploycontent"
