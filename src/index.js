import express from 'express';
import morgan from 'morgan';
import paymentRoutes from './routes/payment-routes.js';
import { PORT, FRONTEND_URL } from './config.js';
import path from 'path';
import cors from 'cors';

const app = express()

console.log("CORS origin:", FRONTEND_URL);

app.use(cors({
    origin: FRONTEND_URL,
    methods: ['GET', 'POST'],
    credentials: true
}));

app.use(morgan('dev'));
app.use(express.json());

app.use('/api/payments', paymentRoutes);

app.get('/', (req, res) => {
    res.send('API working')
});

app.use((req, res) => {
    res.status(404).json({ error: 'Route not found' });
})

app.use((err, req, res, next) => {
    console.log(err);
    res.status(500).json({ error: 'Internal server error' });
})

app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`)
});