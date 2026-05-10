---
title: "I Turned My 404 Page Into a Flappy Bird Clone"
description: Look, nobody likes hitting a 404. It's the digital equivalent of walking into a glass door.
---

Look, nobody likes hitting a 404. It's the digital equivalent of walking into a glass door. You were going somewhere, you had momentum, and now you're staring at a page that, in the best case, says "Oops!" with a sad cloud illustration the developer found on a free icon site at 2 AM.

I've always thought 404 pages were a missed opportunity. They're one of the only pages on a website where the user has zero expectations. They expected something else and got nothing. So whatever you put there is, by definition, a bonus. And yet most of us slap a "Page not found" header, a link back to the homepage, and call it a day.

So when I rebuilt the site for my company, [Bee Interactive](https://bee-interactive.ch/), I decided the 404 page was going to be a full, playable Flappy Bird clone. No iframe, no third-party widget, no "click here to play." You hit the 404, the bird is already there bobbing in the middle of the screen, and you click it to start. That's it. That's the page.

Want to see it? [Here's a link that goes nowhere](https://bee-interactive.ch/this-page-does-not-exist) — try not to die on the first pipe.

![The 404 page in its idle state — the bird bobs in the middle of the screen, ready to be clicked](/img/articles/2026-05-10-i-turned-my-404-page-into-a-game/intro.png "The 404 page on bee-interactive.ch — no instructions, just a bird waiting to be clicked")

This is the story of how I built it, why I made the weird choices I made, and a few of the gotchas I ran into along the way.

## The setup: canvas-only, no framework

The first decision was the simplest: I wasn't going to pull in a game engine for this. No Phaser, no PixiJS, no nothing. The whole point of a 404 page is that it loads instantly. If my "page not found" page has a 400KB JavaScript bundle attached to it, I've kind of failed at the assignment.

So it's just a plain HTML5 canvas, sized to fill the viewport, with vanilla JavaScript on top.

```html
<canvas id="flappyBirdCanvas"></canvas>
```

```css
#flappyBirdCanvas {
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 0;
}
```

The canvas sits at `z-index: 0`, and the actual "404 — Page not found" text floats above it at `z-index: 10` with `pointer-events: none` so the user can click straight through to the bird underneath. That's the trick that makes the whole thing feel seamless. The text is the page, the game is the page, they're the same thing, and you don't have to "enter" the game. You just click.

## Game state: keep it stupid

I've watched people build canvas games and immediately reach for some elaborate state machine library. For a Flappy Bird clone you have, what, three states? Idle, playing, game over. That's a constant and an integer.

```js
const gameStates = {
    IDLE: 0,
    PLAYING: 1,
    GAME_OVER: 2
};

let currentState = gameStates.IDLE;
let frames = 0;
let score = 0;
let highScore = parseInt(localStorage.getItem('flappyBirdHighScore')) || 0;
```

That's the entire state of the game. Current state, a frame counter, a score, and a high score that lives in localStorage so people can come back tomorrow and try to beat themselves. (And before you ask: yes, I know "people will come back tomorrow to play my 404 page" is an aspirational claim. Let me have this.)

The `frames` counter is doing a lot of work here. I use it for animation timing, for spawning pipes at intervals, for the bird's idle bobbing — basically anywhere I'd otherwise have to set up a `setInterval` or track elapsed time, I just check `frames % N === 0`. It's not the most precise approach if your framerate dips, but for a 404 page Flappy Bird, who cares.

## The bird

The bird is the only thing on the page with personality, so it gets the most attention. I didn't draw the sprite either — it's the Flappy Bird bird, three frames of animation cycled through every 10 frames, a velocity, a rotation, and a sine-wave bobbing motion when it's just sitting in idle.

```js
const bird = {
    x: canvasWidth * 0.2,
    y: canvasHeight / 2,
    width: 69,
    height: 48,
    frame: 0,
    velocity: 0,
    gravity: 0.18,
    thrust: 5.5,
    rotation: 0,
    idleAmplitude: 20,
    idleSpeed: 0.05,

    update() {
        if (currentState === gameStates.IDLE) {
            this.y = canvasHeight / 2 + Math.sin(frames * this.idleSpeed) * this.idleAmplitude;
            this.frame++;
            this.rotation = 0;
        } else if (currentState === gameStates.PLAYING) {
            this.frame++;
            this.velocity += this.gravity;
            this.y += this.velocity;

            if (this.velocity >= this.thrust) {
                this.rotation = Math.PI / 2;
            } else {
                this.rotation = -Math.PI / 4;
            }

            if (this.y + this.height / 2 >= canvasHeight || this.y - this.height / 2 <= 0) {
                endGame();
            }
        }
    }
};
```

Two things worth flagging here.

First: the gravity is `0.18`. The thrust is `5.5`. Those numbers look totally arbitrary, and that's because they are. I tweaked them for about an hour straight, playing my own 404 page over and over until it felt right. There is no formula. There is no science. There is just sitting in front of your laptop muttering "no, that's too floaty" until your partner asks if you're okay. The original Flappy Bird was reportedly tuned the same way and I refuse to be ashamed of this.

Second: the rotation. When the bird is climbing it tilts up at `-π/4` (45 degrees up), and once its velocity passes the thrust threshold it slams to `π/2` (straight down). That little bit of game feel — the bird nose-diving when you stop flapping — is what sells it. It's three lines of code and it's the difference between "thing on a screen" and "Flappy Bird."

## Idle bobbing without a state machine

This is the one bit of cleverness I'm proud of. In the IDLE state, the bird needs to look alive. It needs to bob up and down so the user understands it's interactive, not just a static image they're supposed to ignore.

```js
this.y = canvasHeight / 2 + Math.sin(frames * this.idleSpeed) * this.idleAmplitude;
```

Because `frames` is incremented every animation frame, multiplying it by a small constant gives you a slow oscillation. `Math.sin` returns a value between `-1` and `1`, and multiplying that by `idleAmplitude` (20px) gives you a 40px range of motion centered on the middle of the screen. No tweening library, no easing function, no animation timeline. Just a sine wave.

The same trick works for any "breathing" UI element you ever need to build. If you want a button to pulse, a logo to float, a "scroll down" arrow to bounce — `Math.sin(frames * speed) * amplitude` will get you there. Free of charge.

## The hitbox lie

Here's a confession. The bird's hitbox in IDLE state is a circle, not a rectangle:

```js
isPointInside(mouseX, mouseY) {
    const hitboxRadius = 50;
    const distance = Math.sqrt(Math.pow(mouseX - this.x, 2) + Math.pow(mouseY - this.y, 2));
    return distance <= hitboxRadius;
}
```

The bird sprite is 69px wide and 48px tall. The hitbox is a 50px-radius circle around its center, which is bigger than the visible bird in every direction. This is on purpose. People who land on a 404 page are not in a precise mood. They're frustrated, they're scanning the page, and they need to be able to lazily click somewhere near the bird and have something happen.

If they click and nothing happens, they bounce. If they click and the bird flaps, they're in. The hitbox is generous because the cost of a missed click is "user leaves my site forever" and the cost of a too-easy click is "user has fun."

The collision hitbox during gameplay is the actual rectangle, though. That part needs to be honest.

## Pipes

Pipes are stored as a flat array of objects with an x position, a vertical gap center, and a `scored` flag. Every 98 frames (another magic number tuned by feel), a new pipe spawns at the right edge of the canvas.

```js
const pipes = {
    list: [],
    gap: 340,
    width: 90,
    spawnInterval: 98,
    speed: 3,

    add() {
        const minGapY = this.gap + 150;
        const maxGapY = canvasHeight - this.gap - 150;
        const gapCenterY = Math.random() * (maxGapY - minGapY) + minGapY;

        this.list.push({
            x: canvasWidth,
            gapCenterY: gapCenterY,
            scored: false
        });
    }
};
```

The `gap` is 340 pixels, which is way more forgiving than the original Flappy Bird. Again — context matters. The original was a mobile game played on purpose by people who had downloaded it. This is a 404 page. If I make it brutally hard, people play it once and leave. If I make it generously easy, they get a few points, feel good about themselves, and remember the site fondly.

Each pipe also has a `scored` boolean. The scoring logic checks every frame whether a pipe has fully passed the bird, and if so, marks it scored and increments the counter. Without that flag, you'd score every frame the bird is past the pipe, which would give you about 30 points per pipe and would not actually be a game.

```js
if (!pipe.scored && pipe.x + this.width < bird.x - bird.width / 2) {
    pipe.scored = true;
    score++;
    sounds.score.play();
}
```

![The bird mid-flight between two green pipes, with the score counter visible at the top](/img/articles/2026-05-10-i-turned-my-404-page-into-a-game/gameplay.png "Mid-game — pipes scrolling left, bird threading the gap, score ticking up")

## Sound design (lol)

I didn't make any of these sounds. They're Flappy Bird SFX I dropped into a folder. Five files: start, flap, score, hit, die. They're preloaded as `Audio` objects so the first flap doesn't have any latency.

```js
const sounds = {
    start: new Audio('/sfx/flappy-bird/start.wav'),
    flap: new Audio('/sfx/flappy-bird/flap.wav'),
    score: new Audio('/sfx/flappy-bird/score.wav'),
    hit: new Audio('/sfx/flappy-bird/hit.wav'),
    die: new Audio('/sfx/flappy-bird/die.wav')
};
```

The `hit` and `die` sounds are deliberately staggered with a 100ms `setTimeout` so they don't play on top of each other:

```js
sounds.hit.play();
setTimeout(() => {
    sounds.die.play();
}, 100);
```

That tiny delay is what makes the death feel like an event instead of a single noise. Hit, then a beat, then the descending "you died" tone. It's the same trick movies use when something explodes and there's a half-second of silence before the score kicks in.

## Dark mode, because we're adults

The site has a dark mode toggle, and the canvas respects it. The pipes are drawn in different greens depending on whether the page is in dark mode, and the background, score color, and stroke colors all swap accordingly.

```js
const isDark = document.documentElement.classList.contains('dark');
const pipeColor = isDark ? '#10b981' : '#059669';
const pipeDarkColor = isDark ? '#047857' : '#047857';
const pipeLightColor = isDark ? '#34d399' : '#10b981';
```

I check the class on `documentElement` every frame. Is that wasteful? Slightly. Could I subscribe to a class change event instead? Yes. Does it matter at 60fps for a couple of string comparisons? No. Premature optimization, etc. The frame budget for a 404 page Flappy Bird is, generously, the entire frame.

## The game loop

The whole thing runs on a `requestAnimationFrame` loop that's about as boring as it gets:

```js
function gameLoop() {
    frames++;
    update();
    draw();
    requestAnimationFrame(gameLoop);
}
```

Increment frames. Update everything. Draw everything. Schedule yourself again. That's it. There's no fixed timestep, no interpolation, no separate physics tick rate. If your monitor is 144Hz the bird falls slightly faster — which, for a 404 page, is acceptable.

If I were building a real game I'd care about decoupling update from render, accumulating delta time, all that. For this, no. The whole point of building things in a "wrong" way for the right reason is that you get to ship a thing instead of refactoring an architecture.

## The Game Over screen

When you crash, an overlay slides up with your score, your high score from localStorage, and a chunky retro "Continue" button. I went hard on the styling here — thick green borders, hard inset shadows, monospace font, faux-pixel decorations on the corners. The whole 404 page is in modern Tailwind everywhere else, and then you die and suddenly it looks like a CRT from 1987.

That contrast is the joke. The page itself is professional, my site is professional, but the moment you fail at Flappy Bird I drop the act and dump you into a fake arcade cabinet. People notice things like that. They share things like that.

## What I'd do differently

If I rebuilt this from scratch, two things.

One: a delta-time-based update loop, so framerate doesn't affect difficulty. It's not a real problem here, but it would be free to do correctly and I should have done it.

Two: touch support. Right now the click handler only listens for `click` events. On mobile, taps still register as clicks, but I should also handle `touchstart` directly so the response is instant rather than waiting for the 300ms tap-to-click delay (which most modern browsers have removed but not all). For a game where timing matters, even a few milliseconds is felt.

I'd also probably parallax-scroll the background, but at that point I'm just making a real game and I should put it on a real page.

## The real point

The whole reason I built this isn't that 404 pages need to be games. It's that any page on your site can do more than its job description. A 404 page is supposed to tell people they're lost. It is not supposed to be entertaining. It's not supposed to be memorable. It's not supposed to make people deliberately type `/asdf` into the URL bar to play it again.

But it can be all of those things. And the cost is one canvas, a couple hundred lines of vanilla JavaScript, and an evening of tuning numbers until the bird falls correctly.

The best parts of the web are the parts where someone clearly went further than they had to. Build the thing. Ship the bird.

## One last thing

To be very clear: this is an homage on a 404 page, not a product. Flappy Bird is Dong Nguyen's — the sprite, the sounds, the pipe-and-gap formula, every magic number I claimed I "tuned by feel" was actually tuned years ago by him. There are no ads on this page, no analytics on the game, no money changing hands, and zero ambition to recreate the original as a real game. I just thought a 404 deserved a little more than an apology.

And honestly, the original Flappy Bird is better than mine in every way that matters. It was shipped, sweated over, then pulled by its author because it had become too much of a phenomenon. My version is a footnote on a page that, by definition, nobody was looking for. If you can still find a way to play the real one, do that instead.
