# LSMiniSocial — Instructions

## Prerequisites

- Docker & Docker Compose
- Git

## Setup & Installation

### 1. Clone the repository

```bash
git clone <your-repository-url>
cd grupo-06/local-environment
```

### 2. Start Docker containers

```bash
docker-compose up -d
```

This will start:
- **Nginx** on `http://localhost:4080`
- **PHP 8** (app container)
- **MySQL 8.4** on port `4306`
- **phpMyAdmin** on `http://localhost:4081`

### 3. Install PHP dependencies

```bash
docker exec -it pr-local-environment_app composer install
```

### 4. Configure environment

Copy the example env file and configure it:

```bash
cp www/.env.example www/.env
```

Edit `www/.env` and set the following values:

```env
CI_ENVIRONMENT = development

database.default.hostname = mysql
database.default.database = project_db
database.default.username = pw2user
database.default.password = pw2pass
database.default.DBDriver = MySQLi
database.default.port     = 3306

AI_API_KEY = your_api_key_here
AI_API_URL = https://api.groq.com/openai/v1/chat/completions
AI_MODEL   = llama-3.1-8b-instant
```

### 5. Run database migrations

```bash
docker exec -it pr-local-environment_app php spark migrate
```

### 6. Create upload directories

```bash
docker exec -it pr-local-environment_app mkdir -p public/uploads/posts public/uploads/profiles
```

### 7. Open the application

Visit `http://localhost:4080` in your browser.

---

## AI Integration

The AI feature uses an external LLM API to improve post content before publishing.

### How it works

1. The user writes a post and clicks **"Improve with AI"**
2. The frontend sends the text to our own backend endpoint `/ai/improve` (POST)
3. The backend (`AIController`) receives the request and calls `AIService`
4. `AIService` sends the text to the external AI API using **Guzzle**
5. The improved text is returned to the frontend as JSON
6. The user can **accept** (replace their text) or **reject** (keep original) the suggestion

### AI Provider

We use **Groq** as the AI provider (free tier available at [console.groq.com](https://console.groq.com)).

- Model: `llama-3.1-8b-instant`
- Endpoint: `https://api.groq.com/openai/v1/chat/completions`

The API follows the OpenAI-compatible format, so it can be swapped for any OpenAI-compatible provider by changing the `.env` variables:

```env
AI_API_KEY = your_key
AI_API_URL = https://api.openai.com/v1/chat/completions
AI_MODEL   = gpt-3.5-turbo
```

### Architecture

The AI logic is cleanly separated:
- `App\Interfaces\AIServiceInterface` — defines the contract
- `App\Services\AIService` — implements the Guzzle call to the external API
- `App\Controllers\AIController` — handles the HTTP request/response

The frontend **never calls the external AI API directly** — all requests go through `/ai/improve`.

---

## Multilanguage Support

The application supports **English** and **Spanish**.

- Language is auto-detected from the browser's `Accept-Language` header
- If the detected language is not supported, it defaults to **English**
- Language files are located in `app/Language/en/` and `app/Language/es/`
- Detection is handled by `App\Filters\LanguageFilter` which runs on every request

---

## Project Structure

```
www/
├── app/
│   ├── Config/         # Routes, Filters, App config
│   ├── Controllers/    # LandingController, AuthController, HomeController...
│   ├── Filters/        # AuthFilter, LanguageFilter
│   ├── Interfaces/     # PostRepositoryInterface, AIServiceInterface
│   ├── Language/       # en/App.php, es/App.php
│   ├── Models/         # UserModel, PostModel, LikeModel, CommentModel
│   ├── Services/       # AIService
│   └── Views/
│       ├── layouts/    # base.php (template inheritance)
│       ├── auth/       # sign_in.php, sign_up.php
│       └── posts/      # create.php, edit.php
├── public/
│   └── assets/
│       ├── css/        # style.css
│       └── js/         # home.js, ai-improve.js
└── .env
```

---

## Default Credentials (for testing)

Register a new account using a valid La Salle email:
- `@students.salle.url.edu`
- `@ext.salle.url.edu`
- `@salle.url.edu`

Password requirements: minimum 8 characters, at least one uppercase letter, one lowercase letter, and one number.

---

## Production Deployment

Before submitting, set in `www/.env`:

```env
CI_ENVIRONMENT = production
```
