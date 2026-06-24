import { Router } from "express";
import { FRONTEND_BASEURL } from "../config.js";
import { createOrder, receiveWebhook } from "../controllers/payment-controller.js";

const router = Router();

router.post('/create-order', createOrder);

router.get('/success', (req, res) => {res.redirect(`${FRONTEND_BASEURL}/main.php?status=success`)});

router.get('/failure', (req, res) => {res.redirect(`${FRONTEND_BASEURL}/main.php?status=failure`)});

router.get('/pending', (req, res) => {res.redirect(`${FRONTEND_BASEURL}/main.php?status=pending`)});

router.post('/webhook', receiveWebhook);

export default router;