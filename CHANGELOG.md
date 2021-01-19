Nagixx
======

Toolset for developing Nagios-Plugins in an OO-Way with PHP.


Release v1.3.0 :: 2021-01-19
----------------------------

- Updated sources and PHPUnit to run with PHP >= 7.3 (PHP <= 7.2 not supported any longer)
- Updated sources and PHPUnit to run with PHP = 8.0.1



Release v1.1.5 :: 2017-01-14
----------------------------

- Much more enhanced documentation
- Updated composer.json with newest informations
- Added nagios_service_nagixx.txt as example for service definitions
- Renamed nagios_check_nagixx.txt to nagios_command_nagixx.txt as example for command definitions
- Added Console_CommandLine to lib-folder. So the correct version is shipped and used.
    * Current: v1.2.2
- Added phpunit.phar to tests-folder. So the correct version is shipped and used.
    * Current: v5.7.5
- Added vfsStream to tests-folder. So the correct version is shipped and used.
    * Current: v1.6.4
- Corrected class-names to be used in examples



Release v1.1.4 :: 2013-01-14
----------------------------

- Package now works with packagist



Release v1.1.3 :: 2012-08-12
----------------------------

- Added Nagios-Command definitions
- Added logging capabilities for the plugin



Release v1.1.2 :: 2012-08-10
----------------------------

- Refactored Plugin / StatusCalculator
- Added Exception-Handling for wrong CMD-Options/-Arguments
- Added VERSION-property
- Made some statitc- to instance-members for thread safety



Release v1.1.1 :: 2012-08-07
----------------------------

- Internal changes



Release v1.1.0 :: 2012-08-06
----------------------------

- Added handling of performance data
- Added an extended example, for outputting some performance data



Release v1.0.0 :: 2012-07-16
----------------------------

- First release
