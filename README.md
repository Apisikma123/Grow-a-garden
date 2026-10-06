# Grow a Garden

Grow a Garden is a comprehensive web-based platform dedicated to smart urban farming and garden management. The application is specifically designed to help hobbyists, urban farmers, schools, and agricultural communities plan, monitor, and care for their crops through automated growth calendars, intelligent care schedules, weather-adaptive rule engines, and gamified achievement systems. It provides a centralized administrative portal for managing plant catalogs and care templates, alongside an intuitive user dashboard for tracking multi-garden plant life cycles from seedling to harvest.

## Motivation and Architecture

Traditional home gardening and urban farming workflows often suffer from inconsistent care routines, missed watering or fertilizing schedules, and a lack of adaptability to changing local weather conditions. Grow a Garden was developed to modernize and automate this process by providing a proactive plant lifecycle management system that eliminates manual guesswork and prevents crop failure.

From an architectural standpoint, the system relies on Laravel 13 and PHP 8.3 paired with Tailwind CSS v4, Blade templating, and Alpine.js to deliver a lightweight, reactive user interface without the overhead of heavy single-page application frameworks. It leverages Laravel's service container, Eloquent ORM, and scheduled console commands to calculate plant growth milestones, auto-generate daily care tasks, and execute weather autopilot adjustments. Real-time communication and notifications are powered by Laravel Reverb and Laravel Echo WebSockets, ensuring instantaneous updates across client sessions.

Google OAuth 2.0 is integrated via Laravel Socialite alongside an OTP verification system to guarantee secure, frictionless user onboarding and identity management.

## Tech Stack

### Backend
- **PHP 8.3**
- **Laravel 13.x**
- **MySQL / SQLite**
- **Laravel Reverb** (WebSocket server)
- **Laravel Echo & Pusher-JS** (Client-side event broadcasting)

### Frontend
- **Blade** (Templating engine)
- **Tailwind CSS v4** (Utility-first styling with Verdant Growth design system)
- **Alpine.js** (Lightweight reactive DOM manipulation)
- **Chart.js** (Interactive garden analytics & weekly care visualization)

### Libraries and Tools
- **Laravel Socialite** (OAuth 2.0 Single Sign-On)
- **Weather API Engine** (Real-time weather data & autopilot care rules)
- **Vite 7** (Modern frontend asset bundling & HMR)
- **Concurrently** (Multi-process development orchestration)

## Features

- **Streamlined Authentication & Google OAuth**: Seamless registration, OTP email verification, instant password reset, and Google OAuth 2.0 Single Sign-On.
- **Multi-Garden & Plant Tracking**: Manage multiple gardens and plant instances with details on variety, plant count, planting date, growth stages, and harvest logs.
- **Automated Growth Calendar**: Dynamic milestone generation tracking plant progression through Germination, Seedling, Vegetative, Flowering, Fruiting, and Harvest phases.
- **Intelligent Care Task Engine**: Auto-generates scheduled tasks for watering, fertilizing, pest control, and pruning with priority levels and status tracking (Pending, Completed, Skipped).
- **Weather Autopilot & Dynamic Rules**: Automatically adapts watering frequencies and issues proactive alerts based on live weather data (rain reduction, drought increase, heat warnings).
- **Gamified Achievements & Badges**: Milestone-based missions and unlockable badges that encourage consistent plant care habits.
- **Tiered Subscription Plans**: Role-based feature and capacity limits (Free *"Bibit"*, Pro *"Subur"*, and Premium *"Panen Raya"*) with locked state banners and upgrade modals.
- **Admin Management Portal**: Comprehensive control panel to manage user accounts, plant catalogs, growth templates, care rules, weather adjustments, and system audit logs.
- **Real-Time WebSockets**: Instant synchronization of care reminders, notification badges, and garden updates powered by Laravel Reverb.

## Default Credentials (Testing)

For development and evaluation purposes, default accounts are provided via the database seeder:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@example.com` | `password` |
| **Premium User** | `premium@example.com` | `password` |
| **Pro User** | `pro@example.com` | `password` |
| **Free User** | `free@example.com` | `password` |

## Getting Started

### Prerequisites

Ensure the following runtimes and services are installed on your host machine:
- **PHP** >= 8.3
- **Composer** >= 2.0
- **Node.js** >= 20.x and **npm**
- **MySQL** >= 8.0 (or SQLite)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Apisikma123/Grow-a-garden.git
   cd Grow-a-garden
   ```

