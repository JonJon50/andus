# AndUs Database Infrastructure

Last reviewed: October 4, 2026

## Current production database

| Item | Current state |
|---|---|
| Provider | Render Managed PostgreSQL |
| Database | `andus_db` |
| PostgreSQL version | 18 |
| Region | Virginia, matching the AndUs web service |
| Plan | Free |
| Status at review | Available |
| External access | Blocked by an empty IP allowlist |
| Connection pooling | None |
| High availability | Disabled |
| Managed backups | None on the free plan |
| Expiration | **November 2, 2026** |

The free database becomes inaccessible at expiration. Render provides a 14-day upgrade grace period and then deletes the database and its data. Upgrading or migrating before November 2 is the highest-priority infrastructure task.

## How the database works

```text
Browser
   |
   v
Laravel / Livewire on Render
   |
   | private internal database URL
   v
Render PostgreSQL
   |
   +-- services: choices shown on the website
   +-- contact_inquiries: submitted leads
   +-- jobs / failed_jobs: queued work and failures
   +-- sessions / cache: Laravel runtime state
   +-- migrations: schema deployment history
```

The Render-hosted Laravel application should use Render's **internal** database URL. It stays on Render's private network, avoids a public network hop, and continues to work even though external database access is blocked.

Local development and automated tests default to SQLite. Production uses PostgreSQL through Laravel's `pgsql` connection and the PHP `pdo_pgsql` driver installed in the Docker image.

## Business tables

### `services`

Stores the services displayed by the website:

- `id` — database-generated numeric key
- `title`
- `slug` — unique stable identifier
- `description`
- `icon` — optional
- `is_active`
- `sort_order`
- timestamps

The canonical-services migration provisions four active service records. Website code should identify service behavior with stable slugs while storing the real database ID in inquiries.

### `contact_inquiries`

Stores submitted leads:

- `id`
- `service_id` — optional foreign key to `services.id`
- `name`
- `email`
- `phone` — optional
- `company` — optional
- `message`
- `status` — defaults to `new`
- timestamps

If a service is deleted, PostgreSQL sets the inquiry's `service_id` to `NULL` instead of deleting the inquiry. This preserves the lead. Inquiry rows contain personal information and should not be copied into logs, screenshots, support tickets, or public documents.

## Laravel infrastructure tables

| Tables | Purpose |
|---|---|
| `migrations` | Records which Laravel migrations have run. |
| `jobs` | Pending database-backed queue jobs. |
| `failed_jobs` | Jobs that exhausted their retries. |
| `job_batches` | Laravel batch-job tracking. |
| `sessions` | Browser session state because the application uses the database session driver. |
| `cache`, `cache_locks` | Laravel cache and lock state. |
| `users`, `password_reset_tokens` | Standard Laravel authentication tables; the public site currently has no admin routes. |

## What was and was not verified

- Render metadata confirmed that the database is available, free, PostgreSQL 18, unpooled, non-HA, externally blocked, and scheduled to expire November 2, 2026.
- The successful live inquiry proves the application can currently connect and perform its lead-capture workflow.
- Repository migrations and models define the schema summarized above.
- No production inquiry contents were read for this document.
- A read-only hosted inspection attempt was correctly rejected because the external IP allowlist is empty. No networking setting was weakened.

## How to access the database safely

There is currently no Nova, Filament, or custom admin dashboard. Use one of the controlled options below.

### 1. Render Dashboard — safest for routine health checks

Open the `andus-db` resource in Render to review:

- availability and expiration
- CPU, memory, storage, and connection metrics
- deploy and maintenance events
- internal and external connection information
- networking rules
- recovery and backup features after upgrading

The Dashboard does not replace a row browser, but it should be the first stop when diagnosing availability.

### 2. Laravel inspection commands — best for schema checks

Run these only in an authorized environment using the intended database connection:

```bash
php artisan migrate:status
php artisan db:show --counts
php artisan db:table services
php artisan db:table contact_inquiries
php artisan queue:failed
```

These commands inspect migration, schema, table, and queue state. Confirm the selected environment and connection before running any Artisan database command.

### 3. `psql` — best free database client

Render provides a PSQL command and external URL under the database's **Connect** menu. External access currently requires a deliberate networking change:

1. Add only your current trusted public IP as a `/32` entry in the database IP allowlist.
2. Never use `0.0.0.0/0` for production access.
3. Use Render's complete external hostname and require TLS.
4. Treat the connection URL as a password. Do not paste it into chat, documentation, screenshots, Git, or shared shell history.
5. Remove the temporary allowlist entry when access is no longer needed.

Safe inspection examples after connecting:

```sql
SELECT current_database(), version();
SELECT count(*) FROM services;
SELECT count(*) FROM contact_inquiries;
SELECT status, count(*) FROM contact_inquiries GROUP BY status ORDER BY status;
SELECT count(*) FROM jobs;
SELECT count(*) FROM failed_jobs;
```

Do not select names, email addresses, phone numbers, or messages unless the business task actually requires viewing those records.

### 4. Graphical clients

