[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SAM-IT/yii2-mariadb/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/SAM-IT/yii2-mariadb/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/SAM-IT/yii2-mariadb/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/SAM-IT/yii2-mariadb/?branch=master)
[![Build Status](https://travis-ci.org/SAM-IT/yii2-mariadb.svg?branch=master)](https://travis-ci.org/SAM-IT/yii2-mariadb)

# yii2-mariadb
While Yii2 supports MariaDB through its MySQL driver, the differences between MariaDB and MySQL are increasing.
At this time the driver included in Yii2 will not properly detect `JSON` columns in MariaDB and will not properly store
data in them.

The goal of this library is to implement the MariaDB specific changes required to get all features working in MariaDB that
are supported in the Yii2 core library for other DBMSes.

# Tests
The tests coverage is really high due to 2 reasons:
- All code extends their MySQL counter parts in the framework, only very little is added.
- We run the core tests for Yii2 (with some minor changes) to guarantee interoperability with the framework.



