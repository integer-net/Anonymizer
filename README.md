IntegerNet_Anonymizer
=====================
This module allows anonymizing customer data in a sensible way. It uses dummy data provided by Faker and maintains associations like customer address <=> order address.

Facts
-----

| Branch | Build Status | Code Quality |
| ------ | ------------ | ------------ |
| master | [![Build Status (master)](https://travis-ci.org/integer-net/Anonymizer.svg?branch=master)](https://travis-ci.org/integer-net/Anonymizer) | [![Scrutinizer Code Quality (master](https://scrutinizer-ci.com/g/integer-net/Anonymizer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/integer-net/Anonymizer/?branch=master) |
| development | [![Build Status (development)](https://travis-ci.org/integer-net/Anonymizer.svg?branch=development)](https://travis-ci.org/integer-net/Anonymizer) | [![Scrutinizer Code Quality (development](https://scrutinizer-ci.com/g/integer-net/Anonymizer/badges/quality-score.png?b=development)](https://scrutinizer-ci.com/g/integer-net/Anonymizer/?branch=development) |

- version: 2.0.0
- extension key: integer-net/anonymizer
- [extension on GitHub](https://github.com/integer-net/Anonymizer)
- [direct download link](https://github.com/integer-net/Anonymizer/archive/master.zip)

Usage
-----------
Run the anonymizer via command line:

    cd shell
    php anonymizer.php
	
To display progress in real time:

    php anonymizer.php --progress
	
To only update the progress display every 100 entities:

    php anonymizer.php --progress 100

If you have n98-magerun installed, you can also use this command:

    n98-magerun db:anonymize

**Be aware that the process will run very long if you have more than a few thousand orders. Consider deleting old sales data first.**
	
[![asciicast](https://asciinema.org/a/9j4kylm874s4legd8ddbj494m.png)](https://asciinema.org/a/9j4kylm874s4legd8ddbj494m)


Requirements
------------
- PHP >= 5.4
- [Faker](https://github.com/fzaninotto/faker)
- [Magento-PSR-0-Autoloader](https://github.com/magento-hackathon/Magento-PSR-0-Autoloader)

Compatibility
-------------
- Magento CE 1.7, 1.8, 1.9
- Magento EE 1.12, 1.13, 1.14

Installation Instructions
-------------------------
1. Install via composer: `composer require integer-net/anonymizer`

   If the above command prompts you to `please define your magento root dir`, enter `./`
2. Configure Magento-PSR-0-Autoloader to use the composer autoloader. Add this to the `global` node of your `app/etc/local.xml`:

        <composer_vendor_path><![CDATA[{{root_dir}}/vendor]]></composer_vendor_path>

Alternatively download the archive from the [Github release page](https://github.com/integer-net/Anonymizer/releases) and extract it into your installation. It contains the Faker library and no additional configuration is required.

Support
-------
If you have any issues with this extension, open an issue on [GitHub](https://github.com/integer-net/Anonymizer/issues).

Contribution
------------
Any contribution is highly appreciated. The best way to contribute code is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developer
---------
Fabian Schmengler, [integer_net GmbH](http://www.integer-net.de)

Twitter: [@fschmengler](https://twitter.com/fschmengler) [@integer_net](https://twitter.com/integer_net)

Licence
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)

Copyright
---------
© 2015 integer_net GmbH
