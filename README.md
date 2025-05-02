# League Simulator

A brief description of what this project does. (Replace with actual description)

## Tech Stack

-   PHP (Laravel)
-   Node.js (Vite, Tailwind CSS)
-   Nginx
-   MySQL (or specify database from docker-compose.yml)
-   Redis (if used, check docker-compose.yml)

## Prerequisites

-   Docker
-   Docker Compose

## Installation

1.  **Clone the repository:**

    ```bash
    git clone git@github.com:aliihsansepar/league-simulator.git
    cd league-simulator
    ```

2.  **Set up environment variables:**
    Copy the example environment file and configure it according to your needs. (Specify which .env file if applicable, e.g., `.env.example` to `.env`)

    ```bash
    cp .env.example .env
    # Edit .env file with your specific configuration
    ```

    _Note: Ensure database credentials and other sensitive information are set correctly._

3.  **Build and run the Docker containers:**

    ```bash
    docker-compose up -d --build
    ```

    This command will build the images and start the services defined in `docker-compose.yml` in detached mode.

4.  **Install PHP dependencies:**

    ```bash
    docker-compose exec app composer install
    ```

5.  **Generate application key:**

    ```bash
    docker-compose exec app php artisan key:generate
    ```

6.  **Run database migrations:**

    ```bash
    docker-compose exec app php artisan migrate --seed
    ```

7.  **Install Node.js dependencies and build assets:**

    ```bash
    docker-compose exec node npm install
    docker-compose exec node npm run dev #for development
    ```

8.  **Access the application:**
    The application should now be accessible at `http://localhost` (or the port specified in `docker-compose.yml` or Nginx config).

## Usage

Explain how to use the application or specific features.

## Running Tests

Explain how to run the test suite. For example:

```bash
docker-compose exec app php artisan test
```

## Contributing

Information on how to contribute to the project (optional).

## License

Specify the project license (optional).