2. **Install application dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Configure the environment**  
   Copy the example environment configuration and generate the application encryption key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Initialize and Seed the database**  
   Ensure your database server is running and the database specified in your `.env` is created. Run the migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

5. **Start the local development servers**  
   The project includes a unified npm script that utilizes `concurrently` to boot the PHP server, Vite, Queue Worker, Schedule Worker, and Reverb WebSocket server simultaneously:
   ```bash
   npm start
   ```
   To compile frontend assets for development individually:
   ```bash
   npm run dev
   ```
   The application will be accessible at `http://localhost:8000`.

## Environment Variables

The following configuration keys must be defined in your `.env` file to enable all application features:

| Variable | Description | Example Value |
| :--- | :--- | :--- |
| `APP_NAME` | Name of the application | `Grow a Garden` |
| `APP_URL` | Application base URL | `http://127.0.0.1:8000` |
| `DB_CONNECTION` | Database driver | `mysql` |
| `DB_HOST` | Database host address | `127.0.0.1` |
| `DB_PORT` | Database port number | `3306` |
| `DB_DATABASE` | Target MySQL database name | `mini_garden` |
| `DB_USERNAME` | Database user account | `root` |
| `DB_PASSWORD` | Database user password | `secret` |
| `GOOGLE_CLIENT_ID` | OAuth Client ID from Google Cloud Console | `your-client-id.apps.googleusercontent.com` |
| `GOOGLE_CLIENT_SECRET` | OAuth Secret from Google Cloud Console | `your-client-secret` |
| `GOOGLE_REDIRECT_URI` | Authorized redirect URI for Google Auth | `http://127.0.0.1:8000/auth/google/callback` |
| `REVERB_APP_ID` | Application identifier for Laravel Reverb | `grow-a-garden` |
| `REVERB_APP_KEY` | Public identifier for Reverb WebSockets | `your-reverb-key` |
| `REVERB_APP_SECRET` | Secret key for Reverb authentication | `your-reverb-secret` |
| `REVERB_HOST` | Host address for Reverb server | `localhost` |
| `REVERB_PORT` | Port for Reverb WebSocket connections | `8080` |
| `MAIL_MAILER` | Mail driver used for system OTP & notifications | `smtp` |

## Project Structure

```text
Grow-a-garden/
├── app/
│   ├── Console/Commands/       # Scheduled CLI commands (care tasks, weather sync)
│   ├── Http/Controllers/       # Request handling logic (Auth, Garden, Plant, Admin, Settings)
│   ├── Models/                 # Eloquent models (Garden, Plant, Event, Badge, User, etc.)
│   ├── Notifications/          # Database & mail notification channels
│   └── Services/               # Core services (AutopilotService, WeatherService, BadgeService)
├── config/                     # Application configuration files
├── database/
│   ├── factories/              # Model factories for testing and seeding
│   ├── migrations/             # Database schema migrations
│   └── seeders/                # Master data & test user seeders
├── public/                     # Public assets and web entry point
├── resources/
│   ├── css/                    # Tailwind CSS v4 stylesheets and Verdant Growth tokens
│   ├── js/                     # Client-side JavaScript, Alpine.js, & Laravel Echo setup
│   └── views/                  # Blade templates (Admin, Users, Auth, Components, Pages)
├── routes/
│   ├── console.php             # Artisan & scheduled task routing
│   └── web.php                 # HTTP routing definitions
└── vite.config.js              # Vite bundler configuration
```

## License and Author Information

This project is open-source software licensed under the MIT License.

- **GitHub:** [https://github.com/Apisikma123](https://github.com/Apisikma123)  
  **Email:** [agaputra62@gmail.com](https://mail.google.com/mail/?view=cm&fs=1&to=agaputra62@gmail.com)
- **GitHub:** [https://github.com/r4hmansun](https://github.com/r4hmansun)  
  **Email:** [4rrahman5578@gmail.com](https://mail.google.com/mail/?view=cm&fs=1&to=4rrahman5578@gmail.com)
- **GitHub:** [https://github.com/M-RapeliHSN](https://github.com/M-RapeliHSN)  
  **Email:** [raflyhusaini0290@gmail.com](https://mail.google.com/mail/?view=cm&fs=1&to=raflyhusaini0290@gmail.com)
