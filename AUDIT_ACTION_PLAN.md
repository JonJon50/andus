# AndUs Website Audit and Action Plan

**Website:** https://www.withandus.com/  
**Project:** AndUs LLC business website  
**Audit date:** October 2, 2026  
**Purpose:** Restore reliable lead capture, prepare the website for launch, and improve its ability to sell website, AI, automation, data, and consultation services.

## Executive Summary

The website has a solid visual foundation and clearly introduces AndUs as a digital-services business. All five public pages load, the layout works on desktop and mobile, and the portfolio links are reachable.

The main business-critical workflow is not currently dependable. The live site generates Livewire and internal URLs with `http://` even though the site runs on HTTPS. The browser blocks the Livewire script, so the guided contact form does not become interactive and is unlikely to submit inquiries. The local application now boots successfully, and local HTTPS and service-guidance checks pass. Production deployment and live browser verification remain pending.

The first objective is therefore to restore and verify lead capture. Business-development work—consultation booking, better case studies, SEO, analytics, and stronger trust signals—should follow only after the inquiry workflow is reliable.

## Current Functionality

### Working

- Home, About, Services, Projects, and Contact pages load.
- The non-`www` domain redirects to the HTTPS `www` domain.
- Desktop and mobile layouts render without horizontal overflow.
- The mobile navigation opens and displays all navigation links.
- The Vite/Tailwind production asset build succeeds.
- Both external portfolio links load.
- Service positioning is consistent across the main pages.
- Contact inquiries are designed to be validated, saved, and emailed.

### Not Working or Not Yet Reliable

- Livewire does not load on the production site.
- Service-specific project guidance does not update when a service is selected.
- The contact form cannot be considered operational until an end-to-end submission is verified.
- Internal links are generated as HTTP and require redirects back to HTTPS.
- Inquiry storage may fail when production service IDs do not exist in the database.
- A fresh environment may use the wrong contact-recipient variable name.
- HTTPS URL generation and service guidance now have automated coverage, but inquiry validation, database storage, and email delivery still need tests.

## Priority Definitions

- **P0 — Launch blocker:** Prevents core operation or can cause lost leads.
- **P1 — High:** Important for security, reliability, or client conversion.
- **P2 — Medium:** Improves accessibility, search visibility, and professionalism.
- **P3 — Growth:** Adds capabilities that help the business scale.

## Ordered Action Plan

## Phase 1 — Restore a Bootable Application

### Verification update — October 2, 2026

- **Syntax repair completed:** Added the missing class closing brace in `app/Providers/AppServiceProvider.php`, then ran Pint on that file. Preserved the existing production-only HTTPS logic.
- **PHP checks passed:** All 35 PHP files under `app`, `bootstrap`, `config`, `routes`, `database`, and `tests` passed syntax checks.
- **Application checks passed:** `php artisan about` booted successfully, and `php artisan route:list --except-vendor` listed all five public routes.
- **Existing tests passed:** `php artisan test` passed 2 tests with 2 assertions. These example tests do not verify production HTTPS or the inquiry workflow.
- **Existing edits reviewed and preserved:** Docker exclusions, README content, the AndUs application-name default, and Livewire class-generation defaults remain unchanged. The README still lists Events instead of Projects; documentation cleanup is deferred.
- **Commit disposition pending:** No changes were committed, discarded, or deployed. Keep the provider repair separate from documentation and configuration cleanup when preparing commits.
- **Phase 1 technical checks complete:** Production proxy/HTTPS configuration and live Livewire verification remain for Phase 2.

### 1. Fix the PHP syntax error

**Priority:** P0  
**Location:** `app/Providers/AppServiceProvider.php`

**Problem**

The class is missing a closing brace after the `boot()` method. Laravel cannot start, list routes, or run tests.

**Action**

- Correct the method and class braces.
- Format the file using the project's PHP formatter.
- Run PHP syntax checks across application, configuration, route, migration, and test files.

**Done when**

- `php -l app/Providers/AppServiceProvider.php` passes.
- `php artisan about` starts successfully.
- `php artisan route:list --except-vendor` lists all five public routes.
- `php artisan test` can start without a parse error.

### 2. Preserve and review existing local changes

**Priority:** P0

**Problem**

There are existing uncommitted edits to deployment, application, and Livewire configuration files. These changes should be reviewed before deployment so an unrelated edit is not accidentally lost or published.

**Action**

- Review `.dockerignore`, `README.md`, `app/Providers/AppServiceProvider.php`, `config/app.php`, and `config/livewire.php`.
- Commit the HTTPS fix separately from documentation and configuration cleanup.

**Done when**

- Each local change is either intentionally committed or intentionally discarded by the project owner.
- The deployment commit contains only reviewed changes.

## Phase 2 — Fix Production HTTPS and Livewire

