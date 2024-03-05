# Technical-Exercise---Laravel-Developer-Solution

# Product API

This is a Laravel-based API for managing products. It supports operations like creating products, listing products, and filtering products by average rating.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

- PHP >= 7.3
- Composer
- Laravel >= 8.0
- SQLite (for testing)

### Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/yourusername/product-api.git
    ```
2. Navigate to the project directory:
    ```bash
    cd product-api
    ```
3. Install the dependencies:
    ```bash
    composer install
    ```
4. Copy the example environment file and make the required configuration changes in the `.env` file:
    ```bash
    cp .env.example .env
    ```
5. Generate a new application key:
    ```bash
    php artisan key:generate
    ```
6. Run the database migrations:
    ```bash
    php artisan migrate
    ```

### Running the Application

To start the application, you can use the Laravel's built-in server:

```bash
php artisan serve


The API will be available at http://localhost:8000.

Running the Tests
To run the tests, use the following command:

API Endpoints
GET /products: List all products
GET /products?average_rating={rating}: Filter products by average rating
