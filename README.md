<p align="center"><img src="/resources/docs/banner.jpg"></p>

LaraCollab, developed with Laravel and React, serves as a project management tool. The primary idea behind this initiative is to provide developers or development companies with a free platform to efficiently manage clients, projects, log time, and generate invoices. You may wonder, 'Why another tool when there are already feature-rich options available for free?' Yes, that's a valid point. However, my aim is to offer a project management tool specifically tailored for Laravel developers, giving them option to integrate and customize features according to their unique workflows.

## Features

-   User roles (e.g., client, manager, developer, designer) with customizable permissions.
-   Management of client companies.
-   Manage client users that have access to company tasks.
-   Project management with user access control.
-   Task groups within projects (e.g., Todo, In progress, QA, Done, Deployed).
-   Task can have a assignee, due date, custom labels, time estimation (add manually or use timer), attachments, subscribers, and comments.
-   Task filters for efficient organization.
-   Real-time notifications and task updates via web sockets.
-   Mention functionality in task descriptions and comments.
-   Personalized "My Tasks" page for each user.
-   Activity page for projects or selected ones.
-   Invoice generation from billable tasks with logged time.
-   Print or download invoices directly from the platform.
-   Dashboard offering project progress, overdue tasks, recently assigned tasks, and recent comments.
-   Additional reports for daily logged time per user and total logged time.
-   Dark mode support for user preference.

## Screenshots

<p align="center">
<img src="/resources/docs/screenshots/Dashboard - light.jpeg" width="45%">
<img src="/resources/docs/screenshots/Dashboard - dark.jpeg" width="45%">
</p>
<p align="center">
<img src="/resources/docs/screenshots/Projects - light.jpeg" width="45%">
<img src="/resources/docs/screenshots/Projects - dark.jpeg" width="45%">
</p>
<p align="center">
<img src="/resources/docs/screenshots/Project tasks - light.jpeg" width="45%">
<img src="/resources/docs/screenshots/Project tasks - dark.jpeg" width="45%">
</p>
<p align="center">
<img src="/resources/docs/screenshots/Task - light.jpeg" width="45%">
<img src="/resources/docs/screenshots/Task - dark.jpeg" width="45%">
</p>
<p align="center">
<img src="/resources/docs/screenshots/My tasks - light.jpeg" width="45%">
<img src="/resources/docs/screenshots/My tasks - dark.jpeg" width="45%">
</p>
<p align="center">
<img src="/resources/docs/screenshots/Activity - light.jpeg" width="45%">
<img src="/resources/docs/screenshots/Activity - dark.jpeg" width="45%">
</p>
<p align="center">
<img src="/resources/docs/screenshots/Invoice - light.jpeg" width="45%">
<img src="/resources/docs/screenshots/Invoice - dark.jpeg" width="45%">
</p>

## Tech stack

[Laravel](https://laravel.com) for backend, [React](https://react.dev) for frontend and [Inertia](https://inertiajs.com) for "glueing" them together. For the frontend React UI components, the beautiful and awesome [Mantine](https://mantine.dev) library was used.

## Setup

### Project

1. Clone the repository using `git clone https://github.com/vstruhar/lara-collab.git`
2. Cd into the project
3. Install composer dependencies with `composer install`
4. Install npm dependencies with `npm install`
5. Copy the `.env` file with `cp .env.example .env`
6. Generate an app encryption key with `php artisan key:generate`
7. Create an empty database for the application
8. In the `.env` file, add database credentials to allow Laravel to connect to the database (variables prefixed with `DB_`)
9. Migrate the database with `php artisan migrate --seed`
10. You will be asked if you want to seed development data, for testing or development enter `yes`.

> NOTE: [Laravel Sail](https://laravel.com/docs/10.x/sail#introduction) was used for development so if you want you can also use Docker.

### Web sockets

You may use [Pusher](https://pusher.com) for web sockets, since number of free messages should be enough for the use case. Or you can use [open source alternatives](https://laravel.com/docs/10.x/broadcasting#open-source-alternatives).

To use Pusher, sign up, then create a project and copy paste app keys to `.env` (variables with `PUSHER_` prefix).

### Social login (Google)

1. Setup "OAuth consent screen" on Google Console ([link](https://console.cloud.google.com/apis/credentials/consent)).
2. Create "OAuth Client ID", select Web application when asked for type ([link](https://console.cloud.google.com/apis/credentials)).
3. Use generated "Client ID" and "Client secret" in the `.env` (`GOOGLE_CLIENT_ID` and `GOOGLE_CLIENT_SECRET`).

## Roadmap

-   Kanban view.
-   Change permission for specific user.
-   Add emojis to rich text editor.
-   Write tests.
-   Consider moving to TypeScript.