### Local verification update — October 2, 2026

- **Trusted-proxy handling implemented:** `bootstrap/app.php` directly configures trusted proxies with `at: '*'` and `Request::HEADER_X_FORWARDED_PROTO`. Forwarded HTTPS is recognized without trusting `X-Forwarded-Host`; ordinary HTTP requests without the forwarded scheme remain HTTP.
- **Production fallback preserved:** `AppServiceProvider` retains the production-only `URL::forceScheme('https')` fallback.
- **Focused tests passed:** 7 tests cover HTTP behavior, forwarded HTTPS internal and Livewire URLs, Livewire script availability, rejection of the forwarded host, and all four service-guidance selections. Proxy handling is tested independently of the production fallback, without overriding application boot.
- **Full suite passed:** 9 tests and 45 assertions.
- **PHP lint passed:** All 36 PHP files checked passed.
- **Other checks passed:** Pint, the production frontend build, and diff checks passed. The build emitted a non-blocking Node deprecation warning.
- **Production work pending:** Deployment and live browser verification remain pending, including Livewire initialization, absence of mixed content, and all four guidance states on the live contact page. Local tests do not establish that production is fixed.
- **Review only:** All changes remain local and unstaged. No commits, pushes, deployments, or Render-setting changes were performed; commit disposition remains pending.

### 3. Correct proxy and HTTPS URL generation

**Priority:** P0

**Problem**

The live HTTPS pages generate internal links and the Livewire script with `http://`. The browser blocks the insecure Livewire script as mixed content.

**Action**

- Set the production `APP_URL` to `https://www.withandus.com`.
- Ensure Laravel trusts the hosting platform's forwarded proxy headers.
- Force HTTPS URL generation in production only if trusted-proxy configuration does not fully resolve it.
- Clear Laravel's cached configuration and routes during deployment.
- Restart/redeploy the production service.

**Done when**

- Every internal link begins with `https://www.withandus.com`.
- The Livewire script loads over HTTPS.
- `window.Livewire` is available in the browser.
- The browser reports no mixed-content errors.
- Internal navigation no longer incurs HTTP-to-HTTPS redirects.

### 4. Verify Livewire interaction

**Priority:** P0

**Problem**

Selecting a service changes the visible dropdown value but does not update the guidance panel because Livewire is not running.

**Action**

- Select each of the four services on the production contact page.
- Confirm that the matching guidance title, description, and questions appear.
- Verify that loading and validation states render correctly.

**Done when**

- Website Development displays “Website Project Fit.”
- Custom Web Applications displays “Custom Application Fit.”
- Workflow Automation displays “Automation Opportunity.”
- Database & Reporting displays “Database & Reporting Fit.”

## Phase 3 — Make Lead Capture Dependable

### 5. Standardize the inquiry recipient configuration

**Priority:** P0  
**Locations:** `.env.example`, `config/mail.php`

**Problem**

The environment example defines `CONTACT_TO_ADDRESS`, but the mail configuration reads `CONTACT_TO_EMAIL`.

**Action**

- Choose one variable name and use it everywhere.
- Document the required production mail variables without committing credentials.
- Add a configuration validation or deployment check for a missing inquiry recipient.

**Done when**

- A fresh environment resolves `config('mail.contact_to')` to a valid address.
- Missing mail configuration fails clearly during deployment or health checks.

### 6. Use one dependable source for services

**Priority:** P0

**Investigation status:** Implementation is not finalized. Production database availability/configuration remains unresolved, and production service records have not been verified. The canonical-service migration is deferred. Production-only hard-coded service handling remains a known issue. No production changes were made. Further provisioning investigation is paused for this artifact.

**Problem**

Production uses hard-coded service objects with IDs 1–4, while local development reads the `services` table. If the corresponding production rows do not exist, saving an inquiry can violate the foreign-key constraint. The exception is caught, but the lead may never be stored.

**Action**

- Seed or migrate the four canonical services in every environment.
- Read services from the database in production and development.
- Keep stable slugs for business website development, custom applications, workflow automation, and database/reporting.
- Remove the production-only hard-coded service collection after the data is dependable.

**Done when**

- All four service records exist in production.
- Each submitted inquiry stores a valid `service_id` or a deliberate `null` value.
- No database exception is needed for normal form submissions.

### 7. Complete an end-to-end inquiry test

**Priority:** P0

**Local verification scope:** `tests/Feature/ContactInquiryFlowTest.php` covers validation, selected-service persistence, notification addressing/reply-to, rendered email content, and existing failure behavior using an isolated in-memory database, fake mail, and Laravel's in-memory array mailer. Application behavior is unchanged; production verification remains pending.

**Local verification results:** The focused inquiry-flow suite passed 25 tests with 350 assertions. The full suite passed 41 tests with 430 assertions. Laravel Pint ran on the new PHP test file. These results establish local coverage only and do not satisfy the production acceptance criteria below.

