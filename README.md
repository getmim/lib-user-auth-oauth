# lib-user-auth-oauth

Adalah module yang memungkinkan request dari user diauthentikasi berdasarkan metode
OAuth. Module ini membutuhkan ekstensi php [OAuth](http://php.net/manual/en/book.oauth.php)
terinstall untuk bisa berjalan.

## Instalasi

Jalankan perintah di bawah di folder aplikasi:

```
mim app install lib-user-auth-oauth
```

## Konfigurasi

Tambahkan konfigurasi seperti di bawah pada konfigurasi aplikasi/module.

```php
return [
    'apiAuthOAuth' => [
        'loginRoute' => 'siteLogin'
    ]
];
```

Opsi `loginRoute` akan digunakan oleh system ketika suatu aplikasi meminta authorize
sementara user belum login di browser tersebut.

## Penggunaan

Untuk metode autentikasi pada client, silahkan lihat source code yang tersimpa di folder
`example` di repository module ini.

Secara umum, module ini membuka dua endpoint, yaitu `APIHOST/oauth/request_token` dan `APIHOST/oauth/access_token`.