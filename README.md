# Empowering Entrepreneurs

Aspiring-Entrepreneur-Adil is an entrepreneur portal with business resources and community features.

![PHP](https://img.shields.io/badge/PHP-2563EB?style=flat-square) ![MySQL](https://img.shields.io/badge/MySQL-0F172A?style=flat-square)

## Overview

The data model covers `admin`, `business_resources`, `contents`, `feedback`, `forum_comments`, `forum_posts`, `private_messages`, `service_categories`, `users`.

## Application areas

| Area | Pages in the repository |
| --- | --- |
| Admin | [`admin/add_content.php`](admin/add_content.php), [`admin/admin_add_category.php`](admin/admin_add_category.php), [`admin/admin_dashboard.php`](admin/admin_dashboard.php), [`admin/agent_feedback_admin.php`](admin/agent_feedback_admin.php), [`admin/edit_category.php`](admin/edit_category.php) |
| Root Pages | [`adminlogin.php`](adminlogin.php), [`chat.php`](chat.php), [`contents.php`](contents.php), [`dashboard.php`](dashboard.php), [`feedback_agent.php`](feedback_agent.php) |
| Agent | [`agent/add_category.php`](agent/add_category.php), [`agent/add_resource.php`](agent/add_resource.php), [`agent/agent_dashboard.php`](agent/agent_dashboard.php), [`agent/chat.php`](agent/chat.php), [`agent/edit_category.php`](agent/edit_category.php) |

## Getting started

### 1. Get the project

```bash
git clone https://github.com/nasirabbas827/Aspiring-Entrepreneur-Adil.git
cd Aspiring-Entrepreneur-Adil
```

### 2. Configure PHP and the database

1. Use a local PHP server with MySQL or MariaDB, such as XAMPP.
2. Import [`Database/interactive-adil.sql`](Database/interactive-adil.sql) into your local database server.
3. Set your local database connection details in [`admin/config.php`](admin/config.php), [`agent/config.php`](agent/config.php), [`config.php`](config.php).
4. Keep the existing asset and upload directory paths intact.

### 3. Start the application

With the database running, start a local development server from the repository root:

```bash
php -S 127.0.0.1:8000
```

Open http://127.0.0.1:8000/index.php. If using Apache/XAMPP instead, place the project in its document root and open the matching localhost folder.

## Repository guide

| Path | Purpose |
| --- | --- |
| [`CSS`](CSS) | Stylesheets |
| [`Database`](Database) | Database scripts |
| [`config.php`](config.php) | Application/database configuration |
| [`images`](images) | Image assets |
| [`index.php`](index.php) | Web entry page |

## Development notes

These setup instructions are based on the repository source. The application has not been executed as part of this documentation update; verify dependencies and configuration in your local environment.
