# BlendBurger

A restaurant web page built in with PHP, MySQL, JavaScript and Node.js.

The project allows customers to browse products, manage their cart, place orders, and pay online through Mercado Pago. It also includes a complete administration panel for managing users, products, categories, orders, addresses, and application settings.

# Current status: Under development.

---

### Features

## Client
- User registration and authentication.
- Profile management.
- Adress management system.
- Shopping cart.
- Checkout process.
- Mercado Pago integration.
- Blendpoints reward system.

## Administration
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

### Tech

## Backend
- PHP 8
- Node.js
- Express
- MySQL
- Mercado Pago SDK

## Frontend
- HTML5
- CSS3
- JavaScript

## Database and other tools
- XAMPP
- Ngrok
- Git

---

### Project Structure

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

IMPORTANT
.env file must include the next values:
-MERCADOPAGO_API_KEY
-FRONTEND_URL
-FRONTEND_BASEURL
