# Oxygen - Core Framework

![](https://img.shields.io/packagist/v/oxygen/core) ![](https://img.shields.io/packagist/l/oxygen/core) ![phpspec and phpstan](https://github.com/oxygen-cms/core/workflows/phpspec%20and%20phpstan/badge.svg)

This repository contains the core framework for Oxygen.

Oxygen is a framework for building modern web applications.
At its core, Oxygen is but a small addon to the excellent [Laravel Framework](http://laravel.com/),
however with the addition of many extensions, Oxygen becomes a full-featured CMS.
The modular nature of Oxygen means that you can pick and choose exactly how much you want, and make the framework into your own magnificent creation specifically fine-tuned to business needs.

For example:
- custom tables to store data: e.g.: inventory stock, concerts, upcoming events, artists, emails, contacts, bookings
- custom logic to drive business operations.

## Key Features

- **Modular**: you pick how much you need
- **Extensible**: everything about Oxygen, from it's clean UI to its core data structures, can be extended.
- **Open**: Oxygen makes use of hundreds of awesome PHP packages, installed through [Composer](https://getcomposer.org/)
- **Ready for Tomorrow**: Despite being written in PHP, what could be considered as quite a 'legacy' language, Oxygen strives to make use of proven and future-facing technologies such as the Laravel Framework, Doctrine ORM, PHP 7 and above etc...

## Framework Structure

All parts of the framework depend on the Laravel framework to varying degrees.

**Core packages**:

- *oxygen/data* - wrapper around Doctrine ORM
- *oxygen/core* - core framework, depends on *oxygen/data*
- *oxygen/crud* - scaffolding for Create-Read-Update-Delete operations, depends on *oxygen/data* and *oxygen/core*
- *oxygen/theme* - theming support - doesn't depend on any oxygen packages
- *oxygen/preferences* - dynamic preferences configuration, depends on *oxygen/core*, *oxygen/data*, *oxygen/theme*
- *oxygen/auth* - authentication, depends on *oxygen/core*, *oxygen/data*, *oxygen/preferences*

**Basic modules** - each of these adds some optional part of the backend interface, they can be mixed and matched as you please.

- *oxygen/mod-auth* - authentication - this one is pretty necessary to be able to access the backend interface
- *oxygen/mod-dashboard* - admin dashboard
- *oxygen/mod-import-export* - import/export database content
- *oxygen/mod-preferences* - preferences UI

Things which the CMS can store:

- *oxygen/mod-events* - adds events
- *oxygen/mod-media* - adds media items (images, videos, audio, PDFs)
- *oxygen/mod-pages* - adds pages

Deprecated:

- *oxygen/mod-security* - a basic log of all login attempts
- *oxygen/mod-marketplace* - a package marketplace - never took off so decided not worth the maintenance effort


## The Stack

Oxygen uses the Laravel PHP framework, which in turn is based off [Symfony](http://symfony.com/).
As well as Laravel, Oxygen uses countless other packages from Composer, however they are still in fluctuaction so they won't be listed here.

For the front-end, Oxygen's UI component uses [SCSS](http://sass-lang.com/) and a preprocessor for CSS.

## Development

Oxygen is no longer under heavy development, however can be considered mostly stable and it is being used in production on personal projects.
