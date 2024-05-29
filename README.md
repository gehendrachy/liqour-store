# Liquor Store E-commerce Platform

Liquor Store is a sophisticated multi-vendor e-commerce platform specifically designed for selling liquors. Developed using PHP Laravel 8, jQuery, and MySQL, the platform features multiple role-based permission levels, providing a secure and efficient environment for vendors and customers.

## Description

Liquor Store offers a comprehensive solution for a multi-vendor marketplace. It supports different user roles including Super Admin, Vendor, and Customer, each with specific permissions. The Super Admin role is responsible for adding products and their variations, such as size and type. Vendors can select these products, add them to their inventory, and set their own pricing for each variation. Customers can browse and purchase liquors from different vendors, comparing prices and variations.

## Technical Stack

- **Backend Framework:** PHP Laravel 8
- **Frontend Library:** jQuery
- **Database:** MySQL
- **Templating Engine:** Blade (Laravel)
- **ORM:** Eloquent (Laravel)
- **Charts and Reports:** Chart.js for sales analytics
- **Payment Gateway:** Integration with multiple secure payment APIs (e.g., Stripe, PayPal)
- **Authentication:** Laravel Passport for API authentication
- **Notification System:** Real-time notifications using Laravel Echo and Pusher

## Features

### Multi-Role Permissions

- **Super Admin:**
  - Add and manage products and their variations (size, type, etc.).
  - View comprehensive sales reports and analytics.
  - Manage vendor accounts and permissions.

- **Vendor:**
  - Select products added by Super Admin and add to their own inventory.
  - Set prices for products and their variations.
  - Manage their inventory and view sales statistics.

- **Customer:**
  - Browse and purchase products from various vendors.
  - Compare prices and choose vendors based on product variations and pricing.
  - Utilize click and collect options and multiple payment methods.

### Product and Inventory Management

- **Product Variations:**
  - Support for multiple variations of products such as size, type, and packaging.
  - Vendors can set individual prices for each variation.

### Order and Payment System

- **Order Processing:**
  - Real-time order tracking and notifications.
  - Click and collect option for local pickups.

- **Payment Integration:**
  - Supports multiple payment methods including credit/debit cards, PayPal, and other digital wallets.
  - Secure transactions through SSL encryption and secure APIs.

### Analytics and Reporting

- **Sales Analytics:**
  - Multiple charts and graphs displaying sales data, inventory levels, and vendor performance.
  - Real-time data visualization using Chart.js.

### Additional Features

- **Responsive Design:**
  - Optimized for various devices to ensure a consistent user experience across desktops, tablets, and mobile phones.

- **Search and Filter:**
  - Advanced search and filtering options to help customers find products quickly.

- **Reviews and Ratings:**
  - Customers can leave reviews and ratings for products and vendors.

- **Email Notifications:**
  - Automated email notifications for order confirmations, shipping updates, and promotional offers.

## Installation

### Prerequisites

- PHP >= 7.3
- Composer
- MySQL
- Node.js and npm

### Steps

1. **Clone the Repository:**
    ```bash
    git clone https://github.com/gehendrachy/liquor-store.git
    cd liquor-store
    ```

2. **Install Dependencies:**
    ```bash
    composer install
    npm install
    npm run dev
    ```

3. **Environment Setup:**
    - Copy `.env.example` to `.env` and update the configuration settings (database, mail, etc.)
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Database Setup:**
    - Create a new MySQL database and update `.env` with your database credentials.
    ```bash
    php artisan migrate --seed
    ```

5. **Run the Application:**
    ```bash
    php artisan serve
    ```

6. **Access the Application:**
    - Open your web browser and go to `http://localhost:8000`

## Development

### Compiling Assets

- To compile CSS and JavaScript assets, use Laravel Mix:
    ```bash
    npm run dev
    ```

### Running Tests

- Run the test suite to ensure everything is working correctly:
    ```bash
    php artisan test
    ```

## Contribution

Contributions are welcome! Please fork the repository and submit pull requests for any enhancements or bug fixes.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.

---

For any queries or further assistance, please contact us at support@liquorstore.com.

Thank you for choosing Liquor Store!
