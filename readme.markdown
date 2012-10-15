Anonymizer
=====================
Anonymizes all Customer Data

Facts
-----
- version: 0.1.0
- extension key: IntegerNet_Anonymizer
- [extension on GitHub](https://github.com/avstudnitz/IntegerNet_Anonymizer)
- [direct download link](https://github.com/avstudnitz/IntegerNet_Anonymizer/tarball/master)

Description
-----------
The extension anonymizes customer data from the following data objects:
- Customers
- Customers Addresses
- Orders
- Order Addresses
- Quotes
- Quote Addresses
- Newsletter Subscribers

Data is taken from http://fakester.biz.

Zipcode, City, State and Country aren't anonymized so shipping and tax calculations still work correctly.

Relations stay intact, so customer addresses and order addresses still match the customer data after anonymization.

Requirements
------------
- PHP >= 5.2.0
- Mage_Core
- Mage_Customer
- Mage_Sales
- Mage_Newsletter

Compatibility
-------------
- Magento >= 1.4

Installation Instructions
-------------------------
1. Download the extension from the link above and copy all the files into your document root (except this readme.txt).
2. Clear the cache, logout from the admin panel and then login again.
3. Call the extension from from System -> Tools -> Anonymizer.
4. Alternatively, call the extension via shell: php -f shell/anonymizer.php

Support
-------
If you have any issues with this extension, open an issue on [GitHub](https://github.com/avstudnitz/IntegerNet_Anonymizer).

Contribution
------------
Any contribution is highly appreciated. The best way to contribute code is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developer
---------
Andreas von Studnitz

[http://www.integer-net.de](http://www.integer-net.de)

[@avstudnitz](https://twitter.com/avstudnitz)

Licence
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)

Copyright
---------
(c) 2012 integer_net GmbH
