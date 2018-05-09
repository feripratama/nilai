1. Pecah menu Nilai menjadi du bagian, satu untuk admin sekolah satu lagi untuk administrator

-- Menu admin sekolah

```javascript
{
    name: 'Admin Sekolah',
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
````
-- Menu administrator
