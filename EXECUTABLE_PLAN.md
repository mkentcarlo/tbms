# Plan: Make Everything Executable

This plan ensures the TBMS project can be run reliably in multiple environments with minimal friction: scripts are executable, one-command run options exist, and both Docker and local-PHP workflows are supported.

---

## Goals

1. **All scripts are executable** – Shell scripts run with `./script.sh` without `bash script.sh`.
2. **Single “run” entry point** – One command (or a small set) that starts the app in the right way for the current environment.
3. **Docker-first, local fallback** – Prefer Docker when available; support running with local PHP + MySQL when Docker is not used.
4. **Consistent env setup** – One standard env template and clear instructions so setup works the same for everyone.

---

## Phase 1: Script Executability & Env Alignment

### 1.1 Make scripts executable

Ensure these run with `./script.sh`:

| Script | Purpose | Action |
|--------|---------|--------|
| `docker-start.sh` | Start Docker stack + basic setup | `chmod +x docker-start.sh` |
| `setup-docker.sh` | Full Docker setup (env, deps, key, perms) | `chmod +x setup-docker.sh` |
| `fix-middleware.sh` | Clear caches, regenerate autoload | `chmod +x fix-middleware.sh` |

- Add a one-liner in the repo (e.g. in README or a small “bootstrap” script):  
  `chmod +x docker-start.sh setup-docker.sh fix-middleware.sh`
- Document in README/QUICK_START that these are intended to be run as `./script.sh`.

### 1.2 Unify env template

- **Current:** `docker-start.sh` uses `.env.example`, `setup-docker.sh` uses `.env.docker.example`, repo has `.env.xample`.
- **Target:**
  - Use **one** canonical template: `.env.example`.
  - If you need Docker-specific defaults, add `.env.docker.example` that extends/copies from `.env.example`, and have `setup-docker.sh` use it when present; otherwise have it create `.env` from `.env.example` (or inline defaults).
  - Rename or copy `.env.xample` → `.env.example` so all docs and scripts refer to `.env.example` only.
- Update `docker-start.sh` and `setup-docker.sh` to:
  - Prefer `.env.example` when creating `.env` if no Docker-specific template exists.
  - Print a single, clear “Create .env from .env.example” instruction in docs.

---

## Phase 2: Unified “Run” Entry Points

### 2.1 Add `run.sh` (main run script)

Create **`run.sh`** that:

1. **Detect environment**
   - If `docker info` succeeds → treat as “Docker available”.
   - Else → treat as “local PHP” (use `php artisan serve`).

2. **Docker path**
   - Run `docker-compose up -d` (or `./docker-start.sh` if you want that script to remain the “heavy” setup).
   - Optionally:
     - Wait for MySQL (e.g. script or `until docker-compose exec -T mysql mysqladmin ping`).
     - Ensure `.env` exists (from `.env.example` or `.env.docker.example`).
   - Print: “App: http://localhost:8085” and “Run migrations: docker-compose exec app php artisan migrate”.

3. **Local-PHP path**
   - Ensure `.env` exists (from `.env.example`); if not, print how to create it and exit.
   - Optionally check for `php` and `artisan`.
   - Run `php artisan serve`.
   - Print: “App: http://127.0.0.1:8000” and remind that DB must be reachable (e.g. local MySQL).

4. **Usage**
   - `./run.sh` → start app (Docker or PHP based on detection).
   - `./run.sh --docker` → force Docker (exit with message if Docker not available).
   - `./run.sh --local` → force `php artisan serve` (no Docker).
   - `./run.sh --help` → short usage and env requirements.

5. **Executable**
   - Add `run.sh` to the “make executable” set and document it as the default way to run.

### 2.2 npm scripts for run/build

Add to **`package.json`** under `scripts`:

```json
{
  "scripts": {
    "run": "vite",
    "run:serve": "php artisan serve",
    "run:docker": "./docker-start.sh",
    "run:local": "php artisan serve",
    "start": "npm run dev",
    "dev": "vite",
    "build": "vite build"
  }
}
```

- **`npm run run:docker`** – Run Docker stack via `./docker-start.sh` (when you use Docker).
- **`npm run run:local`** / **`npm run run:serve`** – Start Laravel with `php artisan serve` (assumes PHP and DB are available locally).
- **`npm run start`** / **`npm run dev`** – Build/watch frontend (Vite). Often used in a separate terminal from `php artisan serve` or from inside the container.

Keep existing `dev`/`build`; the new ones are for “run app” vs “run frontend build.”

