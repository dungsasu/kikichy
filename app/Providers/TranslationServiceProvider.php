<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Blade;

class TranslationServiceProvider extends ServiceProvider
{
    protected static $customTranslations = [];

    /**
     * Register services.
     *
     * @return void
     */
    public function register() {}
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('translations')) {
            $translations = DB::table('translations')->get();

            $locale = App::getLocale();

            $transArray = [];
            foreach ($translations as $translation) {
                if ($translation->locale === $locale) {
                    $transArray[$translation->key] = $translation->text;
                }
            }
            foreach ($transArray as $key => $value) {

                self::$customTranslations[$locale][$key] = $value;
            }
        }

        Blade::directive('translate', function ($expression) {
            return "<?php echo \App\Providers\TranslationServiceProvider::getCustomTranslation({$expression}); ?>";
        });
    }
    public static function getCustomTranslation($key)
    {
        $locale = App::getLocale();
        return self::$customTranslations[$locale][$key] ?? $key;
    }

    protected static function translate($text)
    {
        return \App\Providers\TranslationServiceProvider::getCustomTranslation($text);
    }
}
