# Gazdovsky Dvor - webová aplikácia [LARAVEL]

**Tento projekt** je moderná webová aplikácia pre reštauráciu vyvinutá v Laraveli. Aplikácia ponúka komplexnú správu jedálneho lístka, rezervácií, recenzií zákazníkov a ďalších funkcií, ktoré uľahčujú každodenné riadenie reštaurácie.

> **Poznámka:** Táto aplikácia je určená pre reštaurácie, ktoré chcú efektívne spravovať svoje služby a komunikovať so zákazníkmi online.

---

## Požiadavky

Pred inštaláciou sa uistite, že máte nainštalované nasledujúce softvérové balíky:

- **XAMPP** (alebo iný PHP server s podporou PHP 8.0+, MySQL a Apache)
- **Composer** – správca PHP závislostí  
  [Stiahnite Composer](https://getcomposer.org/)
- **Node.js a npm** – pre správu a zostavenie front-endových assetov (Tailwind CSS, Vite, JavaScript)  
  [Stiahnite Node.js](https://nodejs.org/)
- **Git** – na klonovanie repozitára (voliteľné, ale odporúčané)

---

## Inštalácia projektu

### 1. Klonovanie repozitára

Ak máte Git, jednoducho si klonujte repozitár:

```bash
git clone https://github.com/Petrrixx/Bachelor-project__Bolecek.git
```

### 2. Inštalácia závislostí

Nainštalujte PHP závislosti pomocou Composer:
```bash
composer install
```
Nainštalujte front-end závislosti pomocou npm:
```bash
npm install
```

### 3. Konfigurácia prostredia

Skontrolujte databázu a jej nastavenie v súbore .env

Upravte súbor .env podľa svojich potrieb, napríklad pre pripojenie k MySQL databáze:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gazdovsky_dvor
DB_USERNAME=root
DB_PASSWORD=
```
> **Poznámka:** Nezabudnite vytvoriť databázu s názvom gazdovsky_dvor (alebo iným, ktorý zadáte v .env).

### 4. Konfigurácia Supabase

Táto aplikácia využíva Supabase pre správu dát. Zabezpečte si prístup k vášmu Supabase projektu na adrese:
https://tzkphgiwxfjssbltutxd.supabase.co

Ak bola databáza dlhšie neaktívna alebo vypnutá, odporúčame pred spustením vykonať zálohovanie dát prostredníctvom rozhrania Supabase.

---

## Spustenie aplikácie

### Lokálny vývojový server
Môžete spustiť lokálny vývojový server niekoľkými spôsobmi:

- **Laravel development server:** Otvorte terminál a spustite:
```bash
php artisan serve
```
Aplikácia bude následne dostupná napríklad na http://localhost:8000.

### Frontend development server
V samostatnom termináli spustite:
```bash
npm run dev
```
Týmto sa spustí Vite, ktorý bude sledovať zmeny v CSS a JavaScript súboroch. Pre produkčné zostavenie použite:
```bash
npm run build
```

---

## Ako aplikácia funguje

### Pre používateľov:
- Na hlavnej stránke sa môžete prihlásiť ako bežný používateľ.
- Prehliadať jedálny lístok, rezervovať stôl alebo pridať recenziu, prípadne kontaktovať prevádzku cez formulár.

### Pre administrátorov:
- Administrátori majú rozšírené možnosti – môžu pridávať a editovať položky jedálneho lístka, spravovať rezervácie, mazať recenzie a vykonávať ďalšie úpravy záznamov v databáze.

---

## Tipy pre vývojárov

- **Vývoj:**
Spúšťajte npm run dev v jednom termináli a php artisan serve v druhom, aby ste mali okamžitú spätnú väzbu na zmeny v kóde.

- **Debugging:**
Ak narazíte na problémy, skontrolujte logy v storage/logs/laravel.log a overte správnosť nastavení v súbore .env.

- **Nasadenie:**
Pre produkčné prostredie zostavte front-end assety pomocou npm run build a nasadte skompilované súbory spolu s Laravel aplikáciou na váš webový server (Apache, Nginx, pod.).

---

## Záver

Webová stránka Gazdovsky Dvor je navrhnutá tak, aby bola flexibilným nástrojom pre riadenie reštaurácie a pomohol vám optimalizovať podnikanie a zlepšiť komunikáciu so zákazníkmi. Ak máte akékoľvek otázky alebo potrebujete pomoc, neváhajte kontaktovať vývojový tím.

*Príjemný vývoj a dobrú chuť!*
