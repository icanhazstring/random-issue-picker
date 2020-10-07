# Random Issue Picker

The random issue picker, can be used to pick surprisingly random issues in your preferred langauge.

Requirements:
* Composer
* PHP 7.3+

## How to use it

```shell
$ git clone https://github.com/icanhazstring/random-issue-picker.git
$ cd random-issue-picker
$ composer install
$ bin/rip random:issue
```

As a preparation for the [Hacktoberfest 2020](https://hacktoberfest.digitalocean.com) we have added some features to support `language` and `label` as input parameter, so now you can use

```shell
$ bin/rip random:issue -l php -t hacktoberfest -t php --label good-first-isste
```

Default values:

| Parameter        |  Default Value       | Example                  |
|------------------|----------------------|---------------------------
| --language, -l   | php                  | -l javascript            |
| --topic, -t      | hacktoberfest        | -t tool -t hacktoberfest |
| --label          |                      | --label good-first-issue |

Note:

As in the first step a random repository will be searched that matches the
given language and topics and in the second step a random issue will be selected
from this repository, it is more likely to not find an issue, if the --label
parameter is used. 

## Watch me create this tool
[YouTube : Making The Tool](https://www.youtube.com/watch?v=QRf4CQxpznM)

## How to contribute

```shell
$ git clone https://github.com/icanhazstring/random-issue-picker.git
```

The minimum requirement, as mentioned above, is PHP 7.3. You either have it installed locally, or you can
run the tool and every check using `docker`. There is `Makefile` present to support your work.

You can run `make up` to build the image and run the container.
> For more available commands refer to the [Makefile](Makefile)

After you have done this, you can install the dependencies using `make composer install`.
When you've made your changes, create a pull request and you are ready to go.

Have fun contributing :+1:
