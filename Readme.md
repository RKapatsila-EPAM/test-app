## How to launch?

#### If you have `make` installed on your machine:
- Run `make copy-env-file`
- Define `YH_FINANCE_API_KEY=` in `.env.local`
- Run `make init`

Every next launch use `make start`

#### If you don't have `make`:
- copy `.env` file into `.env.local`
- Define `YH_FINANCE_API_KEY=` in `.env.local`
- Run `docker compose stop` (just in case)
- Run `docker compose up -d`
- Run `docker exec -it php_env php bin/console import:company-symbols https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json` (Company data importer)

Every next launch use `docker compose up -d`

#### Note
Data has to be imported via `docker exec -it php_env php bin/console import:company-symbols https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json`, othervise app will always return 400 error.
Command is configured as scheduled task to be executed every 15 minutes via cron.
To change that config please edit `docker/cron/cron.txt` and run `make update-cron` afterwards.

## How to test?

### Launch tests
#### If you have `make` installed on your machine:
- `make test`

#### If you don't have `make`:

- `docker container stop php_queue_env` (need to stop queue runner to ensure that there's no othe connection to Database. Yes, in current config I use Database, for simplicity reasons.)
- `docker exec -it php_env php bin/console doctrine:database:drop --force --env=test || true`
- `docker exec -it php_env php bin/console doctrine:database:create --env=test || true`
- `docker exec -it php_env php bin/console doctrine:migrations:migrate -n --env=test || true`
- `docker exec -it php_env php bin/console doctrine:fixtures:load -n --env=test || true`
- `docker exec -it php_env php bin/phpunit`
- `docker compose up -d`

#### Check code coverage:
- `docker exec -e XDEBUG_MODE=coverage -it php_env php bin/phpunit --coverage-text`

### Web
- [Simple endpoint to do all the tasks](http://localhost/company?symbol=ERIC&startDate=2024-11-01&endDate=2025-01-01&email=r.kapatsila@gmail.com)
- [API Documentation](http://localhost/api)
- [API Endpoint](http://localhost/api/company-quote?symbol=CFNB&startDate=2024-11-01&endDate=2025-01-01&email=r.kapatsila@gmail.com)
- [Mailpit](http://localhost:8025/)

### Leftovers
- Code coverage can be improved.
- Instead of Controller we can use StateProviders.
- Reconfigure app to use `redis` as a cache and `rabbitMq` as a message broker.
- Make sense to introduce more refined architecture in terms of code: at this point it's a bit messy, probably align better with API Platform.
- Introduce proper task planner to import company names and symbols.
- Consider more aggressive response caching for API.
- Add logs.
- Improve `.env` management.
- Add linters.
- Add code-fixers.
- Add pipelines to run tests.
- Resolve deprecations.
- etc ...