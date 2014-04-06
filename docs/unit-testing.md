The unit testing suite can be run by executing ```run_unit_test.sh``` (OS X and
Linux) or ```run_unit_test.bat``` (Windows).

The test suite displays its results on the console, as well as generates a test
report at ```tests/report.html```. If the PHP XDebug extension is available, a
coverage report is also generated at ```tests/report/index.html```.

Note that the Windows batch file has been customized to run using the WAMP
environment; should you not be using WAMP, you will have to execute the commands
in the batch file manually and adjusted for your PHP environment.
