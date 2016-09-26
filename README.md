# appserver.io cli
[![Build Status](https://travis-ci.org/AW3i/appserver-io-cli.svg?branch=aw%2Farguments)](https://travis-ci.org/AW3i/appserver-io-cli)
[![Coverage Status](https://coveralls.io/repos/github/AW3i/appserver-io-cli/badge.svg?branch=master)](https://coveralls.io/github/AW3i/appserver-io-cli?branch=master)

This tool generates a simple Rout.Lt project for apperver.io to help kick off your web application

## Usage

To create a simple project run
```bash
ant create-project
```
and then deploy it.

You can either deploy it locally with
```bash
ant local-deploy
```


or deploy it to a docker container.
The docker container is generated on the fly and pulls the latest appserver image available, and binds to port 8080 at localhost
```bash
ant docker-deploy
```

You can also run commands without ant, for example to create a new action you would run
```bash
bin/appserver action Example Example\\Namespace /path  path/to/webapp
```

To list all available commands run
```bash
bin/appserver
```
