---
title: "Building my first open source package: A Livewire file manager for Laravel"
description: The story behind my first open source contribution — a simple, clean file manager for Laravel built with Livewire, Alpine.js and Tailwind CSS, inspired by macOS Finder.
---

For years I have relied on open source packages in my daily work as a Laravel developer. At some point I realised I had never actually published a package of my own. That thought stayed with me, and when a client project created the perfect opportunity, I decided it was time to change that.

This is the story behind my first open source package: a simple, clean file manager for Laravel, built with Livewire, Alpine.js and Tailwind CSS. The source code is available on [GitHub](https://github.com/livewire-filemanager/filemanager).

## Why I decided to build a file manager

On paper, building a file manager in 2024 sounds unnecessary. There are already countless tools embedded in CMSs like WordPress, as well as many standalone file managers. But a specific client request convinced me to take a different path.

The client needed to manage files directly inside an existing Laravel application. The off-the-shelf options I evaluated either imposed a rigid structure, felt too complex for the end users, or would have required a lot of adaptation to really fit our needs. I wanted something that:

- integrated naturally into a Laravel app,
- gave me full control over the behaviour and design, and
- stayed as simple and clean as possible for non-technical users.

At the same time, I was looking for a meaningful way to learn what it really takes to design, package and maintain an open source project. This file manager became that learning vehicle.

## The philosophy: simple interface, flexible structure

From the beginning, I designed the file manager with simplicity in mind, especially from the user's point of view. I'm a big fan of clean interfaces, and I took a lot of inspiration from macOS Finder.

When you first open the file manager, you don't see a complicated structure or a huge configuration screen. You see almost a blank space. From there, you decide how to organise your content: you create the first folder (for example "Home"), then any subfolders you need, and you upload files where they make sense.

There are no imposed conventions for how the tree must look. The package doesn't dictate naming schemes or folder hierarchies. Once it's installed, you are free to adapt it to your project and your mental model.

Of course, a simple interface usually means more complexity hidden behind the scenes. I consciously accepted that trade-off. I'd rather invest in code complexity if it lets the user experience stay calm and obvious.

## The tech stack behind the package

Because this package grew out of real-world Laravel work, it is very much rooted in the Laravel ecosystem.

- **Laravel** provides the framework, routing, and package structure.
- **Livewire** powers the reactive behaviour: double-clicking folders, updating lists, handling uploads and search without a full page reload.
- **Alpine.js** adds a bit of lightweight frontend interactivity where needed.
- **Tailwind CSS** keeps the styling consistent and easy to extend.
- **Spatie Media Library** handles all the media logic: files, database records, and thumbnail generation.

When you install the package, it publishes a migration that creates a folders table. Spatie Media Library adds its own media table. In a typical Laravel 11 setup, the queue connection is set to the database, which is perfect for generating thumbnails asynchronously in production. For demos or very small setups, I often switch this to `sync` so I don't need to run workers; thumbnails are generated immediately.

I also tried to keep the UI self-contained. Folder and file icons are SVGs, not part of an external icon library. There are no heavy CSS or JavaScript dependencies beyond what most Laravel developers already know. The goal is for the file manager to be easy to drop into different back-office environments, including tools like Filament.

## How the file manager works in practice

Once the package is installed and the migrations are run, integrating the file manager into an application is a matter of including the provided component in a Blade view. In a fresh Laravel app, I usually replace the default welcome view while I'm testing. In an existing project, you can add the component wherever it makes sense in your layout.

The first time you open it, the database is empty, so the interface invites you to create an initial root folder. From there, you can:

- create nested folders by double-clicking,
- navigate using automatically generated breadcrumbs, and
- upload one or multiple files into any folder.

If thumbnails are enabled via the queue system, image files show previews instead of generic icons, which gives the whole interface a very familiar, "desktop" feel. Selecting a file opens a side panel with metadata such as the creation time and file size. This panel is deliberately simple today, with room to grow later as I add more information and options.

For serving files publicly, I expose a dedicated route. Once that route is configured, I can select a file, copy its public URL, and reuse that link anywhere else in the application — classic CMS-style behaviour, but inside a bespoke Laravel project.

Search is also built in. When I type a query, it scans the entire tree and returns matching files and folders. This has been very helpful when the number of assets starts to grow and the structure becomes deeper.

## Localisation and customisation

My previous talk was about translations, so localisation was important to me here as well. The file manager follows Laravel's localisation system. If I change the application locale, the interface language follows. Developers can publish the translation files and views to adjust wording or add new languages.

For now, configuration is intentionally minimal. The package tries to do a sensible default setup without overwhelming you with options. That said, the structure leaves space for more advanced configuration later: custom table names, permissions, or integration with existing folder models, for example.

## Keeping it lightweight and independent

Another strong goal for me was independence. I didn't want this package to pull in a large amount of CSS or JavaScript just to function. Everything is built on tools that are already common in modern Laravel applications: Livewire, Alpine.js, Tailwind, and Spatie Media Library.

Because of that, the file manager is also a sort of reference implementation. If you're interested in how to implement double-click behaviour in Livewire, how to manage breadcrumbs, or how to orchestrate uploads and thumbnails, you can read the code, copy patterns, and adapt them to your own components.

## What comes next

This is my first open source package, and I very much see it as a starting point. There is still plenty to do: improving the internal code quality, polishing the UI and UX, and adding a proper test suite.

Most importantly, I'm looking forward to feedback from other developers. Real-world usage will show which features are missing, which assumptions don't hold, and which ideas could be pushed further. I already have some directions in mind — more advanced media management, richer metadata, better permission handling, and deeper integrations with admin panels — but I want those decisions to be shaped by actual needs.

For me, this project is both a practical solution and a learning journey. After years of using other people's packages, it feels good to give something back: a focused, clean and flexible file manager that tries to reproduce the best parts of an operating system's file handling, while remaining firmly in the Laravel and Livewire world.

Check out the project on [GitHub](https://github.com/livewire-filemanager/filemanager) — contributions and feedback are welcome.
