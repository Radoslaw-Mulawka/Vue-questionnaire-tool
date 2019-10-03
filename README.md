DSI-TOOLS
===

## Spis treści

[TOC]

------

## Historia wersji

[Version.md]: Version.md

## Ustalenia zespołu projektowego

#### 1. Rekomendacje do których się stosujemy

- PSR: https://www.php-fig.org/psr/
- SOLID: https://www.p-programowanie.pl/paradygmaty-programowania/zasady-solid/

#### 2. Tworzenie nazw dla klas, bazy danych itp.

- Nazwy klas i tabel w języku angielskim

- Nazwy klas i table w liczbie pojedynczej (np. UserEntity, LogEntity)

  PSR Convention (https://www.php-fig.org/bylaws/psr-naming-conventions/)

> 1. Interfaces MUST be suffixed by `Interface`: e.g. `Psr\Foo\BarInterface`.
> 2. Abstract classes MUST be prefixed by `Abstract`: e.g. `Psr\Foo\AbstractBar`.
> 3. Traits MUST be suffixed by `Trait`: e.g. `Psr\Foo\BarTrait`.

#### 3. Komenda scripts/console

sh scripts/console.sh "$1"

#### 4. Installing
```bash

# Migration and DB seeder (after changing your DB settings in .env)
php artisan migrate --seed

# Generate JWT secret key
php artisan jwt:secret

# install dependency
npm install

# develop
npm run dev # or npm run watch

# Build on production
npm run production
```
