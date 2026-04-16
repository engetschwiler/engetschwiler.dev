---
title: "Managing (part of) my life: From Excel hell to Laravel paradise with Taab"
description: How an insurance agent's question led me to ditch spreadsheets and build Taab, a personal finance app powered by Laravel, event sourcing, and a handful of clever integrations.
---

One day, an insurance agent asked me a question that stopped me in my tracks: "Do you actually know your financial situation?" The answer was a resounding no. As long as my account balance stayed positive, I figured everything was fine. That moment was the spark behind Taab — a personal management application and my very own Laravel playground.

## Why I ditched Excel

Like many people, I started with an Excel spreadsheet. But after spending way too much time tabulating in every direction (hence the name Taab!), I realized I needed something better. Rather than relying on off-the-shelf tools, I decided to build my own application using Laravel — experimenting freely during train rides and between client projects, with zero pressure and full creative freedom.

## Building Taab: what's under the hood

Taab runs on Laravel and uses event sourcing (thanks Spatie!), Alpine.js, and Livewire. I've plugged in services like Mindee for receipt scanning, OpenRoute for calculating professional travel distances, and Algolia for lightning-fast search. The project has become my personal tech sandbox — whenever a new Laravel feature drops, Taab is where I try it out first.

![Taab application dashboard](/articles/images/2025-07-01-managing-my-life-from-excel-hell-to-laravel-paradise-with-taab/dashboard.png "Taab application dashboard")

## What Taab does for me

- **Dashboard:** A financial summary at a glance, with a privacy blur toggle for when you're presenting your screen.
- **Categories:** Fully customizable, with the ability to flag expenses as professional — essential come tax season.
- **Transactions:** Attach receipts, filter by time period, and search through years of history in seconds.
- **Easy entries:** Scan tickets with Mindee, set up recurring expenses, and use payment templates for common transactions.
- **Bulk import:** Drop a stack of tickets at once, with automatic duplicate detection so nothing gets counted twice.

![Transaction entry menu](/articles/images/2025-07-01-managing-my-life-from-excel-hell-to-laravel-paradise-with-taab/add-transactions.png "Transaction entry menu in Taab")

## Making tax season (almost) enjoyable

When tax season comes around, Taab's reports give me an instant overview of the year's income and expenses. Filling out forms becomes a simple copy-paste exercise, and year-over-year comparisons make it easy to spot trends or anomalies.

![VAT summary view](/articles/images/2025-07-01-managing-my-life-from-excel-hell-to-laravel-paradise-with-taab/vat-stats.png "VAT summary view in the Taab application")

## Integrating into my workflow

I also built a companion project management application called Flux. It synchronizes invoices and payments directly into Taab via Laravel Sanctum. When I mark an invoice as paid in Flux, my Taab accounts update automatically — no manual entry, no forgotten transactions.

## The technical details

Event sourcing is at the heart of the application. Every action is recorded as an event, which means I can replay my entire financial history, debug issues effortlessly, and trace the origin of any anomaly. It's the kind of architecture that pays off more and more as the data grows.

![Category statistics over 4 years](/articles/images/2025-07-01-managing-my-life-from-excel-hell-to-laravel-paradise-with-taab/category-stats.png "Category statistics added in Taab for a 4-year overview")

## Real-world hacks and lessons learned

Building a personal app comes with its own set of challenges:

- **Shared hosting limitations:** Background processing for ticket scanning doesn't always play nicely with shared environments.
- **Livewire maintenance:** Keeping Livewire components up to date across versions requires ongoing attention.
- **Kilometer calculations:** I integrated OpenRoute to automatically compute professional travel distances for tax deductions.
- **SBB train ticket parsing:** Without access to an official API, I wrote a parser that extracts travel data from automated SBB confirmation emails — scrappy, but it works.

## In a nutshell

Taab is more than just an app — it's a continuous process of learning and solving real-world problems with code. If you're a freelancer, a side-project enthusiast, or someone who's tired of fighting with spreadsheets, I hope my journey gives you some inspiration. And if you happen to know a better way to parse Swiss train tickets, I'm all ears.
