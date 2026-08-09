# BlendBurger

A restaurant web application built with PHP, MySQL, JavaScript and Node.js.

The project allows customers to browse products, manage their cart, place orders, and pay online through Mercado Pago. It also includes a complete administration panel for managing users, products, categories, orders, addresses, and application settings.

## Current status: Under development.

---

## Features

### Client
- User registration and authentication.
- Profile management.
- Adress management system.
- Shopping cart.
- Checkout process.
- Mercado Pago integration.
- Blendpoints reward system.

### Administration
- User management.
- Role management.
- Ban / Unban users.
- Product management.
- Category management.
- Order management.
- Application state management.
- Store configuration management.
- Image upload system.

---

## Tech

### Backend
- PHP 8
- Node.js
- Express
- MySQL
- Mercado Pago SDK

### Frontend
- HTML5
- CSS3
- JavaScript

### Database and other tools
- XAMPP
- Ngrok
- Git

---

## Project Structure

```
project/
│
├── pages/
├── fonts/
├── services/
├── src/
│   ├── controllers/
│   ├── routes/
│   └── config.js
├── uploads/
│   ├── ads/
│   ├── categories/
│   ├── products/
│   ├── states/
│   └── users/
├── images/
├── node_modules/
└── README.md
```

---

## Installation

### 1. Prerequisites

#### Required:
- XAMPP (Apache, PHP, MySQL)
- Node.js and npm
- Git
- Modern web browser

#### Required for payment/webhook testing:
- Ngrok account
- Mercado Pago developer account/access token

#### Optional:
- Visual Studio Code or another code editor

### 2. Clone the repository

Clone the repository inside XAMPP's htdocs directory so Apache can serve the PHP application.

```bash
git clone https://github.com/voltunos/website_project.git
```

### 3. Configure environment variables

Create a `.env` file inside the Node project.

Example:

```env
PORT=3000
MERCADOPAGO_API_KEY=YOUR_ACCESS_TOKEN
FRONTEND_URL=http://localhost/website_project/pages
DB_HOST=localhost
DB_USER=YOUR_DB_USER
DB_PASSWORD=YOUR_DB_PASSWORD
DB_NAME=YOUR_DB_NAME
```

---

### 4. Start XAMPP

Start:

- Apache
- MySQL

---

### 5. Import the database

Import the provided SQL file into MySQL.

Update `database.php` with your database credentials.

---

### 6. Start the Node server

```bash
npm run dev
```

---

### 7. Start ngrok

Mercado Pago webhooks require a public HTTPS URL.

Before hosting any local server with Ngrok, make sure you're logged in by using this command:

```bash
ngrok config add-authtoken $YOUR_AUTHTOKEN
```

You can copy your Authtoken by logging in Ngrok via a web browser > Your authtoken

Expose your local server:

```bash
ngrok http 3000
```

Copy the generated HTTPS URL and update:

- `notification_url`
- `back_urls`

inside the Mercado Pago configuration (src > controllers > payment-controller.js). Also make sure you don't overwrite the "/api/..." part.

---

## Security

Some implemented security features include:

- Password hashing.
- Prepared Statements (PDO).
- Input validation.
- Output escaping.
- Role-based authorization.
- Session authentication.
- Secure image uploads.
- Server-side payment validation.
- Cart validation before checkout.

---

## Future Improvements

- Email verification.
- Password recovery.
- Order tracking.
- Sales dashboard.
- Search and filtering.
- Product reviews.
- Two-factor authentication.
- Docker deployment.

---

## Screenshots

<img width="1902" height="911" alt="image" src="https://github.com/user-attachments/assets/67f8e4d8-ba3a-49b5-94ad-9a8743759d4d" />
<img width="1919" height="913" alt="image" src="https://github.com/user-attachments/assets/c7e7e426-f1f1-4116-9953-9ce91cf8c9e4" />