TablePlus, DBeaver, or pgAdmin can connect with Render's external connection details and TLS. The same IP-allowlist and credential rules apply. A dedicated read-only PostgreSQL account should be created later for routine reporting instead of sharing the application's full-access credentials.

### 5. Render MCP read-only queries

Render's database query tool runs SQL inside a read-only transaction. The hosted tool currently cannot reach this database because external access is disabled and it has no stable IP to allowlist. A locally operated Render MCP setup or another explicitly allowlisted client is the safer alternative.

Changing the production IP allowlist, credentials, or database role requires a separate authorized task. This document made no such change.

## Backup and recovery

The free database has **no Render backups or point-in-time recovery**. If data is deleted or corrupted today, Render cannot restore an earlier version.

### Before the expiration deadline

1. Upgrade the existing database to a paid Render Postgres plan, or migrate to another durable PostgreSQL provider.
2. Create and securely store a logical backup with `pg_dump` before any migration or major schema change.
3. Test restoration into a separate empty database.
4. Confirm the application, migrations, service records, inquiry storage, and email flow against the durable database.

Paid Render Postgres adds managed recovery features. The recovery window depends on the workspace plan. Independent `pg_dump` exports are still useful for longer retention and provider portability.

Official references:

- [Create and connect to Render Postgres](https://render.com/docs/postgresql-creating-connecting)
- [Render Postgres backups and recovery](https://render.com/docs/postgresql-backups)
- [Render free database limitations](https://render.com/docs/free)
- [Laravel database inspection commands](https://laravel.com/docs/13.x/database)
- [Laravel migrations](https://laravel.com/docs/13.x/migrations)

## If the database breaks

### The website reports a database connection error

1. Open `andus-db` in Render and confirm its status is **Available** and it has not expired.
2. Check Render's status page and the database Events view for maintenance or an outage.
3. Check the AndUs web-service logs for the first `SQLSTATE` error without copying credentials or inquiry data.
4. Confirm the web service and database remain in the same Render workspace and Virginia region.
5. Confirm the application still has the intended internal database URL and PostgreSQL connection settings.
6. Check connection count and storage metrics.
7. Do not create a replacement database or change connection variables until the cause and recovery path are understood.

### A deployment reports a missing table or column

1. Review the failed deployment logs.
2. Compare `php artisan migrate:status` with the migrations in the deployed commit.
3. Back up the database before correcting migration state.
4. Fix the migration or deployment command in source control and redeploy through the normal process.
5. Avoid manually changing production tables unless a reviewed recovery plan requires it.

### Inquiries stop appearing

1. Submit one controlled test inquiry.
2. Check the web-service log for storage errors.
3. Confirm `contact_inquiries` exists and the latest migration ran.
4. Confirm the selected `service_id` exists, or is deliberately `NULL`.
5. Check database availability, expiration, connections, and storage.
6. Remember that email delivery alone does not prove the inquiry was stored because the application currently has a mail fallback when storage fails.

### Queued emails stop moving

1. Confirm the production queue worker exists and is running.
2. Count rows in `jobs` and inspect `php artisan queue:failed`.
3. Check worker logs and database connectivity.
4. Retry only after correcting the underlying problem.
5. Never delete queued or failed jobs merely to make a warning disappear.

### Data was accidentally deleted or changed

1. Stop further writes if continuing would worsen the incident.
2. Preserve logs and create a current export if possible.
3. On a paid database, restore point-in-time recovery into a **new** database and validate it before switching the application.
4. On the current free plan, recovery is possible only from an independent export. Without one, deleted data may be unrecoverable.

## Commands that must not be used casually in production

These commands can erase or reverse data and require an approved backup and recovery plan:

```text
php artisan migrate:fresh
php artisan migrate:reset
php artisan migrate:rollback
php artisan db:wipe
DROP DATABASE
DROP TABLE
TRUNCATE
DELETE without a reviewed WHERE clause
```

## Maintenance checklist

### Weekly until November 2, 2026

- Confirm the upgrade or migration plan is progressing.
- Review Render's database expiration warnings.
- Perform and verify a controlled inquiry test.

### Monthly after moving to durable storage

- Review database availability, storage, connection count, and slow-query symptoms.
- Confirm migrations match the deployed application version.
- Review failed queue jobs.
- Create or verify an off-provider logical backup.
- Confirm a recent backup can be restored into an isolated database.

### Every three to six months

- Rotate credentials using a controlled rollout.
- Review database access and remove unused users and IP allowlist entries.
- Review retention requirements for inquiry personal data.
- Test the documented restore procedure.

## Immediate action plan

1. **Upgrade or migrate `andus-db` before November 2, 2026.**
2. Create the first independent `pg_dump` backup before making database changes.
3. Keep external access blocked until a specific access session is needed.
4. When routine reporting becomes necessary, create a dedicated read-only database role and use a tightly allowlisted client.
5. Add a private admin/reporting interface only as a separately designed, authenticated feature—not as a public database browser.
6. Complete production queue-worker setup only after the database is durable.
