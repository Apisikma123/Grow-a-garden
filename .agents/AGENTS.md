# AGENTS.md — Guidelines & UI Rules for Grow-a-Garden

## 1. UI & Flexbox Sizing Rules (Never Break Text Layout)
- **Full Width on Flex Column Containers**: When creating or editing centered empty state blocks or card containers using `flex flex-col items-center`, ALWAYS set `w-full` (`width: 100%`) on the parent container AND text wrappers.
- **Prevent Vertical 1-Word Text Collapse**: Text wrappers inside `align-items: center` flex columns MUST include `w-full self-stretch max-w-md mx-auto` and explicit `white-space: normal; word-break: normal;` to prevent CSS flexbox cross-axis `min-content` collapse (which causes text to wrap 1 word per line).
- **Responsive Layout Integrity**: Ensure text elements have `min-w-0`, `truncate`, or `break-words` as needed, and never wrap into narrow single-word vertical columns on any screen size.

## 2. Color Palette & Design System Rules (Verdant Growth Theme)
- **Adhere to `resources/css/app.css` & `DESIGN.md` Tokens**:
  - **Primary Green**: `#006c49` (`bg-primary`, `text-primary`)
  - **Secondary Terracotta Earth**: `#944a23` (`bg-secondary`, `text-secondary`)
  - **Tertiary Sage Green**: `#1b6b51` / `#78a994`
  - **Error & Locked Containers**: `#ba1a1a` & `#ffdad6` (`bg-error-container`, `text-on-error-container`)
- **NO Generic Orange**: Do NOT use arbitrary orange or amber Tailwind utility classes (`amber-500`, `#fb923c`, `#f97316`) for locked states, alerts, or charts. Use design system tokens.

## 3. Subscription Tier & Plan Limit Rules
- **Free Plan (`Bibit (Gratis)`)**: Max 1 Kebun, Max 10 Tanaman.
- **Pro Plan (`Subur (Pro)`)**: Max 10 Kebun, Max 100 Tanaman.
- **Premium Plan (`Panen Raya (Premium)`)**: Max 100 Kebun (Tak Terbatas), Tanaman Tak Terbatas.
- **Locked Visuals**: Display strikethrough titles (`line-through opacity-70`), lock icon badges, and error-container warning banners when user exceeds plan limits, triggering the pricing upgrade modal on click.
