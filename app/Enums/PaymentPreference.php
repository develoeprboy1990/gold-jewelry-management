<?php

namespace App\Enums;

enum PaymentPreference: string
{
    case CASH = 'Cash';
    case CREDIT_CARD = 'Credit Card';
    case BANK_TRANSFER = 'Bank Transfer';
    case ONLINE_PAYMENT = 'Online Payment';
    case PAYPAL = 'Paypal';
    case RAZORPAY = 'Razorpay';
    case STRIPE = 'Stripe';
    case OTHER = 'Other';
}
