KSM
===

A webbased software for selling, buying, lending and maintaining forklifts build on [Bienlein](https://github.com/sah-comp/bienlein).

Installation
============

Create the database.

Copy the _config.examle.php_ in app/config and name it *config.php*.

Open it with a text editor and make changes as you fancy, e.g. enter the login information for the database(s) used. Do not forget to choose a install passcode that is not the default one.

Create these folders and make them writeable to PHP:

* public/upload
* app/res/tpl/cache

In the app directory do:

[composer](http://getcomposer.org/) install

This will create the _vendor_ folder and install all dependencies needed.

You are good to go. Point your browser to your applications URL and start working.

Enjoy.
