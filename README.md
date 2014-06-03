TweetsLinker
============
Overview
--------
This website is designed to provide a crowdsourcing platform which aims at building a data set of tweets metadata for research purposes.
The identified metadata (geographic location, keywords highlighted, event type...) are stored in a PostgreSQL database.

Technologies
------------
- Backend
	- NGinX server with PHP
	- Symfony2: http://tutorial.symblog.co.uk
	- PostgreSQL
		Login: Symfony
		Password: uziac5Oh
- Frontend
	- Boostrap
	- JQuery

Installation scripts
--------------------
- Execute `./installSoft` to get all the necessary software
- Execute `./restoreDatabase` to update the database schema
- Execute `python TwitterDatabaseImporter/Main.py` to fill the database with the trafficgis scripts.

Then restart the symfony server with the restart scripts:

- For debug purposes: `./restart dev`
- For production: `./restart prod nodebug`

Important files
---------------
- NGinX configuration files
	- Virtual servers
		- /etc/nginx/sitesavailable/default
	- NGinX config
		- /etc/nginx/nginx.conf
	- PHP config
		- /etc/php5/fpm/php.ini
		- /etc/php5/fpm/phpfpm.conf
- Symfony
	- Root directory
		- /usr/share/nginx/www
	- Assetic files (dependency management)
		- /usr/share/nginx/www/app/config/config*.yml
	- Composer files (software installation)
		- /usr/share/nginx/www/app/composer.json
		- /usr/share/nginx/www/app/composer.phar
	- PHP
		- Entities
			- /usr/share/nginx/www/src/UCDTweet/TweetBundle/Entity
		- Controller
			- /usr/share/nginx/www/src/UCDTweet/TweetBundle/Controller
	- Resources
		- Javascript
			- /usr/share/nginx/www/src/UCDTweet/TweetBundle/Resources/public/js
		- Views (Twig)
			- /usr/share/nginx/www/src/UCDTweet/TweetBundle/Resources/public/
		- Routing
			- /usr/share/nginx/www/src/UCDTweet/TweetBundle/Resourses/config/routing.yml
		- CSS
			- /usr/share/nginx/www/src/UCDTweet/TweetBundle/Resources/public/css
		- Images
			- /usr/share/nginx/www/src/UCDTweet/TweetBundle/Resources/public/css
