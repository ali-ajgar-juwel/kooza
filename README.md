# Kooza – Product Selling Web Application

**Kooza** is a custom-built e-commerce web application designed for small business owners, particularly Facebook-based entrepreneurs, to manage their online product sales more efficiently. It provides essential e-commerce functionalities, including product browsing, shopping cart management, customer reviews, and order processing.

## Overview

Kooza is developed using Core PHP and MySQL, offering a simple and lightweight platform for managing an online store. The application focuses on helping small business owners organize their products, handle customer orders, and provide a convenient shopping experience.

The platform features a responsive user interface, making it accessible to customers using both desktop and mobile devices.

## Technologies Used

| Category | Technologies |
|---|---|
| Backend | Core PHP |
| Frontend | HTML5, CSS3 |
| Database | MySQL |
| Development | PHP, MySQL |

## Project Structure

```text
Kooza/
│
├── admin/               # Admin panel for product and order management
├── images/              # Product images and other assets
├── config.php           # Database connection and configuration
├── index.php            # Homepage of the application
├── cart.php             # Shopping cart page
├── add_to_cart.php      # Add products to the cart
├── product.php          # Product details page
├── place_order.php      # Order placement functionality
├── confirm.php          # Order confirmation
├── reviews.php          # Display product reviews
├── add_review.php       # Submit product reviews
│
└── *.css                # Stylesheets and UI styling
```

## Key Features

- **Product Management:** Manage and display products through the administrative panel.
- **Shopping Cart:** Add, remove, and manage products before checkout.
- **Order Processing:** Place orders and handle the checkout process.
- **Customer Reviews:** Allow customers to submit feedback and view product reviews.
- **Admin Dashboard:** Manage products and customer orders from the administrative panel.
- **Responsive Design:** Mobile-friendly interface for a better shopping experience across different devices.

## Installation & Setup

Follow these steps to run the project locally:

1. Clone the repository:

   ```bash
   git clone <repository-url>
   ```

2. Move the project folder into your local server directory (e.g., `htdocs` if using XAMPP).

3. Create a MySQL database for the project.

4. Update the database connection credentials in `config.php`.

5. Import the project's SQL database file into your newly created database, if available.

6. Start Apache and MySQL using XAMPP.

7. Open the application in your browser:

   ```text
   http://localhost/Kooza/
   ```

## Project Purpose

This project was developed to demonstrate practical web development skills using Core PHP, MySQL, HTML, and CSS. It focuses on implementing fundamental e-commerce functionalities and understanding database-driven web application development.

## License

This project is available for educational and personal use.