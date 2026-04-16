---
title: "Lochness CMS: Why I built my own Laravel-powered CMS"
description: The story behind Lochness CMS — a tailor-made, multilingual Laravel CMS that powers dozens of client projects, from its humble PHP 5 beginnings to a mature private Composer package.
---

For many years I've been building small, multilingual websites for clients who all share the same need: a simple, dependable way to manage their own content. Instead of choosing an off-the-shelf CMS, I gradually built my own. Today that system is called Lochness CMS — a Laravel-based, tailor-made CMS that powers dozens of client projects.

This article is a tour of where Lochness comes from, how it works, and why I still think a custom CMS makes sense in 2026.

## A long history of "homemade" CMSs

My journey with CMSs didn't start with Laravel.

Back in 2008, I built my first homegrown system, "human CMS". It was a very simple PHP 5 project: includes and requires everywhere, a big template file, vanilla CSS and JavaScript, and no build tooling at all. Once you logged in, a small admin panel appeared over the site so you could navigate pages and edit content with TinyMCE.

It was messy technically, but it worked. One big drawback: the CMS was merged into each project. If I wanted to update the CMS, I had to touch every site individually.

![The human CMS admin panel overlaid on the website, with TinyMCE editor and inline navigation](/articles/images/2025-12-09-lochness-cms-why-i-built-my-own-laravel-powered-cms/human-cms.png "The human CMS — my first homegrown content management system, circa 2008")

A few years later I created "interactive CMS", which was a big step forward. The CMS became a separate admin area: you logged in, saw a dashboard of shortcuts, and managed content independently of the front-end. The UI was more structured: table views, search, language switching, media management, and tabs for multilingual content. Technically it was built with PHP 7, early Laravel, the Caffeinated modules package, Gulp, Stylus, and jQuery. It was still tightly integrated into each project, but the concept of a reusable back-office was there.

![The interactive CMS dashboard with structured tile shortcuts for content management](/articles/images/2025-12-09-lochness-cms-why-i-built-my-own-laravel-powered-cms/interactive-cms.png "The interactive CMS — a separate admin area with a dashboard of shortcuts, built with early Laravel")

Since around 2019, those ideas have evolved into what I now call Lochness CMS.

## The tech stack behind Lochness CMS

Lochness is built on modern Laravel and a stack I'm very comfortable with:

- PHP 8.2+
- Laravel 11+
- Tailwind CSS
- Alpine.js
- A few NPM and Composer packages
- Several Spatie packages
- A repository pattern for data access

The CMS itself lives mostly in a dedicated Lochness folder, published as a private Composer package. Around it sits a fairly standard Laravel application that handles routing, controllers, and the database. This separation makes updates and maintenance much easier than in my early attempts.

To bootstrap new projects, I have a small command-line tool: `lochness new`. It's essentially a wrapper around the Laravel installer plus the CMS package and optional presets. I often reuse similar types of sites, so presets let me generate a usable base in one go: install Laravel, install Lochness, apply a project preset, grab a coffee, and come back to a fresh, ready-to-customize installation.

## Design philosophy: simple for clients, powerful for me

Lochness is not a general-purpose, "everyone can install this" CMS. It's deliberately tailor-made for my clients' needs and for the kind of sites I build.

There are a few key principles that guide it:

- **Simplicity for the client.** When a client logs in, they should be able to find the right section, edit or create content, and be done. No complex workflows, no hidden toggles spread across five screens.
- **Multilingual by default.** I work in Switzerland, so multiple languages are the norm. Lochness always assumes multilingual content and makes language switching and translation visible and straightforward.
- **Laravel as a back-end engine.** Because it's "just" a Laravel application under the hood, I can adapt Lochness to almost any project: simple brochure sites, content-heavy sites, or more complex structures with relations and custom logic.
- **Separation of concerns.** The CMS focuses on managing content and configuration. The front-end is free to be whatever the project needs, often custom Blade views that read from the CMS via clear models and repositories.

## The cockpit: dashboard and navigation

When you log into Lochness, you land on a dashboard made of tiles. Some tiles are built into the CMS (content sections, media, translations, cache tools), others can be project-specific.

![The Lochness CMS dashboard with modern tile layout for news, SEO, media, translations and more](/articles/images/2025-12-09-lochness-cms-why-i-built-my-own-laravel-powered-cms/lochness-cms.png "The Lochness CMS cockpit — a clean, modern dashboard with project-specific tiles")

The cockpit is fully bilingual (currently French and English), and supports dark mode. I originally underestimated dark mode until a client told me they couldn't see a button I was talking about — because I had only styled it for light mode. Since then, I've made sure the entire interface works well in both themes.

The main navigation is defined in a dedicated `lochness.php` config file. There I declare menu sections and entries, connect them to resources, and control access with permissions. This gives me a central place to shape how editors move through the CMS.

## Managing content: resources and repositories

The core building blocks of Lochness are resources. A resource could be pages, news, products, events — whatever a particular project needs.

To add a new resource, I use an Artisan command that scaffolds everything:

