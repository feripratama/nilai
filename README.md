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
    //...
    Bantenprov\Nilai\NilaiServiceProvider::class,
    //...
```

#### Lakukan migrate :

```bash
$ php artisan migrate
```

#### Lakukan publish semua komponen :

```bash
$ php artisan vendor:publish --tag=nilai-publish
```

#### Lakukan auto dump :

```bash
$ composer dump-autoload
```

#### Lakukan seeding :

```bash
$ php artisan db:seed --class=BantenprovNilaiSeeder
```

#### Edit menu `resources/assets/js/menu.js`

```javascript
{
    name: 'Dashboard',
    icon: 'fa fa-dashboard',
    childType: 'collapse',
    childItem: [
        //...
        // Akademik
        {
            name: 'Akademik',
            link: '/dashboard/akademik',
            icon: 'fa fa-angle-double-right'
        },
        // Nilai
        {
            name: 'Nilai',
            link: '/dashboard/nilai',
            icon: 'fa fa-angle-double-right'
        },
        //...
    ]
},
```

```javascript
{
    name: 'Admin',
    icon: 'fa fa-lock',
    childType: 'collapse',
    childItem: [
        //...
        // Akademik
        {
            name: 'Akademik',
            link: '/admin/akademik',
            icon: 'fa fa-angle-double-right'
        },
        // Nilai
        {
            name: 'Nilai',
            link: '/admin/nilai',
            icon: 'fa fa-angle-double-right'
        },
        //...
    ]
},
```

#### Tambahkan components `resources/assets/js/components.js` :

```javascript
//... Akademik ...//

import AkademikAdminShow from '~/components/bantenprov/nilai/akademik/AkademikAdmin.show.vue';
Vue.component('akademik-admin', AkademikAdminShow);

//... Echarts Akademik ...//

import Akademik from '~/components/bantenprov/nilai/akademik/Akademik.chart.vue';
Vue.component('akademik-echarts', Akademik);

import AkademikKota from '~/components/bantenprov/nilai/akademik/AkademikKota.chart.vue';
Vue.component('akademik-echarts-kota', AkademikKota);

import AkademikTahun from '~/components/bantenprov/nilai/akademik/AkademikTahun.chart.vue';
Vue.component('akademik-echarts-tahun', AkademikTahun);

//... Mini Bar Charts Akademik ...//

import AkademikBar01 from '~/components/views/bantenprov/nilai/akademik/AkademikBar01.vue';
Vue.component('akademik-bar-01', AkademikBar01);

import AkademikBar02 from '~/components/views/bantenprov/nilai/akademik/AkademikBar02.vue';
Vue.component('akademik-bar-02', AkademikBar02);

import AkademikBar03 from '~/components/views/bantenprov/nilai/akademik/AkademikBar03.vue';
Vue.component('akademik-bar-03', AkademikBar03);

//... Mini Pie Charts Akademik ...//

import AkademikPie01 from '~/components/views/bantenprov/nilai/akademik/AkademikPie01.vue';
Vue.component('akademik-pie-01', AkademikPie01);

import AkademikPie02 from '~/components/views/bantenprov/nilai/akademik/AkademikPie02.vue';
Vue.component('akademik-pie-02', AkademikPie02);

import AkademikPie03 from '~/components/views/bantenprov/nilai/akademik/AkademikPie03.vue';
Vue.component('akademik-pie-03', AkademikPie03);

//... Nilai ...//

import NilaiAdminShow from '~/components/bantenprov/nilai/nilai/NilaiAdmin.show.vue';
Vue.component('nilai-admin', NilaiAdminShow);

//... Echarts Nilai ...//

import Nilai from '~/components/bantenprov/nilai/nilai/Nilai.chart.vue';
Vue.component('nilai-echarts', Nilai);

import NilaiKota from '~/components/bantenprov/nilai/nilai/NilaiKota.chart.vue';
Vue.component('nilai-echarts-kota', NilaiKota);

import NilaiTahun from '~/components/bantenprov/nilai/nilai/NilaiTahun.chart.vue';
Vue.component('nilai-echarts-tahun', NilaiTahun);

//... Mini Bar Charts Nilai ...//

import NilaiBar01 from '~/components/views/bantenprov/nilai/nilai/NilaiBar01.vue';
Vue.component('nilai-bar-01', NilaiBar01);

import NilaiBar02 from '~/components/views/bantenprov/nilai/nilai/NilaiBar02.vue';
Vue.component('nilai-bar-02', NilaiBar02);

import NilaiBar03 from '~/components/views/bantenprov/nilai/nilai/NilaiBar03.vue';
Vue.component('nilai-bar-03', NilaiBar03);

//... Mini Pie Charts Nilai ...//

import NilaiPie01 from '~/components/views/bantenprov/nilai/nilai/NilaiPie01.vue';
Vue.component('nilai-pie-01', NilaiPie01);

