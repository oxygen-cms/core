# Oxygen - Core Framework

![](https://img.shields.io/packagist/v/oxygen/core) ![](https://img.shields.io/packagist/l/oxygen/core) ![phpspec and phpstan](https://github.com/oxygen-cms/core/workflows/phpspec%20and%20phpstan/badge.svg)

This repository contains the core framework for Oxygen.

Oxygen is a framework for building web applications.
At its core, Oxygen is but a small addon to the excellent [Laravel Framework](http://laravel.com/),
however with the addition of many extensions, Oxygen becomes a full-featured CMS.
The modular nature of Oxygen means that you can pick and choose exactly how much you want, and make the framework into your own magnificent creation specifically fine-tuned to business needs.

For example:
- custom tables to store data: e.g.: inventory stock, concerts, upcoming events, artists, emails, contacts, bookings
- custom logic to drive business operations.

## Framework Structure

All parts of the framework depend on the Laravel framework to varying degrees.

**Core packages**:

- [oxygen/data](https://github.com/oxygen-cms/data) - wrapper around Doctrine ORM
- [oxygen/core](https://github.com/oxygen-cms/core) - core framework, depends on *oxygen/data*
- [oxygen/crud](https://github.com/oxygen-cms/crud)  - scaffolding for Create-Read-Update-Delete operations, depends on *oxygen/data* and *oxygen/core*
- [oxygen/theme](https://github.com/oxygen-cms/theme)  - theming support - doesn't depend on any oxygen packages
- [oxygen/preferences](https://github.com/oxygen-cms/preferences)  - dynamic preferences configuration, depends on *oxygen/core*, *oxygen/data*, *oxygen/theme*
- [oxygen/auth](https://github.com/oxygen-cms/auth) - authentication, depends on *oxygen/core*, *oxygen/data*, *oxygen/preferences*

**Basic modules** - each of these adds some optional part of the backend interface, they can be mixed and matched as you please.

- [oxygen/mod-auth](https://github.com/oxygen-cms/mod-auth) - authentication - this one is pretty necessary to be able to access the backend interface
- [oxygen/mod-import-export](https://github.com/oxygen-cms/mod-import-export) - import/export database content
- [oxygen/mod-preferences](https://github.com/oxygen-cms/mod-preferences) - preferences UI

Things which the CMS can store:

- [oxygen/mod-events](https://github.com/oxygen-cms/mod-events) - adds events
- [oxygen/mod-media](https://github.com/oxygen-cms/mod-media) - adds media items (images, videos, audio, PDFs)
- [oxygen/mod-pages](https://github.com/oxygen-cms/mod-pages) - adds pages

Deprecated:

- *oxygen/mod-dashboard* - admin dashboard - **will be integrated into new Vue.JS user interface**
- *oxygen/mod-security* - a basic log of all login attempts - **now integrated into the authentication module**
- *oxygen/mod-marketplace* - a package marketplace - **never took off so decided not worth the maintenance effort**


## The Stack

Oxygen uses the Laravel PHP framework, which in turn is based off [Symfony](http://symfony.com/).
As well as Laravel, Oxygen uses countless other PHP packages from Composer, too many to list them all here.

Oxygen is currently undergoing a modernization process to migrate to a swish new user interface written with Vue.js and built with Webpack. More to come...
