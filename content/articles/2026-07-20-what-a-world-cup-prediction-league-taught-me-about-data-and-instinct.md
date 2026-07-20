---
title: "The one rule that won me a World Cup prediction league"
description: I finished first out of 100 in a World Cup prediction league. The data set the baseline; the points came from knowing exactly when I was allowed to override it.
---

I finished first out of 100 in the Bet Your Goal Open League with 114 points. I did not expect that when I signed up, and the way it happened turned out to be more interesting than the result itself.

The whole thing comes down to one rule I only articulated somewhere around the quarterfinals: **use the data to set the baseline, and override it only when you have a specific reason rather than a vague feeling.** Everything below is how I arrived at that, and the two times it cost me for ignoring it.

![The Bet Your Goal dashboard showing 114 points and first place out of 100 in the Open League](/img/articles/2026-07-20-what-a-world-cup-prediction-league-taught-me-about-data-and-instinct/results.jpg "114 points, first out of 100")

## The setup

[Bet Your Goal](https://betyourgoal.com/) is a prediction league built by [Christophe Rumpel](https://x.com/christophrumpel), and it does exactly what it needs to do: you pick a scoreline for each match before kickoff, picks lock when the game starts, and points accumulate over the tournament. Getting the winner right earns something. Getting the exact score right earns a lot more. With 48 teams and a new knockout round to get through, the 2026 edition offered plenty of chances to be wrong.

![The Bet Your Goal homepage: predict every match, win bragging rights - 104 matches, 48 teams](/img/articles/2026-07-20-what-a-world-cup-prediction-league-taught-me-about-data-and-instinct/betyourgoal.jpg "104 matches, 48 teams")

I approached it the way I approach most problems: gather the data first, decide second. For every match I looked at form, goals scored and conceded, defensive records, fixture congestion, injuries, and where available, the bookmakers' odds. I ran a lot of this through Claude, partly to save time and partly because I was curious how far a structured analysis could get me.

## Setting the baseline: what the data does well

For picking winners, the data was excellent. Form tables, expected goals, and the betting markets agree far more often than they disagree, and when they agree they are usually right. Spain's run was legible from the group stage: they conceded a single goal in seven matches and were never behind in the entire tournament. France scoring sixteen goals on the way to the semifinal was not a fluke. Morocco going unbeaten for the whole competition was visible in their numbers long before anyone called them a story.

The data was also good at spotting mismatches that looked closer than they were. Norway's attack was terrifying and their defence leaked constantly. Colombia created chances relentlessly and converted almost none of them. Once you see a pattern like that stated numerically, it is hard to unsee, and it holds up more often than not.

The corollary is that the market is usually right, and the value is in knowing when it is not. The books had Mexico priced generously at home given they had not conceded a goal all tournament, and they had England slightly ahead of Norway despite Haaland being the most in-form striker on the pitch. Those are the spots worth taking a position on.

## Where the baseline runs out

Exact scores are close to a lottery. Even the best public models hit somewhere around fifteen percent on a precise scoreline, and low-scoring football is structurally resistant to prediction. A single deflection or a VAR review changes 1-0 into 1-1. I made peace with this early and started treating the exact score as a best guess anchored to the total-goals line rather than a claim about the future.

The bigger gap was upsets. This tournament produced three that no model saw coming. Paraguay eliminated Germany. Morocco knocked out the Netherlands. Norway beat Brazil, extending a genuinely absurd historical record where Brazil have still never beaten them.

The model captures roughly ninety percent of what matters. The last ten percent is context that does not show up in a spreadsheet: fatigue that has not translated into results yet, a squad that has quietly stopped believing, a striker in the kind of form where he decides matches on his own.

## The override that worked

Every rational input said Brazil. Better squad, better form, better defence, and a bookmaker line that reflected all of it. But something about the fixture felt wrong to me, and I picked Norway to win by a goal. It came in exactly as I called it. Haaland scored twice that night against a Brazil side that was not quite themselves.

I want to be careful here, because this is the story I would most like to tell about myself and it is the least reliable evidence in the article. One correct call against the market is what you would expect from noise. The reasons I gave afterwards — Haaland's form, Brazil looking flat — are reasons I assembled *after* seeing the result. At the time, what I actually had was a feeling.

## The overrides that did not

Which is why the informative cases are the ones that went the other way.

> **The one that cost me was Mexico against England in the round of 16. Every number pointed to England, and the books had them ahead, but Mexico had not conceded a single goal in the entire tournament and had not lost a competitive home match since 2013. I picked 1-0 Mexico. England won 3-2, Mexico shipped two goals in a night after five clean sheets, and the fortress that had held for five matches turned out to be a story I had told myself rather than an argument the data supported.**

That is the difference the rule is built on. "Norway have a defensive record that gets exposed against elite attacks" is a reason to pick against them. "I have a hunch" is not. Picking against a favourite because you want a story is how you lose points, and I lost them exactly that way often enough to notice the pattern.

## The one edge worth more than any statistic

Wait for the team sheets.

Lineups are confirmed one hour before kickoff, and for matches where rotation is likely, that hour is worth more than every number I looked at beforehand. A rested first eleven and a rotated one are different teams wearing the same shirt, and no pre-match model prices that in until it is announced. If the league lets you edit picks until kickoff, use that window. This is the single change I would make to my process.

## The Swiss exception

I have to mention the run. Switzerland went unbeaten through the group, beat Algeria for our first knockout win in the World Cup in eighty-eight years, went through against Colombia on penalties, and then took Argentina to extra time in the quarterfinal while down to ten men. A quarterfinal equals our best result since 1954.

I was not objective about a single one of those matches. But that is its own kind of discipline: knowing which fixtures you cannot assess honestly is useful information about your own model. I picked Switzerland with my heart every time and I knew, while doing it, that I was doing it.

## The part that had nothing to do with analysis

After the final, [Povilas Korop](https://x.com/PovilasKorop) pointed out something I had not noticed. Of the 100 people in the league, only 13 were still placing bets on the final match. Eighty-seven percent had stopped somewhere along the way.

That reframes my result more than any of the analysis above. Some of the gap between first place and the rest of the table is not better prediction. It is simply having submitted a pick for every match, including the ones I did not care about, at three in the morning Swiss time, after a week where I had gotten several in a row wrong. Consistency is not a strategy, but it is a prerequisite for one, and it turns out to be rarer than being right.

## What I would keep

The process worked. First out of 100 in a tournament that eliminated Germany, the Netherlands, and Brazil before the semifinals is a good outcome, and a decent chunk of it was luck — I would not claim otherwise. But the framework held up: baseline from the data, override only with an argument, and never confuse the two. I will use it again for Euro 2028.
