Introduction
=====

This CLI tool is built to scrape items off the defined website and output these in JSON format.

Business Rules:

- Callable from CLI.
- JSON array of all the product.
- Output `option title`, `description`, `price` and `discount`.
- Ordered by annual price with the most expensive package first.

`Caveat: Developed and tested on Linux.`

Outstanding Improvements
----

- Integration/feature tests for the command.
- Project classes heavily depend on concretion, use interfaces more.
- Entity properties to be accessed by methods and properties made private.
- CI implementation.
- Remove lingering namespaces in classes.
- Collection to be made iterable.

Requirements
-----

To run this tool you'll need:

- php8
- composer

This tool ships with `docker-compose` configuration file which is exposed through `make`. If you've got `docker/make` installed you won't need anything else.

Configuration
-----

Scrape configuration can be found in `config/parameters.yml` file.

How to run
-----

Before you can run, you'll need to install dependencies

```sh
make install
```

To run as standard

```sh
make scrape
```

This will launch a docker container to run the command in. If you'd like to run the command locally, please find the command in the `makefile`.

Development
-----

To run unit all tests:

```sh
make tests
```

You'll find development related commands in the `makefile`. The basic ones are:

```
- make update # Update composer dependencies.
- make lint # Run linter/php-cs on the src folder.
- make tests # Run unit tests.
```
