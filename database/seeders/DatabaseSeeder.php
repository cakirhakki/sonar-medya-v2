<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        
        $this->call(SuperAdminRoleSeeder::class);
        $this->call([ServiceCategorySeeder::class]);
        $this->call([ServicesReklamPerformansSeeder::class]);
        $this->call([ServicesReklamPerformansFaqSeeder::class]);
        $this->call([ServicesSiteYonetimiSeeder::class]);
        $this->call([ServicesSiteYonetimiFaqSeeder::class]);
        $this->call([ServicesDanismanlikEgitimSeeder::class]);
        $this->call([ServicesDanismanlikEgitimFaqSeeder::class]);
        $this->call([PackageCategoryIkasKurulumSeeder::class,]);
        $this->call([IkasKurulumPackagesCreateSeeder::class,]);
        $this->call([IkasKurulumPackageItemsSeeder::class,]);
        $this->call([IkasKurulumPackageFaqsSeeder::class,]);
        $this->call([ReklamYonetimiPackageFullSeeder::class,]);
        $this->call([IndependentPackagesFullSeeder::class,]);
        $this->call([SiteSettingSeeder::class,]);
        $this->call([BlogDemoSeeder::class,]);
        $this->call([ServicesDijitalPazarlamaSeeder::class,]);
        $this->call([ServicesDijitalPazarlamaFaqSeeder::class,]);
        $this->call([ServicesEticaretYonetimiSeeder::class,]);
        $this->call([ServicesEticaretYonetimiFaqSeeder::class,]);
        $this->call([ServicesTeknikSeoSeeder::class,]);
        $this->call([ServicesTeknikSeoFaqSeeder::class,]);
        $this->call([ServicesDanismanlikSeeder::class,]);
        $this->call([ServicesDanismanlikFaqSeeder::class,]);
        $this->call([ServicesGorselTasarimSeeder::class,]);
        $this->call([ServicesGorselTasarimFaqSeeder::class,]);
        $this->call([ServicesYazilimMobilSeeder::class,]);
        $this->call([ServicesYazilimMobilFaqSeeder::class,]);
        $this->call([BrandSeeder::class,]);

        $user = User::firstOrCreate(
            ['email' => 'cakirhakki@gmail.com'],
            [
                'name' => 'Hakkı Çakır',
                'phone' => '5551112233',
                'password' => Hash::make('134679.'),
                'email_verified_at' => now(),
            ],
        );
        $user->assignRole('super_admin');

        if (app()->environment('local')) {
            \App\Models\Customer::query()
                ->whereNull('email_verified_at')
                ->update(['email_verified_at' => now()]);
        }
    }
}