**Known acceptance gaps:** A persistence failure followed by successful mail currently displays the ordinary success message without storing the inquiry. Mail failure propagates without a custom inline submission error, and any already-stored inquiry remains. A previous success banner can remain visible after a later failed submission. Database failure logs include visitor email and exception details. These tests characterize current behavior rather than endorse it as dependable lead capture.

**Production limit:** No production inquiry was submitted, database records were not inspected or changed, and inbox receipt was not verified. Local fake/array mail tests cannot prove external delivery or a browser-to-production-database-to-inbox trace. Step 7's production end-to-end acceptance criteria remain unmet. No Render, environment, or deployment changes were made.

**Action**

- Submit a clearly labeled test inquiry on production.
- Verify inline validation for missing and invalid values.
- Verify the inquiry is written to the database.
- Verify the business receives the notification email.
- Verify the email reply-to address points to the prospective client's address.
- Verify the success message appears only after storage and notification succeed.

**Done when**

- One test inquiry is traceable from browser submission through database storage and email delivery.
- Failures show a useful message and are logged without exposing sensitive details.

### 8. Queue email and protect against delivery failures

**Priority:** P1

**Problem**

The form currently sends email synchronously. A slow or unavailable SMTP service can delay or fail the customer's request.

**Action**

- Queue the notification email.
- Ensure the production queue worker is running.
- Add retry and failed-job monitoring.
- Decide whether a successfully stored inquiry should display success even if the notification email is temporarily delayed.

**Done when**

- Form submission is not blocked by SMTP response time.
- Failed notifications can be retried without losing the inquiry.

### 9. Add spam and abuse protection

**Priority:** P1

**Action**

- Apply rate limiting to inquiry submissions.
- Add a honeypot or equivalent low-friction bot check.
- Add CAPTCHA only if actual spam volume requires it.
- Limit and normalize phone, company, and message input.

**Done when**

- Repeated automated submissions are throttled.
- Legitimate users can submit without unnecessary friction.
- Abuse attempts are logged without storing excessive personal data.

## Phase 4 — Add Automated Verification

### 10. Replace example tests with business-critical tests

**Priority:** P1

**Required tests**

- Every public page returns a successful response.
- Production URL generation uses HTTPS.
- The service list renders all active services in the intended order.
- Selecting a service changes the guidance panel.
- Name, email, and project message validation works.
- Valid submissions create an inquiry.
- Invalid service IDs are rejected.
- A notification email is sent to the configured recipient.
- Database failure behavior is explicit and tested.
- Mail failure behavior is explicit and tested.

**Done when**

- `php artisan test` passes locally and during deployment.
- A regression that breaks the contact form fails the test suite.

### 11. Add a deployment smoke test

**Priority:** P1

**Action**

- Check `/`, `/about`, `/services`, `/projects`, and `/contact` after deployment.
- Confirm HTTPS asset URLs.
- Confirm Livewire initialization.
- Confirm the contact page can perform a non-destructive interaction.
- Check the two portfolio URLs.

**Done when**

- Deployment is not considered complete until all smoke checks pass.

## Phase 5 — Improve Accessibility and Form Usability

### 12. Make navigation keyboard accessible

**Priority:** P2

**Problem**

The mobile menu uses a label connected to a checkbox hidden with `display: none`. This is difficult or impossible to operate reliably with a keyboard or assistive technology.

**Action**

- Replace the label/checkbox control with a real button.
- Add `aria-expanded` and `aria-controls`.
- Add a visible keyboard focus style.
- Add a skip-to-content link.
- Indicate the current page in navigation with `aria-current="page"`.

**Done when**

- The menu opens and closes with keyboard controls.
- Focus is always visible.
- Screen readers announce the menu's state.

### 13. Improve contact-form semantics

**Priority:** P2

**Action**

- Add meaningful `name` and `autocomplete` attributes.
- Use `type="tel"` and a suitable input mode for phone numbers.
- Mark required fields in both HTML and accessible text.
- Associate each error message with its field.
- Move focus to the first invalid field after submission.
- Announce validation, loading, and success messages with an appropriate live region.
- Disable the submit button only while a submission is actually in progress.
- Change loading copy from `Sending...` to `Sending…`.

**Done when**

- The form can be completed with keyboard and screen-reader navigation.
- Browser autofill recognizes common fields.
- Errors identify both the problem and the next action.

## Phase 6 — Clarify the Business Offering

### 14. Add consultation as a real service

**Priority:** P1

**Problem**

The site invites visitors to start projects but does not define a consultation product or give visitors a way to schedule one.

**Action**

