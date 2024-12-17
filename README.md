
# Project Documentation

## Table of Contents
1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Usage](#usage)

---

## Introduction

---

## Installation
Follow these steps to install the project:
- Necessary to Install Docker and Composer
1. Clone the repository:
   ```bash
   git clone https://github.com/xging/CurrencyApp.git

2. In project folder "ClearPHP" open console and install composer.
   ```bash
   composer install
3. Change API Key to yours from (https://app.freecurrencyapi.com/dashboard) 
   ```bash
    File to change: src/Config.php:
    Line to change: 'key' => 'YOUR_KEY'
4. Build and start containers with Docker Compose
   ```bash
   docker-compose up --build

5. Run migration
   ```bash
   docker exec -it clear-php-container bash
   php ../migrations/migration.php

## Usage
## Console (docker exec -it clear-php-container bash)

1. Add Currency pairs
   ```bash
   php index.php add-pair GBP EUR
2. Remove Currency pairs
   ```bash
   php index.php remove-pair GBP EUR
3. Show Currency rate pair
   ```bash
   php index.php show-pair-rate GBP EUR 
4. Run&Watch Currency rate pairs
   ```bash
   php index.php watch-pair

## HTTP Request
1. Check current exchange rate by date
   - from = GBP
   - to = EUR
   - datetime=2024-12-16
   ```bash
   http://localhost:8080/api/get-currency-rates?from=GBP&to=EUR&datetime=2024-12-16

2. Check exchange rate history by date
   - from = GBP
   - to = EUR
   - date=2024-12-16
   ```bash
   http://localhost:8080/api/get-currency-rates-hist?from=GBP&to=EUR&date=2024-12-16

3. Check exchange rate history by date
   - from = GBP
   - to = EUR
   - date=2024-12-16
   - time=10:29:20
   ```bash
   http://localhost:8080/api/get-currency-rates-hist?from=GBP&to=EUR&date=2024-12-16&time=10:29:20
