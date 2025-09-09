<?php

namespace Tests\Feature;

use App\Filament\Resources\CustomerResource\Pages\CreateCustomer;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\CreatesAdminUser;
use Tests\TestCase;

class CustomerResourceAvatarTest extends TestCase
{
    use RefreshDatabase;
    use CreatesAdminUser;

    public function test_admin_can_create_customer_with_avatar(): void
    {
        Storage::fake('public');

        $admin = $this->makeAdmin();
        $this->actingAs($admin, 'admin');

        $file = UploadedFile::fake()->image('avatar.jpg', 200, 200);

        Livewire::test(CreateCustomer::class)
            ->fillForm([
                'name'        => 'Avatar User',
                'email'       => 'avatar@example.com',
                'phone'       => '5455899873',
                'birth_date'  => '1990-01-01',
                'gender'      => 'male',
                'address'     => 'Test Address',
                'avatar_path' => $file,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('customers', ['email' => 'avatar@example.com']);

        $customer = Customer::whereEmail('avatar@example.com')->firstOrFail();
        $this->assertNotEmpty($customer->avatar_path);

        /** @var \Illuminate\Filesystem\AssertableFilesystem $disk */
        $disk = Storage::disk('public');
        $disk->assertExists($customer->avatar_path); // ✅ assertExists kullan
    }

    public function test_model_deletes_old_avatar_when_avatar_path_changes(): void
    {
        Storage::fake('public');

        // Eski avatarı gerçekten oluştur ve kaydet
        $oldFile = UploadedFile::fake()->image('old.jpg', 120, 120);
        $oldPath = Storage::disk('public')->putFile('avatars', $oldFile);

        /** @var \Illuminate\Filesystem\AssertableFilesystem $disk */
        $disk = Storage::disk('public');
        $this->assertTrue($disk->exists($oldPath));

        // Eski avatarla müşteri oluştur
        $customer = Customer::factory()->create([
            'name'        => 'Has Old Avatar',
            'email'       => 'oldavatar@example.com',
            'avatar_path' => $oldPath,
        ]);

        // Yeni avatarı oluştur, yeni path'i yaz ve kaydet
        $newFile = UploadedFile::fake()->image('new.jpg', 200, 200);
        $newPath = Storage::disk('public')->putFile('avatars', $newFile);

        $customer->avatar_path = $newPath;
        $customer->save(); // ->updating kancamız eskiyi silecek

        // Doğrulamalar
        $disk->assertMissing($oldPath);      // eski dosya silinmiş olmalı
        $disk->assertExists($newPath);       // yeni dosya mevcut olmalı
        $this->assertSame($newPath, $customer->fresh()->avatar_path);
    }
}
