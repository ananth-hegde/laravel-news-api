# Laravel News API

This project is a Laravel-based API for aggregating news from multiple sources. The API provides endpoints for user authentication, managing news articles, and user preferences.

## Getting Started

Follow these steps to set up and run the project locally.

### Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop) (if not already installed)

### Installation

1. **Clone the repository:**
 ```sh
   git clone https://github.com/ananth-hegde/laravel-news-api.git
   cd laravel-news-api
```
2. **Install Docker Desktop:**

If Docker Desktop is not already installed, download and install it from here.
https://www.docker.com/products/docker-desktop

3. **Run Docker Compose:**

Start the application using Docker Compose:
```sh
docker-compose up -d
```

4. **Run entrypoint script**

Run the below command to apply the migrations and seed database with initial data:

```sh
docker-compose exec app ./entrypoint.sh
```

5. **Accessing the Application**
Once the application is up and running, you can view the API documentation at:
```sh
http://127.0.0.1:8000/api/documentation
```

This documentation provides details on all available endpoints, including authentication, news articles, and user preferences.

**Additional Information**
1) The application uses Laravel Sanctum for API authentication.
2) The API documentation is generated using Swagger and can be accessed at the URL mentioned above.
3) All endpoints except for register, login and resetpassword are protected using Sanctum. Please ensure to copy the bearer token returned after successful registration/login, it will be used for all further APIs

**Contributing**
If you would like to contribute to this project, please fork the repository and submit a pull request.

**License**
This project is licensed under the MIT License.