- A model
- A migration, seeder, and factory
- Tests for the front-end and back-office
- A dedicated resource class in the Lochness namespace

The resource class extends a base repository and gives me a set of hooks and properties to control behaviour:

- Whether the resource can be reordered
- Whether search is enabled
- Whether certain items can be edited or deleted (for example, make the "terms and conditions" page undeletable)
- Whether cloning is allowed (to duplicate an item with all its relations and media)

In the CMS UI, each resource appears as a table view with:

- Visibility toggles (to publish/unpublish)
- Reordering controls (if enabled)
- Search
- Tabs for each language
- Basic metadata like publication dates

From the client's perspective, this is clear and predictable: each section behaves similarly, with only the fields tailored to the specific content type.

## File management and media handling

I also maintain a separate [file manager package](https://github.com/livewire-filemanager/filemanager) built with Livewire. Lochness embeds this file manager directly into its interface.

From within the CMS, I can:

- Create folders and subfolders
- Upload and preview images
- Organise documents such as PDFs and Word files
- Search across the media tree

Editors see a familiar, visual structure for assets, and I can reuse the same file manager across projects.

In rich-text fields, I integrate the file manager through a custom plugin. The editor opens a file explorer, browses the file manager's structure, and inserts selected images into the content. Behind the scenes this uses an internal API exposed by the file manager package, but for the editor it's just "insert image from media library".

There are also dedicated components for:

- **Thumbnails:** drag-and-drop an image, crop it to the correct ratio, and store it consistently for front-end usage.
- **Galleries:** upload multiple images for a gallery field attached to a resource.
- **Documents:** manage downloadable files separately from images.

This combination keeps media management powerful but understandable, even for non-technical users.

## Strong support for translations

Translations are a big part of Lochness.

First, there are **content translations**: each resource is fully multilingual. Tabs let editors switch between languages (for example, French, English, German) and translate titles, bodies, and other fields. If a language isn't filled, the system falls back to another language based on configuration, so pages never suddenly become empty.

Second, there are **static translations**: labels for forms and buttons, interface copy that doesn't belong to a specific resource, and so on. Lochness has a dedicated area to manage these keys per language and visually indicate which ones are missing.

One of my favourite features is the ability to export and import static translations via Excel. I can:

- Export all static keys into an Excel file.
- Send it to a translation agency or client.
- Let them fill the missing cells.
- Re-import the file back into the CMS.

As long as the structure of the sheet is respected, the system updates all translations. This workflow speeds up large translation efforts significantly. I'm also considering offering the same export/import mechanism for full resources in the future.

## Extending the dashboard with custom tiles

While the CMS provides standard tiles out of the box, I can also define project-specific tiles that render custom Blade views and Livewire components.

For example, I created a weather tile that shows the current weather in Bern. The tile itself is declared in the `lochness.php` config, but the Blade view and Livewire component live in the project's code, not in the CMS package. This makes the dashboard a flexible space for project-specific widgets: statistics, reminders, integrations, and more.

Each tile can be configured with different column spans and positions, allowing me to adapt the layout to the project and the editor's needs.

## Why I still prefer my own CMS

With so many CMSs available — open source and commercial — it's fair to ask why I keep investing in my own system.

There are a few reasons:

- **Learning and experimentation.** Lochness is my playground for new ideas, packages, and techniques. When a new Spatie package or Laravel feature appears, I can try it here and then apply it to client work.
- **Full control.** I know every part of the codebase. If a client needs a specific behaviour, I can add it exactly where it belongs, without fighting against an external system's limitations.
- **Consistency across projects.** Because many of my projects share similar patterns, a shared CMS gives me a consistent foundation. I can focus my energy on the parts that truly differ.
- **Client-friendly simplicity.** I tailor the interface to the types of sites I build and the way my clients work. They don't have to learn a huge, generic admin system to publish a few pages.

Today, I'd estimate that around 80% of my time on a new project goes into client-specific work, and about 20% into adapting or extending the CMS for that project. Lochness is mature enough that it rarely needs big changes; most of the work is about small, focused improvements.

## Maintenance, releases and the future

Lochness is currently a private Composer package hosted in a private repository. When I need to update client sites, the process is straightforward: pull the project locally, run `composer update`, push to GitHub, and let GitHub Actions deploy to the server. Because the CMS is now properly packaged and decoupled, updates across many installations are relatively painless.

Over time it has reached version 5.x. Major versions typically come with bigger structural changes — especially around media handling and architecture. These days I mostly ship smaller, incremental updates rather than big breaking releases, because the system feels stable and mature.

I've been asked whether I'll ever open source Lochness. I'm not against it in principle, but turning a "for me and my clients" codebase into a clean public project would require a lot of refactoring, documentation, and ongoing support. For now, I prefer to keep it as a focused, private tool that quietly powers a couple of hundred projects in the background.

What matters most to me is that Lochness keeps doing its job: giving my clients a simple, multilingual, reliable way to manage their content, while giving me a flexible laboratory for everything I want to explore with Laravel.
