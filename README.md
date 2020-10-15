## Simple mail box

### Stack
- Laravel 
- MySql
- axios

### Deployment
- Install dependencies using `composer install`
- Copy `.env.example` to `.env`
- Setup database and update db credentials in `.env`
- Run Migration `php artisan migrate`
- Run the server `php artisan serve` or create apache virtualhost
- Run the artisan command to fetch emails `php artisan inbox:fetch-emails`

##### Schedule task for fetch email in a regular interval

- Add a cronjob in the server to fetch the emails in a regular interval
- `* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`
- Current task interval is `everyFifteenMinutes` - This can be changed in `app\Console\Kernel.php`

 
