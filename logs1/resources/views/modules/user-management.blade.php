@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <h2 class="text-2xl font-bold mb-4">User Management</h2>
        <p>This is the User Management module.</p>
        <!-- Dynamic data from gateway, e.g. -->
        @if(isset($users) && !empty($users))
            <table class="table w-full mt-4">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user['name'] ?? 'N/A' }}</td>
                            <td>{{ $user['email'] ?? 'N/A' }}</td>
                            <td><span class="badge badge-secondary">{{ $user['role'] ?? 'N/A' }}</span></td>
                            <td>
                                <button class="btn btn-sm btn-info">Edit</button>
                                <button class="btn btn-sm btn-error ml-2">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No user data available. Check gateway connection.</p>
        @endif
        <!-- Include module-specific JS if needed -->
        @vite('resources/js/modules/user-management.js')
    </div>
@endsection