<div class="row">
    <div class="col-md-6">
        <form action="#" method="post" wire:submit.prevent="save">
            @csrf

            <div class="form-group">
                <label for="search">Search by Email or NetID</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="searchIcon">
                            <i class="fas fa-search" aria-hidden="true" title="Search"></i>
                            <span class="sr-only">Search</span>
                        </span>
                    </div>
                    <input wire:model.debounce.350ms="search" type="text" class="form-control @error('search') is-invalid @enderror" name="search" id="search" aria-describedby="searchIcon">
                    @error('search') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

            </div>

            <div class="form-group">
                <label for="additionalRoles">Assign Additional Roles</label>
                <select wire:model.debounce.350ms="additionalRoles" class="form-control" name="additionalRoles" id="additionalRoles" multiple>
                    <option value="platform-administrators">Platform Administrators</option>
                </select>
            </div>

            <div class="form-group">
                <button class="btn btn-block btn-lg btn-primary" type="submit" @unless($user) disabled @endunless >Save</button>
            </div>
        </form>
    </div>
    <div class="col-md-6 @unless ($user) my-auto @endunless">
        @if ($user)
        <dl class="row">
            <dt class="col-sm-6">NetID</dt>
            <dd class="col-sm-6">{{ $user?->username ?? 'None' }}</dd>

            <dt class="col-sm-6">Email</dt>
            <dd class="col-sm-6">{{ $user?->email ?? 'None' }}</dd>

            <dt class="col-sm-6">Name</dt>
            <dd class="col-sm-6">{{ $user?->full_name ?? 'None' }}</dd>

            {{-- @TODO make this restricted by a permission --}}
            <dt class="col-sm-6">Legal Name</dt>
            <dd class="col-sm-6">{{ $user?->full_legal_name ?? 'None' }}</dd>

            <dt class="col-sm-6">Primary Affiliation</dt>
            <dd class="col-sm-6">{{ $user?->primary_affiliation ?? 'None' }}</dd>

            <dt class="col-sm-6">Affiliation Role</dt>
            <dd class="col-sm-6">{{ $affiliationRole ?? 'None' }}</dd>
        </dl>
        @else
            <div class="text-center">
                <h3 class="text-muted">Search for a User</h3>
                <p class="text-muted">Search for a user on the left and their information will show up here!</p>
            </div>
        @endif
    </div>
</div>
