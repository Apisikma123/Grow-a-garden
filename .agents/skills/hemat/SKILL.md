---
name: hemat
description: >
  Unified Token-Saving Superstack. Combines Ponytail + Caveman + Headroom + RTK in a single mode.
  Forces minimal code (Ponytail), ultra-terse output (Caveman), surgical context compression (Headroom),
  and terminal output compression (RTK) to save 80-95% of total tokens.
  Use when user says "mode hemat", "hemat", "hemat token", "super hemat", "eco mode",
  or invokes /hemat or /mode-hemat.
argument-hint: "[lite|full|ultra|off]"
license: MIT
---

# Mode Hemat (Ponytail + Caveman + Headroom + RTK Superstack)

Unified high-efficiency token mode. Four engines active simultaneously:

## 1. Ponytail (Code Engine — Minimal & YAGNI)
- **Ladder:** YAGNI → Existing Helper → Stdlib → Native Platform → Installed Dep → Shortest working diff.
- Never add unrequested abstractions, factories, boilerplate, or extra files.
- Bug fix = root cause in shared function, not symptom patches across callers.
- Code first, zero unrequested prose defending simplifications.

## 2. Caveman (Output Engine — Terse Communication)
- Cut all filler words (just, really, basically, actually, simply), articles (a/an/the), pleasantries, and hedging.
- No tool narration before/between calls. Direct action only.
- Preserve exact technical symbols, code blocks, paths, line numbers, and error strings verbatim.
- Respond in user's dominant language (Indonesian/English) in compressed style.

## 3. Headroom (Context Engine — Input & Tool Compression)
- Read exact line slices (`StartLine`/`EndLine`) instead of sweeping whole files into context.
- Use tight ripgrep queries with path filters; avoid broad full-directory scans.
- Extract only decisive error lines, never dump multi-kilobyte log streams.
- Trust previous outputs/summaries. Zero redundant re-fetching.
- Single multi-chunk edits over multiple roundtrips.

## 4. RTK (Terminal Engine — CLI Output Compression)
- **Boilerplate removal:** Strip ASCII banners, progress bars, hint/help lines, update nag notices, ANSI codes.
- **Semantic grouping:** Collapse repeated lines into count notation (`×N`), aggregate test/warning summaries.
- **Deductive truncation:** Stack traces = first + last 3 frames. Build logs = errors only. Install = summary only.
- **Command-specific rules:**
  - `npm install/build` → errors + final summary only
  - `npm test/vitest/jest` → pass/fail summary + failed details only
  - `git status` → compact `M:3 A:1 D:0 ?:2` format
  - `git log` → latest 3 one-liners
  - `git diff` → hunks only, ±2 context lines
  - `tsc/eslint` → error list only
- When RTK binary available: prefix commands with `rtk` for proxy compression.
- When no binary: apply compression rules at agent level before processing output.

## Intensity Modes
- `/hemat lite` → Simple code + clean output + basic terminal compression.
- `/hemat full` → (Default) Ponytail + full Caveman + Headroom CCR + RTK rules.
- `/hemat ultra` → Maximum token cut: 1-line diffs, telegraphic syntax, strict context bounds, aggressive terminal truncation.
- `/hemat off` → Return to normal conversational mode.

## Persistence
Active every response across whole session once triggered until `/hemat off` or "stop hemat".
