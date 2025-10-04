@extends('layouts.app')

@section('title', 'ALMS Registration')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <h2 class="text-2xl font-bold mb-4">Asset Registration</h2>
        <p>This is the Asset Registration submodule under ALMS.</p>
        <!-- Simple form for new asset -->
        <form class="form-control w-full max-w-xs mt-4">
            <label class="label">
                <span class="label-text">Asset Name</span>
            </label>
            <input type="text" placeholder="Enter asset name" class="input input-bordered w-full" />
            <label class="label">
                <span class="label-text">Serial Number</span>
            </label>
            <input type="text" placeholder="Enter serial" class="input input-bordered w-full" />
            <button class="btn btn-primary mt-4">Register Asset</button>
        </form>
        <!-- Dynamic data from gateway, e.g. -->
        @if(isset($data) && !empty($data))
            <table class="table w-full mt-4">
                <thead>
                    <tr>
                        <th>Asset Name</th>
                        <th>Serial</th>
                        <th>Registration Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['assets'] ?? [] as $asset)
                        <tr>
                            <td>{{ $asset['name'] ?? 'N/A' }}</td>
                            <td>{{ $asset['serial'] ?? 'N/A' }}</td>
                            <td>{{ $asset['registered_at'] ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No asset data available. Check gateway connection.</p>
        @endif
        <!-- Include module-specific JS if needed -->
        @vite('resources/js/modules/alms/registration.js')
    </div>
@endsection