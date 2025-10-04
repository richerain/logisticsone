@extends('layouts.app')

@section('title', 'PLT Route')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <h2 class="text-2xl font-bold mb-4">Route Planning</h2>
        <p>This is the Route Planning submodule under PLT.</p>
        <!-- Dynamic data from gateway, e.g. -->
        @if(isset($data) && !empty($data))
            <table class="table w-full mt-4">
                <thead>
                    <tr>
                        <th>Route ID</th>
                        <th>Start Point</th>
                        <th>End Point</th>
                        <th>Distance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['routes'] ?? [] as $route)
                        <tr>
                            <td>{{ $route['id'] ?? 'N/A' }}</td>
                            <td>{{ $route['start'] ?? 'N/A' }}</td>
                            <td>{{ $route['end'] ?? 'N/A' }}</td>
                            <td>{{ $route['distance'] ?? 0 }} km</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No route data available. Check gateway connection.</p>
        @endif
        <!-- Include module-specific JS if needed -->
        @vite('resources/js/modules/plt/route.js')
    </div>
@endsection