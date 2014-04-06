CIUnit (part of foostack) was originally written for CodeIgniter 1.7.x. Its
homepage is http://www.knollet.com/foostack/.

This project uses a slightly modified version of CIUnit called My CIUnit,
available from https://bitbucket.org/kenjis/my-ciunit/src.

It also includes the following custom modifications:

* Edits to use DELETE instead of TRUNCATE, to fix foreign key checks: https://bitbucket.org/kenjis/my-ciunit/pull-request/2/replace-truncate-with-delete-to-allow/diff
* Edits to prevent IP address warnings: http://stackoverflow.com/questions/18082618/php-error-encountered-when-running-command-line-using-codeigniter-2-xx
* Removes include reference to PHPUnit/Autoload.php in the bootstrap loader
* Adds manual inclusions of PHPUnit classes to generate.php for generating fixtures
* Add cleanup_uploads() function to cleanup the mock test folder
* Add 'site_data_dir' config variables to the CIUnit config.php pointing to uploads/tests
* Removed overridden functions in MY_url_helper.php from CIU_url_helper.php
