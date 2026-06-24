import { config } from 'dotenv';
config();

function required(name) {
    if (!process.env[name]) {
        throw new Error(`Missing environment variable: ${name}`)
    }
    return process.env[name];
}

export const PORT = process.env.PORT || 3000;

export const MERCADOPAGO_API_KEY = required('MERCADOPAGO_API_KEY');
export const FRONTEND_URL = process.env.FRONTEND_URL || "http://localhost";
export const FRONTEND_BASEURL = process.env.FRONTEND_BASEURL || "http://localhost/website_project/pages";

export const DB_HOST = process.env.DB_HOST || "localhost";
export const DB_USER = process.env.DB_USER || "root";
export const DB_PASSWORD = process.env.DB_PASSWORD || "";
export const DB_NAME = process.env.DB_NAME || "website_project";