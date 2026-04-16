---
title: "From FrontPage to the App Store: My NativePHP journey"
description: A self-taught web developer's 20-year journey from building school websites with Microsoft FrontPage to publishing a native iOS app on the App Store using Laravel and NativePHP.
---

Let me take you on a little journey — a journey that, for me, started way back in 2002.

That's when I built my very first website: my school's website. I was just a kid, poking around with Microsoft FrontPage, not knowing what I was really doing but loving every minute of it. For example, I liked switching between the interface modes for code and preview views. I could see what I was coding almost in real time. It felt like magic. But nothing online, just local development and previewing the HTML page in the Netscape browser.

Fast forward a few years, and I'd founded my own company, Bee Interactive, specializing in making websites and small "web apps". Since then, I've always liked creating interfaces and friendly UI's. Keeping the idea of making a real mobile app one day.

## The native app dream

But let's talk about the real adventure: my dive into NativePHP. If you're a developer, you probably know that urge to build something that lives on a device, not just floats in a browser tab. For years, I wanted to create desktop and mobile apps, but I always hit the same wall: I didn't have the skills. Objective-C? Swift? Xcode? Java? Kotlin? It all felt like learning to ride a unicycle while juggling flaming torches — possible, but not exactly inviting. Additionally, I'm a self-taught developer without an engineering background, which makes it challenging for me to dive into this subject easily. Everything I've learned so far has been through books, video tutorials, and working with my clients.

I remember watching Steve Jobs unveil the iPhone in 2007. It was revolutionary, and a few weeks later, the world was buzzing about mobile apps. I got my hands on the first iPhone very early in Switzerland, played with the handful of apps available, and thought, "Why not me?" So I jumped in, armed with nothing but determination and hours of online tutorials. One of the first apps that caught my attention was Things, from Cultured Code. This application was so simple yet so powerful, well-designed. The Things application is the kind of app I want to create one day! Since then, I spent countless evenings with Simon Allardice, an instructor from Lynda.com, trying to make sense of Objective-C. But to be honest, it was tough. I didn't have a formal developer background; I was self-taught, picking up scraps of knowledge wherever I could.

## Humbling beginnings

My first attempts at iOS development were, let's say, humbling and frustrating. I tried, failed, tried again, failed harder. After a while without evident progress, I set the dream aside and focused on web development, specifically PHP, WordPress, and finally, Laravel. It was a good fit. I could build things the way I wanted, solve problems, help my clients even better year after year, build my own CMS (of course!), and even create a little side project: Popcorn, a web app to organize my (obsessive) DVD collection (over 500 DVDs). The original idea was to have a database for my movies, and I still have the old files and the icon I designed back then. The app itself doesn't run anymore, but the memories are still there.

The web version of Popcorn was simple — built with PHP and MySQL, with no fancy features. But it worked. It's still running today, mostly because it's so basic that nothing ever breaks. I was proud of it. It was my first significant project, and it scratched that itch to build something useful.

## NativePHP changes everything

Then, in 2023, something changed. Something new went on the table. Marcel Pociot released NativePHP at Laracon US, and suddenly, the doors to native app development swung open for people like me. No more learning new languages or wrestling with Xcode nightmares. NativePHP lets me build desktop and mobile apps using the tools I already know — PHP and Laravel. I built a few apps for testing and learning how NativePHP works, and it was incredibly easy to create real macOS applications. I built a small project management application for my company, which is still heavily used and has been continually improved to this day.

And then, in February 2025, Simon Hamp gave a talk at Laracon about building mobile apps with PHP and NativePHP, and I was there, soaking it all in. The idea that I could bring Popcorn back to life — not just as a web app, but as a real, native app — was irresistible. So I dove in.

## How it works

The process was surprisingly simple. You start with a basic Laravel API application — controllers, models, etc. The usual suspects. Then, you install the `nativephp/mobile` package that creates an iOS folder inside your Laravel project with all the files you need for NativePHP. It generates the Xcode project for you. When you build the app, it bundles your Laravel application into the Xcode project and spits out a mobile app. No need to reinvent the wheel. It just works.

Coming back to Xcode was a bit of a shock — so much had changed since my last attempt. Swift had replaced Objective-C, and the whole ecosystem felt unfamiliar. But the workflow was straightforward: build your Laravel app, add the NativePHP package, and let it do the heavy lifting. I could even run the app on my iPhone or in the simulator, just like any other native app.

## Building the MVP

