# mySupir

## Requirements
### Nexmo
Change your `NEXMO_API_KEY` and `NEXMO_API_SECRET` in `.env` file

### Pusher
Change your `PUSHER_APP_ID`, `PUSHER_APP_KEY`, `PUSHER_APP_SECRET`, and `PUSHER_APP_CLUSTER` in `.env` file

## Midtrans
Change your `MIDTRANS_MERCHANT_ID`, `MIDTRANS_CLIENT_KEY`, and `MIDTRANS_SERVER_KEY` in `.env` file

## Some notable features of this include:
- [Laravel UI](https://laravel.com/docs/6.x/frontend)
- [Ide Helper](https://github.com/barryvdh/laravel-ide-helper)
- [Debugbar](https://github.com/barryvdh/laravel-debugbar)
- [Laravel Sanctum](https://laravel.com/docs/7.x/sanctum)
- [Laravel CORS](https://github.com/fruitcake/laravel-cors)
- [Laravel Permission](https://spatie.be/docs/laravel-permission) by Spatie
- [Midtrans PHP](https://github.com/Midtrans/midtrans-php)
- [Musonza Chat](https://github.com/musonza/chat)

## Installation
1. Clone this repository
2. Run `composer install`
3. Setup `.env` file. You can copy `.env.example`
4. Generate ide helper: `php artisan ide-helper:generate`
5. Run migrations `php artisan migrate`
6. Run database seeding `php artisan db:seed` for initialize role etc
7. Happy coding! 👩‍💻👨‍💻💻