### 2.3 Optional: Makefile

If you want a single `make` surface:

- **`make run`** – Run `./run.sh`.
- **`make run-docker`** – `./run.sh --docker`.
- **`make run-local`** – `./run.sh --local`.
- **`make setup**** – `./setup-docker.sh` (when Docker is used for setup).
- **`make install**** – `composer install && npm install` (or via Docker: `docker-compose exec app composer install && docker-compose exec app npm install`).
- **`make build**** – `npm run build`.
- **`make migrate**** – `php artisan migrate` or `docker-compose exec app php artisan migrate` (could be two targets: `migrate` for local, `migrate-docker` for in-container).

Implementation can be a small `Makefile` that forwards to the scripts and npm. This is optional; `run.sh` + npm scripts can be enough.

---

## Phase 3: Documentation & Prerequisites

### 3.1 README / “How to run”

Add or consolidate a **“How to run”** section:

1. **Prerequisites**
   - **Docker:** Docker Desktop installed and running.
   - **Local:** PHP 8.2+, Composer, Node 18+, MySQL (or MariaDB) running and reachable.

2. **First-time setup**
   - Clone repo, then either:
     - **Docker:** `./setup-docker.sh` (or `./docker-start.sh` and then manual migrate/build).
     - **Local:** `cp .env.example .env`, `composer install`, `npm install`, `php artisan key:generate`, `php artisan migrate`, `npm run build` (or `npm run dev`).

3. **Daily run**
   - **Preferred:** `./run.sh` (picks Docker or local automatically).
   - **Explicit:** `./run.sh --docker` or `./run.sh --local`.
   - **Frontend dev:** In another terminal, `npm run dev` (or inside container: `docker-compose exec app npm run dev`).

4. **URLs**
   - Docker: http://localhost:8085  
   - Local serve: http://127.0.0.1:8000  

5. **Script reference**
   - `./run.sh` – main run entry point.
   - `./docker-start.sh` – start Docker stack (+ basic bootstrap).
   - `./setup-docker.sh` – full Docker setup.
   - `./fix-middleware.sh` – clear caches / fix middleware issues (run from project root; use `php`/`composer` from host or document Docker: `docker-compose exec app bash` then run there).

### 3.2 One-command bootstrap (optional)

Add **`bootstrap.sh`** (or extend `run.sh` with `--setup`):

- Ensures scripts are executable:  
  `chmod +x run.sh docker-start.sh setup-docker.sh fix-middleware.sh`
- If Docker: run `./setup-docker.sh` (or `./docker-start.sh`).
- If local: create `.env` from `.env.example` if missing, run `composer install`, `npm install`, `php artisan key:generate`, and print “Run `php artisan migrate` and `./run.sh --local`”.

Document: “First time? Run `./bootstrap.sh` (or `chmod +x *.sh && ./setup-docker.sh` for Docker).”

---

## Phase 4: Implementation Checklist

Track implementation with something like:

- [ ] **1.1** `chmod +x` for `docker-start.sh`, `setup-docker.sh`, `fix-middleware.sh` (and document).
- [ ] **1.2** Unify env: `.env.example` as canonical; fix references in scripts; handle `.env.xample` / `.env.docker.example` as needed.
- [ ] **2.1** Add `run.sh` with detection, `--docker`, `--local`, `--help`.
- [ ] **2.2** Add npm scripts: `run:docker`, `run:local` / `run:serve`, and keep `dev`/`build`.
- [ ] **2.3** (Optional) Add `Makefile` with `run`, `run-docker`, `run-local`, `setup`, `install`, `build`, `migrate`.
- [ ] **3.1** Update README/QUICK_START with prerequisites, first-time setup, daily run, URLs, script list.
- [ ] **3.2** (Optional) Add `bootstrap.sh` and document first-time flow.

---

## Summary

| Deliverable | Purpose |
|-------------|---------|
| Executable shell scripts | Run with `./script.sh` from any shell |
| **`run.sh`** | One entry point: auto-detect Docker vs local, `--docker` / `--local` / `--help` |
| Unified `.env.example` | Single template for all setups |
| npm `run:*` scripts | `npm run run:docker` / `npm run run:local` for IDEs and scripts |
| Optional Makefile | `make run` / `make run-local` etc. for those who prefer `make` |
| Docs | Prerequisites, first-time setup, daily run, and script reference in one place |

After this, “run it” means: **`./run.sh`** (or `./run.sh --docker` / `./run.sh --local` when you want to force one environment), with scripts and env aligned so everything is executable and predictable.
