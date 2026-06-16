# Golf Stat Tracker — Rebuild Roadmap

## Architecture
- **Backend:** Node.js + Express, MySQL (existing schema), JWT auth
- **Web:** React (Vite)
- **Mobile:** Expo (React Native)
- **Course data:** golfcourseapi.com (free tier, 50 req/day), cached in MySQL

---

## Done So Far
- Express server running
- `.env` set up (Golf API key, DB credentials)
- MySQL connection via `mysql2` pool (`db.js`)
- Golf Course API search working (`/search?q=`)
- `GET /api/courses/:id` route, moved into `routes/courses.js` using Express Router
- Git repo confirmed and committed

---

## Phase 1 — Backend (Node/Express + MySQL)

### Course Routes
- [ ] `GET /api/courses/search?q=` — search **your DB** by course name (LIKE query). This is the "cheap, instant" path used while typing.
- [ ] `GET /api/courses/search-online?q=` — explicit, user-triggered call to Golf Course API. Only fires on a button click, not on keystrokes (rate limit protection).
- [ ] Write `mapApiCourseToSchema()` — transforms Golf Course API response format into your `Courses` table schema (handles hole-by-hole par/yardage, totals, front9/back9, par3_count).
- [ ] `POST /api/courses` — saves a course to your DB. When triggered from an online search result, save **all tee sets** for that course in one go (not just the one the user picked), to avoid future API calls for that course.
- [ ] Decide and implement the "course not found anywhere" fallback — manual entry form (mirrors current `logStats.php` new-course section), saved to DB like option B discussed.
- [ ] (Low priority, future) Staleness check: if a course's `created_at`/`updated_at` is >1 year old, optionally re-fetch from API and compare/update.

### Auth Routes
- [ ] `POST /api/auth/register` — hash password (bcrypt), insert into `Users` table
- [ ] `POST /api/auth/login` — verify password, issue JWT
- [ ] JWT middleware — verify token on protected routes, attach `user_id` to `req`
- [ ] Decide token storage strategy for web (httpOnly cookie vs localStorage) and mobile (SecureStore in Expo)
- [ ] (Optional) refresh token flow if you want longer-lived sessions without re-login

### Rounds & Hole Stats Routes
- [ ] `POST /api/rounds` — create a new round + insert 18 `HoleStats` rows (mirrors `stats.php`)
- [ ] `GET /api/rounds` — list current user's rounds (for profile/recent activity)
- [ ] `GET /api/rounds/:id` — full round detail including hole-by-hole stats (mirrors data pulled in `edit_round.php`)
- [ ] `PUT /api/rounds/:id` — update round + hole stats (mirrors `update_round.php`)
- [ ] `DELETE /api/rounds/:id` — delete a round (not in old PHP version, but standard REST — consider adding)

### Stats/Profile Route
- [ ] `GET /api/profile/stats` — calculate and return averages (avg score, fairways %, GIR %, putts/hole) — mirrors the calculation logic currently in `profile.php`

### General Backend Cleanup
- [ ] Move all routes into `routes/` folder, each as an Express Router (courses.js, auth.js, rounds.js, profile.js)
- [ ] Centralized error handling middleware
- [ ] Input validation (consider a lightweight library like `express-validator`) — especially important since old PHP code trusted a lot of raw `$_POST` data
- [ ] Confirm `.env` is gitignored and was never committed with real credentials

---

## Phase 2 — Web Frontend (React + Vite)

- [ ] Scaffold Vite + React project
- [ ] Set up routing (React Router): Home, Login, Register, Log Stats, Profile, Edit Round
- [ ] Auth context/state — store JWT, attach to API requests, handle login/logout
- [ ] Login/Register pages
- [ ] **Log Stats page**
  - [ ] Course search input (DB search first, debounced)
  - [ ] "Search online" button → Golf Course API results
  - [ ] Tee color dropdown (populated once course selected)
  - [ ] Auto-populate par/yardage per hole on tee selection
  - [ ] Manual course entry fallback
  - [ ] 18-hole scorecard input (score, fairways, GIR, putts) with live totals (port `calculateTotals()` logic)
- [ ] **Profile page**
  - [ ] Stats overview (avg score, fairways %, GIR %, putts/hole)
  - [ ] Recent rounds table with edit links
- [ ] **Edit Round page** — same scorecard component as Log Stats, pre-populated with existing data
- [ ] Shared scorecard component (reuse between Log Stats and Edit Round — your PHP had near-duplicate code here, React fixes that)
- [ ] Styling — port/refresh `styles.css`, consider component-based styling approach (CSS modules, Tailwind, etc.)

---

## Phase 3 — Mobile (Expo / React Native)

- [ ] Scaffold Expo project (Expo Router recommended)
- [ ] Set up navigation (tabs: Home/Log/Profile, or similar)
- [ ] Auth flow — JWT stored via Expo SecureStore
- [ ] Reuse API call logic from web (shared functions/hooks where possible)
- [ ] Mobile-adapted scorecard input — 18-hole entry is the trickiest UI to get right on small screens (consider hole-by-hole flow instead of one big table)
- [ ] Course search + tee selection (same backend, mobile UI)
- [ ] Profile/stats screen
- [ ] Test on both iOS and Android (Expo Go during dev, EAS Build for real builds)

---

## Phase 4 — Deployment

- [ ] Deploy backend (Render, Railway, or VPS) — move off AMPPS for production
- [ ] Deploy MySQL (managed DB or same VPS) — ensure credentials are env-based, not hardcoded
- [ ] Deploy web frontend (Vercel, Netlify, or similar)
- [ ] App Store submission (Apple Developer account, $99/year)
- [ ] Google Play submission ($25 one-time)
- [ ] Domain name for web app (optional but adds polish to portfolio link)

---

## Possible Future Features (Post-MVP)

- [ ] Course data staleness check (discussed above — re-verify against API after 1 year)
- [ ] Round history filtering/sorting (by date, course, score)
- [ ] Stats trends over time (charts — score trend, GIR trend, etc.)
- [ ] Handicap calculation
- [ ] Shot-by-shot tracking (beyond hole-level stats) — bigger feature, closer to Arccos/18 Birdies territory
- [ ] Course favorites / "my home courses"
- [ ] Social features — share rounds, compare with friends
- [ ] Weather data integration for the round date/location
- [ ] Export round data (CSV/PDF)
- [ ] Dark mode
- [ ] Push notifications (mobile) — e.g. reminders to log a round
- [ ] Apple Watch / wearable integration (long-term stretch goal, mirrors GHIN app features)

---

## Resume/Portfolio Talking Points (keep these in mind while building)
- RESTful API design (Express + MySQL)
- JWT authentication across web and mobile
- Third-party API integration with caching strategy (rate-limit-aware design)
- Shared codebase/logic between web (React) and mobile (React Native/Expo)
- Migration project: legacy PHP → modern Node.js stack (good "before/after" story)
- Data modeling decisions (course/tee schema, staleness handling)
