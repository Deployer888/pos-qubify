# Please follow the instructions

`Server Requirements:`
- Php server & CLI version >= 8.2 <br>
- node version >= 18.14


`step 1:` clone this git repository <br>
`step 2:` copy .env.example to .env then set env variables<br>
`step 3:` Configure mail info in .env for email verification <br>
`step 4:` run command <code>composer install</code> <br>
`step 5:` run command <code>npm install & npm run dev</code> <br>
`step 6:` run project from laragon(enable ssl)/localhost <br>
`step 7:` install project</code><br>
`step *:` Check env MIX_ENV_MODE, MIX_ASSET_URL, APP_URL <br>

### For Ready codecanyon production, prduction will run localhost/product_name
1. npm install(If not installed)
2. Change $product_version & VERSION on helper.php, config/app.php
4. `npm run production`
5. cut chunk files from public/public/js/chunks to public/js/chunks(For run project from root directory)
6. Update .env <br>
   APP_URL <br>
   APP_VERSION <br>
   APP_INSTALLED <br>
   APP_PURCHASE_CODE <br>
   MIX_ENV_MODE <br>
   MIX_ASSET_URL <br>
   DB_DATABASE <br>
   DB_USERNAME <br>
   DB_PASSWORD <br>
6. Remove the storage shortcut from public
7. run `php artisan optimize:clear`
8. Remove unnecessary files from the storage
9. Remove node_modules and empty db_backups
10. 


# Authors of this repo

### Regards Team [ITClan BD](https://itclanbd.com)

Dveloped by [Shaikat](https://github.com/zahidhassanshaikot), [Hadi](https://github.com/awalhadi), @mamun88, @pranto_abir, @mirhbrahman, @mosharafhosen90, @main12sani
