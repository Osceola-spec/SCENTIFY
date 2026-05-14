@extends('base.base')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Profile Card -->
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">My Profile</h3>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <!-- Profile Picture Section -->
                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                @if($user->profile_picture)
                                    <img src="{{ asset('images/' . $user->profile_picture) }}" 
                                         alt="{{ $user->name }}" 
                                         class="rounded-circle"
                                         style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #007bff;">
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 150px; height: 150px; background-color: #e9ecef; border: 3px solid #007bff;">
                                        <i class="fas fa-user fa-5x text-secondary"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- User Info Section -->
                        <div class="col-md-8">
                            <div class="mb-3">
                                <h5 class="text-muted">Name</h5>
                                <p class="fs-5"><strong>{{ $user->name }}</strong></p>
                            </div>
                            <div class="mb-3">
                                <h5 class="text-muted">Email</h5>
                                <p class="fs-5"><strong>{{ $user->email }}</strong></p>
                            </div>
                            <div class="mb-3">
                                <h5 class="text-muted">Role</h5>
                                <p class="fs-5">
                                    @if($user->role === 'admin')
                                        <span class="badge bg-danger">{{ ucfirst($user->role) }}</span>
                                    @else
                                        <span class="badge bg-success">{{ ucfirst($user->role) }}</span>
                                    @endif
                                </p>
                            </div>
                            @if($user->phone)
                                <div class="mb-3">
                                    <h5 class="text-muted">Phone</h5>
                                    <p class="fs-5"><strong>{{ $user->phone }}</strong></p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Bio Section -->
                    @if($user->bio)
                        <div class="mb-4">
                            <h5 class="text-muted">Bio</h5>
                            <p>{{ $user->bio }}</p>
                        </div>
                    @endif

                    <!-- Member Since -->
                    <div class="mb-4">
                        <h5 class="text-muted">Member Since</h5>
                        <p>{{ $user->created_at->format('d F Y') }}</p>
                    </div>

                    <!-- Edit and Logout Buttons -->
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name', $user->name) }}" required>
                    </div>

                    <!-- Phone -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="{{ old('phone', $user->phone) }}">
                    </div>

                    <!-- Bio -->
                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea class="form-control" id="bio" name="bio" rows="3" 
                                  placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <!-- Profile Picture -->
                    <div class="mb-3">
                        <label for="profile_picture" class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" id="profile_picture" name="profile_picture" 
                               accept="image/*">
                        <small class="text-muted">Max size: 2MB. Formats: JPEG, PNG, JPG, GIF</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
