# News Aggregator Api


This is a **News Aggregator API** built with **Laravel 9**. The app regularly fetches the latest news articles from multiple sources and stores them in the database. Users can sign up, log in, and set their preferred news sources, categories, and authors.


## How to Run the Application
#### 1. Clone the Repository
```bash
git clone git@github.com:DarshPhpDev/news-aggregator-api.git
```
Navigate to the project directory:
```bash
cd news-aggregator-api
```

#### 2. Run the Application with Docker
Ensure you have **Docker** and **docker-compose** installed on your machine. To set up and run the application, use the following command:
```bash
docker-compose up -d
```
and wait for the docker images to be pulled, built and the containers to be up and running.

#### 3. Run Database Migrations and Seeders
```bash
docker-compose exec app php artisan migrate --seed
```
This command will create the necessary tables and seed the database with initial data.

#### 4. Manually Fetch Articles
```bash
docker-compose exec app php artisan articles:fetch
```

Optionally, you can set up a cron job to automatically run the news fetcher every hour. Add the following line to your server’s crontab:
```bash
* * * * * docker-compose exec app php artisan schedule:run >> /dev/null 2>&1
```
This will ensure that the application regularly fetches the latest articles from external sources.

#### 5. Test the App
Once the setup is complete, open your browser and navigate to:
```bash
http://localhost:8000
```
The app is now up and running.

#### 6. API Documentation
Since the app is up and running, you can browse the api documentation and execute api calls through this url:
```bash
http://localhost:8000/api/documentation
```
## Technologies Used

- **PHP 8.x**
-   **Laravel 9**
-   **Laravel Sanctum** (for API authentication)
-   **Swagger** (for API documentation)
-   **MySQL**
-   **Docker** (with Docker Compose)


## License

This project is open-source and licensed under the MIT License.
