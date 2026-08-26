# Global Project Rules — Grow-a-Garden

## 1. Browser Automation & Review Policy (Strictly Disabled)
- **NO Automated Browser Review / Subagent**: NEVER launch the `browser_subagent` or open, control, or record the browser on the user's machine.
- **Pure Code & CLI Workflow**: Perform all task execution, syntax checks, migrations, and build validations strictly through local code edits, static analysis, and CLI commands (`php artisan`, `npm run build`, etc.) without opening the browser.

## 2. Flexbox Sizing & Layout Rules
- Centered flex columns must have `w-full` on parent & text wrappers to prevent vertical 1-word collapse.

## 3. Design System (Verdant Growth Theme)
- Primary Green: `#006c49` | Secondary Terracotta: `#944a23` | Error: `#ba1a1a`
- Do NOT use generic arbitrary orange/amber Tailwind utilities.
