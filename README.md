consultation-system
========================
* [local] consultation-system.test

Requirements
---
 * configure your local [projects enrironment](https://bitbucket.org/as-docker/projects-environment)
 * make sure You have [YAKE](https://yake.amsdard.io/) installed
 * make sure `consultation-system.test` domain is routed to your localhost


Run project
---
```
yake configure
yake up
yake install
```
* run `yarn run watch` in background to work with assets
* run `php bin/console cache:clear` to clear application cache
* run `php bin/console make:entity --regenerate App` to generate getters and setters of entities

Migrations
---
```
yake php bin/console doctrine:migrations:diff
yake php bin/console doctrine:migrations:migrate  
```
