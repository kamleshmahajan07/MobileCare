<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

function formatPrice(mixed $price): string
{
    return '₹' . number_format((float) $price, 2);
}