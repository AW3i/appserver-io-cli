# appserver.io cli

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