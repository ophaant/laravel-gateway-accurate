# Gateway Accurate API

## Requirements

- PHP 8.1
- Composer
- postgresql

## Installation

1. run ``composer install``
2. then run migration ``php artisan migrate:fresh --seed``
3. generate passport keys ``php artisan passport:install`` if exists then run ``php artisan passport:install --force``
4. run ``php artisan serve``
5. run ``php artisan schedule:run``

## Usage

1. access url ``http://localhost:8000/api/v1/setup`` to setup the application connect accurate api

## List of API

table below is list of api that can be used

| Method | URI                                                     |
|--------|---------------------------------------------------------|
| GET    | api/v1                                                  |
| GET    | api/v1/setup                                            |
| GET    | api/v1/oauth-callback{url?}                             |
| POST   | api/v1/auth/register                                    |
| POST   | api/v1/auth/login                                       |
| POST   | api/v1/auth/logout                                      |
| POST   | api/v1/accurate/refresh-token                           |
| GET    | api/v1/accurate/databases                               |
| GET    | api/v1/accurate/customers                               |
| POST   | api/v1/accurate/customers                               |
| GET    | api/v1/accurate/employees                               |
| GET    | api/v1/accurate/items                                   |
| GET    | api/v1/accurate/sales-invoices                          |
| POST   | api/v1/accurate/sales-invoices                          |
| POST   | api/v1/accurate/sessions                                |
| GET    | api/v1/bank/account-types                               |
| GET    | api/v1/bank/categories                                  |
| POST   | api/v1/bank/categories                                  |
| GET    | api/v1/bank/categories/{category}                       |
| PUT    | api/v1/bank/categories/{category}                       |
| DELETE | api/v1/bank/categories/{category}                       |
| GET    | api/v1/bank/lists                                       |
| POST   | api/v1/bank/lists                                       |
| GET    | api/v1/bank/lists/{list}                                |
| PUT    | api/v1/bank/lists/{list}                                |
| DELETE | api/v1/bank/lists/{list}                                |
| GET    | api/v1/journal-voucher-uploads                          |
| POST   | api/v1/journal-voucher-uploads                          |
| DELETE | api/v1/journal-voucher-uploads/{journal_voucher_upload} |
| GET    | api/v1/whitelist-ips                                    |
| POST   | api/v1/whitelist-ips                                    |
| GET    | api/v1/whitelist-ips/{whitelist_ip}                     |
| PUT    | api/v1/whitelist-ips/{whitelist_ip}                     |
| DELETE | api/v1/whitelist-ips/{whitelist_ip}                     |
| GET    | api/v1/users                                            |
| POST   | api/v1/users                                            |
| GET    | api/v1/users/{user}                                     |
| PUT    | api/v1/users/{user}                                     |
| DELETE | api/v1/users/{user}                                     |
| GET    | api/v1/roles                                            |
| POST   | api/v1/roles                                            |
| GET    | api/v1/roles/{role}                                     |
| PUT    | api/v1/roles/{role}                                     |
| DELETE | api/v1/roles/{role}                                     |
| GET    | api/v1/permissions                                      |
| POST   | api/v1/permissions                                      |
| GET    | api/v1/permissions/{permission}                         |
| PUT    | api/v1/permissions/{permission}                         |
| DELETE | api/v1/permissions/{permission}                         |
