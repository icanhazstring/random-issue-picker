# Random Issue Picker

The random issue picker, can be used to pick surprisingly random issues in your preferred langauge.

Requirements:
* Composer
* PHP 7.3+

##How to use it

```shell
$ git clone https://github.com/icanhazstring/random-issue-picker.git
$ cd random-issue-picker
$ composer install
$ bin/rip random:issue
```

As a preparation for the [Hacktoberfest 2020](https://hacktoberfest.digitalocean.com) we have added some features to support langauge and label as input parameter, so now you can use

```shell
$ bin/rip random:issue -l php --label hacktoberfest
```

Default values:

| Parameter        |  Default Value       |
|------------------|----------------------|
| --language, -l   | php                  |
|  -- label        | "good first issue"   |
