# AndUs Email Infrastructure

Last reviewed: October 4, 2026

## Current status

- `withandus.com` is verified in Resend with DKIM, SPF, and DMARC DNS records managed through Namecheap.
- A live website inquiry successfully reached the configured recipient through Resend.
- The message uses the visitor's name and email as `Reply-To`, so replying goes to the person who submitted the form.
- Render currently reports the AndUs web service and Postgres database on free plans.
- A dedicated production queue worker is not currently provisioned.

## How an inquiry email works

```text
Visitor submits the Livewire form
             |
             v
Laravel validates the inquiry and recipient configuration
             |
             v
Laravel attempts to store the inquiry in Render Postgres
             |
             v
ContactInquirySubmitted builds the notification email
             |
             v
Laravel's Resend transport calls the Resend HTTPS API
             |
             v
Resend authenticates with the withandus.com DNS records
             |
             v
The notification reaches CONTACT_TO_EMAIL
```

The Resend API key authenticates the application. It must remain only in secure environment settings and must never be committed, placed in documentation, or shared in screenshots.

## Is it completely free?

No. The email provider is currently free within its limits, but the complete business workflow has infrastructure and domain costs.

| Component | Current state | Cost and limit |
|---|---|---|
| Resend transactional email | Free plan | $0/month for up to 3,000 emails per month, limited to 100 emails per day and 3 sending domains. Email activity is retained for 30 days. |
| Namecheap domain and DNS | Active | DNS records have no separate Resend charge, but `withandus.com` is a paid domain that must be renewed. The Namecheap screenshot showed registration through June 7, 2029. Renewal pricing can change. |
| Render web service | Free plan | $0 while within Render's limits. It spins down after 15 minutes without traffic and can take about a minute to wake. Free services share 750 workspace hours per month. |
| Render Postgres | Free plan | The current database expires on **November 2, 2026**. It becomes inaccessible at expiration and is deleted after a 14-day upgrade grace period. Free Postgres has no backups. |
| Production background worker | Not provisioned | A dependable Laravel queue worker on Render requires a paid worker. The smallest current worker plan starts at approximately $7/month. |

Current official references:

- [Resend pricing](https://resend.com/pricing) — free-forever plan, quotas, domains, and retention.
- [Render free-plan limitations](https://render.com/docs/free) — sleeping web services, monthly limits, and 30-day Postgres expiration.
- [Render pricing](https://render.com/pricing) — current database and worker pricing.

Prices and limits can change. Check the linked provider pages before making budget decisions.

## How long will it work?

- **Resend:** The free plan has no stated trial expiration. It continues while the account remains in good standing and usage stays within the current free limits.
- **Sending domain:** The Resend verification remains usable while the required DNS records stay intact and the domain remains registered.
- **Domain registration:** Namecheap showed `withandus.com` registered through June 7, 2029. Enable auto-renew and keep a valid payment method before that date.
- **Database:** The immediate deadline is November 2, 2026. Upgrade the existing Postgres instance before expiration to preserve stored inquiries and support a durable database queue.
- **API key:** Keep the production key private. Revoke and replace it immediately if it is ever exposed.

## Current delivery versus queued delivery

The live test proves that Resend delivery works. The repository also contains local Step 8 work that marks the mailable for queued delivery with three attempts and backoff. That queued behavior should not be treated as production-ready until all of the following exist:

1. A non-expiring production database with the `jobs` and `failed_jobs` tables.
2. A continuously running Render background worker.
3. The same application secrets and database connection available to the worker.
4. Production verification that queued inquiries are processed and failed jobs are visible.

Do not deploy the queue-only behavior without a worker. Otherwise, inquiries can be stored while their notification emails remain waiting in the queue.

## Maintenance checklist

### Each month

- Review Resend usage against the 3,000-per-month and 100-per-day limits.
- Review delivery, bounce, complaint, and suppression activity in Resend.
- Check Render for failed deploys, database warnings, usage limits, or suspended services.
- Submit one controlled test inquiry and confirm both database storage and email delivery.

### After every email-related deployment

- Confirm the contact form shows its success state.
- Confirm the inquiry exists in Postgres.
- Confirm Resend shows the message as delivered.
- Confirm Reply-To points to the inquiry submitter.
- If queues are enabled, confirm the job leaves the queue and no failed job is created.

### Every three to six months

- Confirm the Resend domain remains verified.
- Confirm the DKIM, `rsend`, `send`, and `_dmarc` DNS records remain present in Namecheap.
- Review who has access to Namecheap, Resend, Render, and GitHub.
- Keep two-factor authentication enabled on each infrastructure account.
- Rotate the Resend API key if access changed or exposure is suspected.

### Annually

- Confirm Namecheap auto-renew, payment information, and contact information.
- Review current Resend and Render pricing and limits.
- Review whether inquiry volume justifies a higher email plan or larger infrastructure.

## Immediate action items

1. **Upgrade or replace the Render Postgres database before November 2, 2026.** This is the most urgent reliability issue.
2. Keep the working Resend API key secret and stored only in Render.
3. Update the project's example environment documentation later: it still references `no-reply@andusllc.com` and does not document the Resend key, while the verified domain is `withandus.com`.
4. Finish and verify Step 8 before deploying queued delivery, including provisioning the production worker.
5. Enable account-level two-factor authentication wherever it is not already enabled.

## Quick troubleshooting

| Symptom | First checks |
|---|---|
| Form fails before success | Render logs, database availability, `CONTACT_TO_EMAIL`, and application configuration cache. |
| Inquiry stored but no email | Resend Emails log; if queues are enabled, check pending and failed jobs plus worker status. |
| Resend rejects the message | API key, sender address on `withandus.com`, domain verification, and quota usage. |
| Message bounces or is suppressed | Recipient address, Resend bounce details, and the suppression list. Do not repeatedly send to invalid addresses. |
| Site is slow on the first visit | The free Render web service may be waking after its 15-minute idle shutdown. |

## Ownership summary

- Namecheap owns the domain registration and authoritative DNS configuration.
- Render runs the Laravel application and stores inquiry data.
- Laravel validates the form, stores the inquiry, and builds the notification.
- Resend authenticates and delivers the outbound email.
- The business owner must maintain renewals, secrets, billing, monitoring, and periodic delivery tests.
