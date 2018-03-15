# Nilai

[![Join the chat at https://gitter.im/nilai/Lobby](https://badges.gitter.im/nilai/Lobby.svg)](https://gitter.im/nilai/Lobby?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bantenprov/nilai/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bantenprov/nilai/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/bantenprov/nilai/badges/build.png?b=master)](https://scrutinizer-ci.com/g/bantenprov/nilai/build-status/master)
[![Latest Stable Version](https://poser.pugx.org/bantenprov/nilai/v/stable)](https://packagist.org/packages/bantenprov/nilai)
[![Total Downloads](https://poser.pugx.org/bantenprov/nilai/downloads)](https://packagist.org/packages/bantenprov/nilai)
[![Latest Unstable Version](https://poser.pugx.org/bantenprov/nilai/v/unstable)](https://packagist.org/packages/bantenprov/nilai)
[![License](https://poser.pugx.org/bantenprov/nilai/license)](https://packagist.org/packages/bantenprov/nilai)
[![Monthly Downloads](https://poser.pugx.org/bantenprov/nilai/d/monthly)](https://packagist.org/packages/bantenprov/nilai)
[![Daily Downloads](https://poser.pugx.org/bantenprov/nilai/d/daily)](https://packagist.org/packages/bantenprov/nilai)

Nilai

### Install via composer

- Development snapshot

```bash
$ composer require bantenprov/nilai:dev-master
```

- Latest release:

```bash
$ composer require bantenprov/nilai
```

### Download via github

```bash
$ git clone https://github.com/bantenprov/nilai.git
```

#### Edit `config/app.php` :

```php
'providers' => [

    /*
    * Laravel Framework Service Providers...
    */
    Illuminate\Auth\AuthServiceProvider::class,
    Illuminate\Broadcasting\BroadcastServiceProvider::class,
    Illuminate\Bus\BusServiceProvider::class,
    Illuminate\Cache\CacheServiceProvider::class,
    Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
    Illuminate\Cookie\CookieServiceProvider::class,
    //....
    Bantenprov\Nilai\NilaiServiceProvider::class,
```

#### Lakukan migrate :

```bash
$ php artisan migrate
```

#### Publish database seeder :

```bash
$ php artisan vendor:publish --tag=nilai-seeds
```

#### Lakukan auto dump :

```bash
$ composer dump-autoload
```

#### Lakukan seeding :

```bash
$ php artisan db:seed --class=BantenprovNilaiSeeder
```

#### Lakukan publish component vue :

```bash
$ php artisan vendor:publish --tag=nilai-assets
$ php artisan vendor:publish --tag=nilai-public
```
#### Tambahkan route di dalam file : `resources/assets/js/routes.js` :

```javascript
{
    path: '/dashboard',
    redirect: '/dashboard/home',
    component: layout('Default'),
    children: [
        //== ...
        {
         path: '/dashboard/nilai',
         components: {
            main: resolve => require(['./components/views/bantenprov/nilai/DashboardNilai.vue'], resolve),
            navbar: resolve => require(['./components/Navbar.vue'], resolve),
            sidebar: resolve => require(['./components/Sidebar.vue'], resolve)
          },
          meta: {
            title: "Nilai"
           }
       },
        //== ...
    ]
},
```

```javascript
{
    path: '/admin',
    redirect: '/admin/dashboard/home',
    component: layout('Default'),
    children: [
        //== ...
        {
            path: '/admin/nilai',
            components: {
                main: resolve => require(['./components/bantenprov/nilai/Nilai.index.vue'], resolve),
                navbar: resolve => require(['./components/Navbar.vue'], resolve),
                sidebar: resolve => require(['./components/Sidebar.vue'], resolve)
            },
            meta: {
                title: "Nilai"
            }
        },
        {
            path: '/admin/nilai/create',
            components: {
                main: resolve => require(['./components/bantenprov/nilai/Nilai.add.vue'], resolve),
                navbar: resolve => require(['./components/Navbar.vue'], resolve),
                sidebar: resolve => require(['./components/Sidebar.vue'], resolve)
            },
            meta: {
                title: "Add Nilai"
            }
        },
        {
            path: '/admin/nilai/:id',
            components: {
                main: resolve => require(['./components/bantenprov/nilai/Nilai.show.vue'], resolve),
                navbar: resolve => require(['./components/Navbar.vue'], resolve),
                sidebar: resolve => require(['./components/Sidebar.vue'], resolve)
            },
            meta: {
                title: "View Nilai"
            }
        },
        {
            path: '/admin/nilai/:id/edit',
            components: {
                main: resolve => require(['./components/bantenprov/nilai/Nilai.edit.vue'], resolve),
                navbar: resolve => require(['./components/Navbar.vue'], resolve),
                sidebar: resolve => require(['./components/Sidebar.vue'], resolve)
            },
            meta: {
                title: "Edit Nilai"
            }
        },
        //== ...
    ]
},
```
#### Edit menu `resources/assets/js/menu.js`

```javascript
{
    name: 'Dashboard',
    icon: 'fa fa-dashboard',
    childType: 'collapse',
    childItem: [
        //== ...
        {
        name: 'Nilai',
        link: '/dashboard/nilai',
        icon: 'fa fa-angle-double-right'
        },
        //== ...
    ]
},
```

```javascript
{
    name: 'Admin',
    icon: 'fa fa-lock',
    childType: 'collapse',
    childItem: [
        //== ...
        {
        name: 'Nilai',
        link: '/admin/nilai',
        icon: 'fa fa-angle-double-right'
        },
        //== ...
    ]
},
```

#### Tambahkan components `resources/assets/js/components.js` :

```javascript
//== Nilai

import Nilai from './components/bantenprov/nilai/Nilai.chart.vue';
Vue.component('echarts-nilai', Nilai);

import NilaiKota from './components/bantenprov/nilai/NilaiKota.chart.vue';
Vue.component('echarts-nilai-kota', NilaiKota);

import NilaiTahun from './components/bantenprov/nilai/NilaiTahun.chart.vue';
Vue.component('echarts-nilai-tahun', NilaiTahun);

import NilaiAdminShow from './components/bantenprov/nilai/NilaiAdmin.show.vue';
Vue.component('admin-view-nilai-tahun', NilaiAdminShow);

//== Echarts Group Egoverment

import NilaiBar01 from './components/views/bantenprov/nilai/NilaiBar01.vue';
Vue.component('nilai-bar-01', NilaiBar01);

import NilaiBar02 from './components/views/bantenprov/nilai/NilaiBar02.vue';
Vue.component('nilai-bar-02', NilaiBar02);

//== mini bar charts
import NilaiBar03 from './components/views/bantenprov/nilai/NilaiBar03.vue';
Vue.component('nilai-bar-03', NilaiBar03);

import NilaiPie01 from './components/views/bantenprov/nilai/NilaiPie01.vue';
Vue.component('nilai-pie-01', NilaiPie01);

import NilaiPie02 from './components/views/bantenprov/nilai/NilaiPie02.vue';
Vue.component('nilai-pie-02', NilaiPie02);

//== mini pie charts


import NilaiPie03 from './components/views/bantenprov/nilai/NilaiPie03.vue';
Vue.component('nilai-pie-03', NilaiPie03);

```