import NilaiPie02 from '~/components/views/bantenprov/nilai/nilai/NilaiPie02.vue';
Vue.component('nilai-pie-02', NilaiPie02);

import NilaiPie03 from '~/components/views/bantenprov/nilai/nilai/NilaiPie03.vue';
Vue.component('nilai-pie-03', NilaiPie03);
```

#### Tambahkan route di dalam file : `resources/assets/js/routes.js` :

```javascript
{
    path: '/dashboard',
    redirect: '/dashboard/home',
    component: layout('Default'),
    children: [
        //...
        // Akademik
        {
            path: '/dashboard/akademik',
            components: {
                main: resolve => require(['~/components/views/bantenprov/nilai/akademik/AkademikDashboard.vue'], resolve),
                navbar: resolve => require(['~/components/Navbar.vue'], resolve),
                sidebar: resolve => require(['~/components/Sidebar.vue'], resolve)
            },
            meta: {
                title: "Akademik"
            }
        },
        // Nilai
        {
            path: '/dashboard/nilai',
            components: {
                main: resolve => require(['~/components/views/bantenprov/nilai/nilai/NilaiDashboard.vue'], resolve),
                navbar: resolve => require(['~/components/Navbar.vue'], resolve),
                sidebar: resolve => require(['~/components/Sidebar.vue'], resolve)
            },
            meta: {
                title: "Nilai"
            }
        },
        //...
    ]
},
```

```javascript
{
    path: '/admin',
    redirect: '/admin/dashboard/home',
    component: layout('Default'),
    children: [
        //...
        // Akademik
        {
            path: '/admin/akademik',
            components: {
                main: resolve => require(['~/components/bantenprov/nilai/akademik/Akademik.index.vue'], resolve),
                navbar: resolve => require(['~/components/Navbar.vue'], resolve),
                sidebar: resolve => require(['~/components/Sidebar.vue'], resolve)
            },
            meta: {
                title: "Akademik"
            }
        },
        {
            path: '/admin/akademik/create',
            components: {
                main: resolve => require(['~/components/bantenprov/nilai/akademik/Akademik.add.vue'], resolve),
                navbar: resolve => require(['~/components/Navbar.vue'], resolve),
                sidebar: resolve => require(['~/components/Sidebar.vue'], resolve)
            },
            meta: {
                title: "Add Akademik"
            }
        },
        {
            path: '/admin/akademik/:id',
            components: {
                main: resolve => require(['~/components/bantenprov/nilai/akademik/Akademik.show.vue'], resolve),
                navbar: resolve => require(['~/components/Navbar.vue'], resolve),
                sidebar: resolve => require(['~/components/Sidebar.vue'], resolve)
            },
            meta: {
                title: "View Akademik"
            }
        },
        {
            path: '/admin/akademik/:id/edit',
            components: {
                main: resolve => require(['~/components/bantenprov/nilai/akademik/Akademik.edit.vue'], resolve),
                navbar: resolve => require(['~/components/Navbar.vue'], resolve),
                sidebar: resolve => require(['~/components/Sidebar.vue'], resolve)
            },
            meta: {
                title: "Edit Akademik"
            }
        },
        // Nilai
        {
            path: '/admin/nilai',
            components: {
                main: resolve => require(['~/components/bantenprov/nilai/nilai/Nilai.index.vue'], resolve),
                navbar: resolve => require(['~/components/Navbar.vue'], resolve),
                sidebar: resolve => require(['~/components/Sidebar.vue'], resolve)
            },
            meta: {
                title: "Nilai"
            }
        },
        {
            path: '/admin/nilai/create',
            components: {
                main: resolve => require(['~/components/bantenprov/nilai/nilai/Nilai.add.vue'], resolve),
                navbar: resolve => require(['~/components/Navbar.vue'], resolve),
                sidebar: resolve => require(['~/components/Sidebar.vue'], resolve)
            },
            meta: {
                title: "Add Nilai"
            }
        },
        {
            path: '/admin/nilai/:id',
            components: {
                main: resolve => require(['~/components/bantenprov/nilai/nilai/Nilai.show.vue'], resolve),
                navbar: resolve => require(['~/components/Navbar.vue'], resolve),
                sidebar: resolve => require(['~/components/Sidebar.vue'], resolve)
            },
            meta: {
                title: "View Nilai"
            }
        },
        {
            path: '/admin/nilai/:id/edit',
            components: {
                main: resolve => require(['~/components/bantenprov/nilai/nilai/Nilai.edit.vue'], resolve),
                navbar: resolve => require(['~/components/Navbar.vue'], resolve),
                sidebar: resolve => require(['~/components/Sidebar.vue'], resolve)
            },
            meta: {
                title: "Edit Nilai"
            }
        },
        //...
    ]
},
```
