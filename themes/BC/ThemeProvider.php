<?php
namespace Themes\BC;

use Database\Seeders\DatabaseSeeder;

class ThemeProvider extends \Themes\Base\ThemeProvider
{

    public static $version = '';
    public static $name = 'Travent';
    public static $seeder = DatabaseSeeder::class;
}
