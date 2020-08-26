# Laratalk Package

Laratalk adalah package laravel untuk kebutuhan realtime chat di aplikasi Anda.

[![Latest Stable Version](https://poser.pugx.org/febrihidayan/laratalk/v)](//packagist.org/packages/febrihidayan/laratalk) [![Total Downloads](https://poser.pugx.org/febrihidayan/laratalk/downloads)](//packagist.org/packages/febrihidayan/laratalk) [![Latest Unstable Version](https://poser.pugx.org/febrihidayan/laratalk/v/unstable)](//packagist.org/packages/febrihidayan/laratalk) [![License](https://poser.pugx.org/febrihidayan/laratalk/license)](//packagist.org/packages/febrihidayan/laratalk)

## Peryaratan

- PHP >= 7.2
- Laravel >= 6.0
- Pusher Api

## Fitur

- User chat private
- Fungsi pencarian
- Dark mode

## Installasi

Install package Laratalk
```
composer require febrihidayan/laratalk
```

Kemudian jalankan artisan berikut untuk publish asset
```
php artisan laratalk:install
```

## Konfigurasi

Jangan lupa atur pusher Anda di `.env`
```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=ap1
```

Selanjutnya Anda bisa konfigurasi di file `laratalk.php` di folder config aplikasi Anda.

## Owner
- [Febri Hidayan](https://github.com/febrihidayan)

## Donasi
Berikan saya donasi untuk terus memberikan aplikasi open source yang bermanfaat.
- [Paypal](https://paypal.me/febrihidayan)
- [Dana](https://link.dana.id/qr/2d6by546)
- [Trakteer](https://trakteer.id/febrihidayan)
