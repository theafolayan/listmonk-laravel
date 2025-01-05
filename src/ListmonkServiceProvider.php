<?

namespace Theafolayan\ListmonkLaravel;

use Illuminate\Support\ServiceProvider;

class ListmonkServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/listmonk.php', 'listmonk');

        $this->app->singleton('listmonk', function () {
            return new Listmonk();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Config/listmonk.php' => config_path('listmonk.php'),
        ], 'config');
    }
}
