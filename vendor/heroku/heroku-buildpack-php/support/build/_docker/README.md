# Building Platform Packages using Docker

## Building the Image

**After every change to your formulae, perform the following** from the root of the Git repository (not from `support/build/_docker/`):

    $ docker build --tag heroku-php-build-cedar-14 --file $(pwd)/support/build/_docker/cedar-14.Dockerfile .

## Configuration

File `env.default` contains a list of required env vars, some with default values. You can modify it with the values you desire, or pass them to `docker run` using `--env`.

Out of the box, you'll likely want to change `S3_BUCKET` and `S3_PREFIX` to match your info. Instead of setting `AWS_ACCESS_KEY_ID` and `AWS_SECRET_ACCESS_KEY` in that file, it is recommended to pass them to `docker run` through the environment, or explicitly using `--env`, in order to prevent accidental commits of credentials.

## Using the Image

From the root of the Git repository (not from `support/build/_docker/`):

    docker run --tty --interactive --env-file=support/build/_docker/env.default heroku-php-build-cedar-14 /bin/bash

That runs with values from `env.default`; if you need to pass e.g. `AWS_ACCESS_KEY_ID` and `AWS_SECRET_ACCESS_KEY` because they are not already in your environment, do either:

    AWS_ACCESS_KEY_ID=... AWS_SECRET_ACCESS_KEY=... docker run --tty --interactive --env-file=support/build/_docker/env.default heroku-php-build-cedar-14 /bin/bash

or

    docker run --tty --interactive --env-file=support/build/_docker/env.default -e AWS_ACCESS_KEY_ID=... -e AWS_SECRET_ACCESS_KEY=... heroku-php-build-cedar-14 /bin/bash

You then have a shell where you can run `bob build`, `support/build/_util/deploy.sh` and so forth.
