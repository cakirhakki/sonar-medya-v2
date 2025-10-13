<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PackageCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePackageCategoryPolicy
{
    use HandlesAuthorization;

    /** Listeleme (menü + index erişimi) */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_service_package') || $user->can('view_service_package');
    }

    /** Tek kaydı görüntüleme */
    public function view(User $user, PackageCategory $category): bool
    {
        return $user->can('view_service_package') || $this->viewAny($user);
    }

    /** Yeni kategori oluşturma */
    public function create(User $user): bool
    {
        return $user->can('create_service_package') || $user->can('update_service_package');
    }

    /** Güncelleme */
    public function update(User $user, PackageCategory $category): bool
    {
        return $user->can('update_service_package');
    }

    /** Silme */
    public function delete(User $user, PackageCategory $category): bool
    {
        return $user->can('delete_service_package');
    }

    /** Toplu silme */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_service_package') || $user->can('delete_service_package');
    }

    /** Geri yükleme (soft delete) */
    public function restore(User $user, PackageCategory $category): bool
    {
        return $user->can('restore_service_package');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_service_package') || $user->can('restore_service_package');
    }

    /** Kalıcı silme */
    public function forceDelete(User $user, PackageCategory $category): bool
    {
        return $user->can('force_delete_service_package');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_service_package') || $user->can('force_delete_service_package');
    }

    /** Kopyalama */
    public function replicate(User $user, PackageCategory $category): bool
    {
        return $user->can('replicate_service_package') || $user->can('create_service_package');
    }

    /** Sıralama */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_service_package');
    }
}
