---
title: "I rebuilt my terminal around the way I work"
description: "Running several AI agents in parallel broke my old terminal setup. So I built iTerminal, a macOS terminal where every tab is a project and I always know which agent is waiting for me."
french: "https://bee-interactive.ch/blog/comment-j-ai-recree-un-terminal-a-mon-usage"
---

I've lived in iTerm for years. Tests, deploys, git, database dumps: if I'm working, there's a terminal open somewhere.

What changed is who else is in there. A good part of my code is now written by Claude Code, which also runs in the terminal. I give it a task, it reads the project, edits files, runs the tests, and asks before doing anything risky. Since it works on its own, nothing stops me from running three or four of them at once, one per project.

That's where iTerm stopped keeping up.

## Too many windows, not enough signal

My setup was one window per project: Claude on one side, a second shell next to it to check what it was doing. Multiply that by four projects and most of my day was spent hunting for the right window.

The worst part was the silent wait. An agent asks me a question while I'm busy in another project, and it just sits there. Nothing blinks, nothing pings. Sometimes I'd find it ten minutes later, still waiting for a "yes".

So I set myself a simple goal: the day I can uninstall iTerm without missing it.

## One tab, one project

The whole idea fits in one sentence: every tab is a project, with Claude on the left and a shell in the same folder on the right.

A new tab starts as a plain terminal. I `cd` into a project, type `clauded`, and the tab turns into a workspace.

![A plain iTerminal tab with the clauded command typed in a project folder](/img/articles/2026-11-26-i-rebuilt-my-terminal-around-the-way-i-work/terminal.webp "A plain shell. One command turns it into a project workspace.")

Claude opens on the left, and the shell slides to the right with its history intact. If that folder already has a tab, the app jumps to it instead of opening a duplicate. When Claude is done, the tab goes back to being a regular terminal.

The status bar at the bottom shows what I actually look at all day: the branch, how many files changed and, when the site is served by [Alpage](/articles/2026/04/23/alpage-application), its local URL. One click opens it in the browser.

![An iTerminal project workspace: tab list on the left, Claude working in the middle, the project's tests on the right](/img/articles/2026-11-26-i-rebuilt-my-terminal-around-the-way-i-work/project.webp "Claude works on the left, I check on the right. Branch, changed files and local URL at the bottom.")

## Knowing who's waiting

This is the feature the whole app was built for. iTerminal knows whether each agent is working, waiting for an answer, or done. It doesn't guess: Claude Code reports every state change itself through hooks, and the app just listens.

A waiting project gets an orange badge in the tab list. The status bar counts how many agents are working and how many are waiting, and one shortcut takes me straight to the next one that needs me.

When Claude asks for permission, say to run a build, a card appears in its panel. I answer with a single key: 1 for yes, 2 for yes and don't ask again, 3 for no.

![A permission request in iTerminal: Claude wants to run npm run build, with three keyboard answers](/img/articles/2026-11-26-i-rebuilt-my-terminal-around-the-way-i-work/approval.webp "A project is waiting: the card shows up in Claude's panel and the tab gets a badge.")

## Reading before accepting

Letting an AI write code doesn't mean I stop reading it. If anything, I read more. The right-hand panel can switch from the shell to a diff view: changed files, added and removed lines, with syntax highlighting.

It updates live while Claude works. I stage file by file or hunk by hunk, then commit the step. And when a line bothers me, I click it and write a comment. It goes straight to Claude, with the file and line attached.

![iTerminal's diff view: the list of files Claude changed and the added lines in one of them](/img/articles/2026-11-26-i-rebuilt-my-terminal-around-the-way-i-work/changes.webp "The diff view updates as Claude works.")

## Nothing gets lost

When a terminal crashes, it usually takes everything running inside with it. In iTerminal, sessions live outside the app. I can quit it, update it or watch it crash: on the next launch, every tab picks up its session exactly where it was.

Even after a reboot, tabs come back in their folders and Claude resumes its conversation. The only thing that ends a session is me closing the tab on purpose.

## Always one shortcut away

`cmd+enter` brings the window up full screen, above everything else, on whichever display the mouse is on. Same shortcut to hide it. No Dock icon, no windows to arrange: the terminal is there when I need it and gone when I don't.

Everything is custom-drawn, settings included, and fully usable from the keyboard. Font, size, line height, theme, transparency, shortcut, notifications, language (French or English, following the system by default). Changes apply instantly.

![iTerminal's settings panel, open on the Appearance section](/img/articles/2026-11-26-i-rebuilt-my-terminal-around-the-way-i-work/settings.webp "Settings are custom-built too, and keyboard-only if I want.")

## Rebuilt, not reinvented

I didn't write a terminal emulator from scratch. The hardest part, drawing text fast and correctly, comes from [Ghostty](https://ghostty.org), an open-source terminal known for its speed. Its core is designed to be embedded in other apps, so that's what I did. Everything around it is mine: the project tabs, the agent tracking, the diff view, the sessions that survive.

And I built it with Claude Code, inside the very terminal I was replacing. Before writing a line, I described what the app should do and how it should look. Then we moved in short steps, writing down every important decision as we went. A few days later, iTerminal was signed, installed, and my daily driver.

## A tool that fits

None of these features is groundbreaking on its own. What makes the difference is that every detail matches how I work, and nobody else's.

That's the same reasoning I apply to the business tools I build for clients. Off-the-shelf software makes you [adapt to it](/articles/2026/07/14/why-your-off-the-shelf-software-costs-you-three-times-more-than-you-planned). A tool built for you adapts to you, and you feel it from day one.

Every screenshot in this article comes from the real app, running fictional projects.
