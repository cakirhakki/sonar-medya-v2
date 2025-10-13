<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

// Mevcut modeller/policy'ler
use App\Models\User;
use App\Policies\UserPolicy;
use Spatie\Permission\Models\Role;
use App\Policies\RolePolicy;
use App\Models\Service;
use App\Policies\ServicePolicy;
use App\Models\ServicePackage;
use App\Policies\ServicePackagePolicy;
use App\Models\Customer;
use App\Policies\CustomerPolicy;
use App\Models\PostCategory;
use App\Policies\PostCategoryPolicy;
use App\Models\Post;
use App\Policies\PostPolicy;
use App\Models\Comment;
use App\Policies\CommentPolicy;
use App\Models\ContactMessage;
use App\Policies\ContactMessagePolicy;
use App\Models\PackageCategory;
use App\Policies\ServicePackageCategoryPolicy;
use App\Models\PackageItem;
use App\Policies\PackageItemPolicy;
use App\Models\Permission;
use App\Policies\PermissionPolicy;
use App\Models\ServiceCategory;
use App\Policies\ServiceCategoryPolicy;
use App\Models\ServiceFaq;
use App\Policies\ServiceFaqPolicy;
use App\Models\SiteSetting;
use App\Policies\SiteSettingPolicy;
use App\Models\Brand;
use App\Policies\BrandPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Comment::class => CommentPolicy::class,
        ContactMessage::class => ContactMessagePolicy::class,
        Customer::class => CustomerPolicy::class,

        Permission::class => PermissionPolicy::class,
        Post::class => PostPolicy::class,
        PostCategory::class => PostCategoryPolicy::class,
        Role::class => RolePolicy::class,
        Brand::class => BrandPolicy::class,

        SiteSetting::class => SiteSettingPolicy::class,
        User::class => UserPolicy::class,

        Service::class => ServicePolicy::class,
        ServiceCategory::class => ServicePolicy::class,
        ServiceFaq::class => ServicePolicy::class,
        ServicePackage::class => ServicePolicy::class,
        PackageCategory::class => ServicePolicy::class,
        PackageItem::class => ServicePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // ✅ super_admin (guard=admin) için global bypass
        Gate::before(function ($user, $ability) {
            // Spatie Permission: hasRole($role, $guard = null)
            return $user?->hasRole('super_admin', 'admin') ? true : null;
        });
    }
}