So, I decided to start over with my long-held dream: creating a real, native iOS app and publishing it to the App Store. If I can achieve this in a matter of weeks, I'll give mobile development another chance. The idea here was to recreate Popcorn, but with some core differences. It won't be "my DVD library", because, as time has passed and we're in the era of streaming, I don't have as many DVDs as I did before. It will be more of a "save for later" type of application. It came from discussions with my friends about movies and TV shows, and I had no centralized place to save what I wanted to watch later. I had the core functionality in mind. Now, let's build the MVP with Laravel and its all-new starter kits. So I won't focus on the design, only on making things work! Basically, I'll have two applications:

The first one is a "server" application, containing all the endpoints so that the mobile app can connect to and retrieve the necessary information. Additionally, this approach will enable the possibility of connecting with other users. The server application was ready in a few days, thanks to the robust Laravel ecosystem and, in particular, Laravel Sanctum.

Then, there is the interesting application: the mobile application. Same here, a simple application that relies on API calls to the server application. The layout and design will remain the same, using the same starter kit. Additionally, what's great here's the possibility, during the development phase, to treat this application as a Laravel application, preview it in your browser, fix bugs, run tests, and so on. And then, when you're done, you can transfer it to Xcode and build the iOS application. Of course, I've encountered a few bugs, even many bugs! I had to tweak the Xcode project slightly to make it work, as the development of the NativePHP iOS version was also ongoing. I also received some feedback and assistance from the NativePHP community, which was extremely helpful. After a couple of weeks of development, I finally had my first MVP and was ready to test the application on a real device! The test phase and adding the final touches were smooth, and I learned a lot about fixing issues with Xcode and its way to debug stuff.

## The challenges

Of course, there were challenges. Integrating features like file uploads with Livewire was challenging, especially since standard HTTP requests often don't work well with mobile devices. I had to get creative — encoding files as base64, sending them to the API, cropping images on the server, and sending them back. It's a workaround, but it gets the job done until the NativePHP team resolves the issues. But I've managed to finish the application with all its core features.

## Publishing to the App Store

Publishing to the App Store (and being accepted!) was its own adventure. You need an Apple Developer account (which isn't cheap), and the submission process is painfully slow, compared to the instant gratification of web development. Every new version has to be bundled, uploaded, and reviewed by Apple's team, which can take hours or even days. If you find a bug after the release, be prepared to wait again for the fix to go live. It's a lesson in patience.

But seeing my app in the App Store? That was worth every headache. According to Simon Hamp, it's probably only the second app on the App Store built with Laravel and NativePHP. That's pretty cool.

## Cross-platform and beyond

I also built a desktop version and the Android (made possible by Shane Rosenthal) version of Popcorn, using the same structure. For now, the desktop and mobile apps are separate, but the NativePHP team is working on making it possible to build once and deploy everywhere — on Windows, macOS, and Linux. The dream of true cross-platform development is closer than ever.

Popcorn itself has grown, too. It's not just a personal movie database anymore; it's a little social network where users can share their collections. You can search for movies, add them to playlists, take notes, and even see what other users are watching. Building the API instead of relying on a local SQLite database made sharing possible. There are already around 400 users who have an account! That's mind-blowing for me.

Performance is another area I'm working on. The API can be slow, and users expect instant responses. I'm optimizing wherever I can, learning from the community, and constantly improving the user experience.

## What's next

The best part? The possibilities are endless. Every time someone in the community shares a new project or a clever workaround, I learn something new. It's a collaborative, ever-evolving space, and I'm excited to see where it goes. It can only grow from now on.

So, what's next? For me, it's all about refining Popcorn — making it faster, more reliable, and even more fun to use. I want to explore new features, level up my skills, and become a better developer. The curve of learning has been incredibly fast over the last few months, compared to the time when I was struggling with Objective-C. I can't imagine what I'll be able to produce in the years to come. Most of all, I want to continue sharing what I've learned, because if there's one thing this journey has taught me, it's that we're all in this together.

Also, the NativePHP team released a service called [Bifrost](https://bifrost.nativephp.com/) that will help developers publish their apps with ease, without the hassle of getting through the app store and play store processes! I can't wait to test this out with Popcorn — it will be full circle!

There are tons of resources, articles, tutorials, videos, and many other tools that will help you in your mobile app development journey. The evolution of tools, surrounded by a solid ecosystem, is a winning combination!

If you're thinking about building native apps but feel intimidated by the learning curve, give NativePHP a shot. You might surprise yourself.