- Add “Technology & AI Consultation” to the Services page.
- Explain who the consultation is for, its length, preparation, deliverable, price or pricing approach, and next step.
- Add “Book a Consultation” as a primary call to action.
- Add a scheduling workflow after the inquiry path is stable.
- Collect project stage, target timeline, approximate budget range, and preferred meeting method.

**Done when**

- A visitor can understand exactly what a consultation includes.
- A qualified visitor can request or schedule a consultation without guessing what to do.

### 15. Represent AI capabilities accurately

**Priority:** P1

**Problem**

The contact form labels rule-based prompts as “AI,” although the website does not use an AI model.

**Action**

- Rename the existing feature to “Guided Project Intake” or “Smart Project Guidance,” or connect it to a genuine AI service.
- If adding AI, define the business purpose, information sent to the model, retention policy, fallbacks, cost limits, and human review process.
- Add a concrete AI case study showing the original task, workflow, result, and measurable benefit.

**Done when**

- Marketing claims accurately match implemented functionality.
- Visitors can see a credible example of an AI consultation or integration outcome.

### 16. Strengthen trust and conversion content

**Priority:** P2

**Action**

- Add a founder biography, professional photograph, and relevant experience.
- Turn portfolio entries into short case studies with problem, solution, tools, and result.
- Add testimonials when permission is available.
- Add service starting points or explain how estimates are prepared.
- Add direct business contact information.
- Add expected response time and service area/remote availability.
- Replace the AI demo's generic “Create Next App” browser title.

**Done when**

- Visitors can identify who they will work with, what AndUs has delivered, and what happens after contact.

## Phase 7 — Search, Legal, and Measurement

### 17. Add page-specific search metadata

**Priority:** P2

**Action**

- Give every page a unique title and description.
- Add canonical HTTPS URLs.
- Add Open Graph and social-sharing metadata.
- Add Organization or ProfessionalService structured data.
- Generate an XML sitemap and reference it in `robots.txt`.
- Use descriptive sharing images and favicon assets.

**Done when**

- Each page has a unique search snippet.
- Canonical URLs use the final HTTPS `www` domain.
- Search engines can discover every public page through the sitemap.

### 18. Add privacy and service terms

**Priority:** P1

**Action**

- Add a privacy policy explaining inquiry data collection, use, storage, email delivery, and retention.
- Add appropriate service terms or consultation terms before accepting paid engagements.
- Link legal pages from the form and footer.
- Define how long unsuccessful leads are retained.

**Done when**

- Visitors can review data-use information before submitting personal information.

### 19. Add privacy-conscious business measurement

**Priority:** P3

**Action**

- Track visits to Services, Projects, Contact, and consultation pages.
- Track clicks on major calls to action.
- Track successful form submissions without sending message content or personal details to analytics.
- Establish a simple lead-status workflow: new, contacted, qualified, proposed, won, lost.

**Done when**

- AndUs can measure which pages and calls to action generate qualified inquiries.
- Personal inquiry content is excluded from analytics events.

## Recommended Execution Sequence

1. Fix `AppServiceProvider.php` syntax.
2. Run PHP linting, route listing, and the existing test suite.
3. Correct production proxy/HTTPS configuration.
4. Deploy and verify that Livewire loads over HTTPS.
5. Standardize contact email configuration.
6. Seed and consistently load services from the database.
7. Perform a labeled end-to-end inquiry test.
8. Add inquiry, email, HTTPS, and Livewire tests.
9. Add rate limiting and spam protection.
10. Improve form and navigation accessibility.
11. Add a defined consultation service and booking path.
12. Strengthen case studies, founder credibility, and AI positioning.
13. Add privacy terms, SEO metadata, sitemap, and analytics.

## Launch Gate

The site should not be treated as ready for active lead generation until all of the following are true:

- [x] Laravel boots without syntax or configuration errors (verified locally October 2, 2026).
- [ ] All internal URLs and assets use HTTPS.
- [ ] Livewire initializes on the live contact page.
- [ ] All four service-guidance states work.
- [ ] A test inquiry is stored in the production database.
- [ ] The notification email is delivered to the correct recipient.
- [ ] Form failures display a useful response and retain the user's input where appropriate.
- [ ] Automated contact-form and HTTPS tests pass.
- [ ] Spam throttling is active.
- [ ] Privacy information is linked next to the form.

## Post-Launch Success Measures

- Number of qualified inquiries per month.
- Percentage of Contact page visitors who submit an inquiry.
- Percentage of inquiries that become consultations.
- Percentage of consultations that become proposals.
- Percentage of proposals that become paid projects.
- Average time from inquiry to first response.
- Email-delivery and failed-job rate.
- Most requested service category.

## Suggested Next Implementation Milestone

Complete Phases 1–3 as one reliability milestone: bootable source, correct production HTTPS, working Livewire, consistent service data, and a verified inquiry submission. Do not add booking, analytics, or AI features until this milestone is complete.
