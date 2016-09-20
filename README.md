# appserver.io cli
[![Build Status](https://travis-ci.org/AW3i/appserver-io-cli.svg?branch=aw%2Farguments)](https://travis-ci.org/AW3i/appserver-io-cli)
[![Coverage Status](https://coveralls.io/repos/github/AW3i/appserver-io-cli/badge.svg?branch=master)](https://coveralls.io/github/AW3i/appserver-io-cli?branch=master)

## appserver:server
> action: start | stop | restart | status
>
> options: --with-fpm | --directory

```bash
$ vendor/bin/appserver appserver:server <action> [options]
```

## appserver:config
> action: change | add | remove
> type: parameter
>
> options: --container | --server | --param | --value | --config | --backup

```bash
$ vendor/bin/appserver appserver:config <action> [<type>] [options]
```
### appserver:config change

```bash
$ vendor/bin/appserver appserver:config change parameter --server message-queue --param workerNumber --value=4
```

### appserver:config add

```bash
$ vendor/bin/appserver appserver:config add parameter --server message-queue --param test --value 1
```

### appserver:config remove

```bash
$ vendor/bin/appserver appserver:config remove parameter --server message-queue --param test
```

### appserver:appconfig

```bash
$vendor/bin/appserver appserver:appconfig application-name Name\\Space webapp/directory
```
