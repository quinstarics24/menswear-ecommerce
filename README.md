# menswear-ecommerce

## Project Overview
This is a menswear e-commerce website that allows users to browse, add to cart, and purchase clothing items online. The project is built using PHP and includes various functionalities to enhance user experience.

## File Structure
- **cart.php**: Manages the shopping cart functionality, including adding, removing, and displaying products.
- **index.php**: The homepage that showcases featured products and links to other sections.
- **product.php**: Displays individual product details and options to add products to the cart.
- **checkout.php**: Handles the checkout process, including user information collection and payment processing.
- **includes/**: Contains reusable components such as header, footer, database connection, and utility functions.
  - **header.php**: HTML for the site header, including navigation.
  - **footer.php**: HTML for the site footer, including copyright information.
  - **db.php**: Manages database connections and queries.
  - **functions.php**: Utility functions for formatting and validation.
- **templates/**: Contains templates for rendering specific components.
  - **cart_items.php**: Renders items in the shopping cart.
- **assets/**: Contains static files such as CSS and JavaScript.
  - **css/styles.css**: Styles for the website.
  - **js/app.js**: JavaScript for client-side functionality.
- **composer.json**: Configuration file for PHP dependencies.
- **.env**: Environment variables for configuration.
- **README.md**: Documentation for the project.

## Setup Instructions
1. Clone the repository to your local machine.
2. Navigate to the project directory.
3. Install dependencies using Composer:
   ```
   composer install
   ```
4. Set up your database and update the `.env` file with your database credentials.
5. Start the local server using XAMPP or any preferred method.
6. Access the application via your web browser at `http://localhost/menswear-ecommerce`.

## Usage Guidelines
- Browse products on the homepage.
- Click on a product to view details and add it to your cart.
- View your cart and proceed to checkout when ready.
- Follow the prompts to complete your purchase.

## Contributing
Contributions are welcome! Please fork the repository and submit a pull request for any improvements or bug fixes.