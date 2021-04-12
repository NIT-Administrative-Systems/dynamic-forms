<?php

namespace App\Http\Livewire;

use App\Domains\User\ACL\SystemRole;
use App\Domains\User\NetID\SyncUserFromDirectory;
use App\Models\User;
use App\Repositories\UserRepository;
use Livewire\Component;
use Northwestern\SysDev\SOA\DirectorySearch;

class UserForm extends Component
{
    public string $search = '';
    public array $additionalRoles = [];
    public ?User $user = null;
    public ?string $affiliationRole;

    public function mount(?string $search = '')
    {
        $this->search = $search;
    }

    public function render()
    {
        $this->resetValidation();

        if (strlen(trim($this->search)) > 0) {
            $this->lookup();
        }

        return view('livewire.user-form');
    }

    public function save()
    {
        $this->lookup();

        if (! $this->user) {
            return;
        }

        $repo = app()->make(UserRepository::class);
        $user = $repo->saveWithPrimaryAffiliationRole($this->user, $this->affiliationRole, $this->additionalRoles);

        session()->flash('status', sprintf('%s has been updated.', $user->full_name));

        return redirect()->to(route('admin.user.index'));
    }

    protected function lookup(): void
    {
        $origUser = $this->user;

        $this->user = $this->lookupUser();
        $this->affiliationRole = $this->user ? SystemRole::forPrimaryAffiliation($this->user->primary_affiliation) : null;

        if ($origUser?->id != $this->user?->id) {
            $this->additionalRoles = $this->user
                ? $this->user->roles->reject(fn ($role) => in_array($role->name, SystemRole::resetableRoles()))->map->name->all()
                : [];
        }

        if (! $this->user) {
            $this->addError('search', 'Not found in the Northwestern directory');
        }
    }

    protected function lookupUser(): ?User
    {
        $directoryApi = app()->make(DirectorySearch::class);
        $directorySync = app()->make(SyncUserFromDirectory::class);
        $repo = app()->make(UserRepository::class);

        $searchBy = str_contains($this->search, '@') ? 'mail' : 'netid';
        $directoryData = $directoryApi->lookup($this->search, $searchBy, 'basic');

        if (! $directoryData) {
            return null;
        }

        $user = $repo->findByNetid($directoryData['uid']);

        return $directorySync($user, $directoryData);
    }
}
