@extends('layouts.app')

@section('title', 'DTLR Logs')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <h2 class="text-2xl font-bold mb-4">Tracking Logs</h2>
        <p>This is the Tracking Logs submodule under DTLR.</p>
        <!-- Dynamic data from gateway, e.g. -->
        @if(isset($data) && !empty($data))
            <table class="table w-full mt-4">
                <thead>
                    <tr>
                        <th>Log ID</th>
                        <th>Event</th>
                        <th>Timestamp</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['logs'] ?? [] as $log)
                        <tr>
                            <td>{{ $log['id'] ?? 'N/A' }}</td>
                            <td>{{ $log['event'] ?? 'N/A' }}</td>
                            <td>{{ $log['timestamp'] ?? 'N/A' }}</td>
                            <td>{{ $log['user'] ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No log data available. Check gateway connection.</p>
        @endif
        <!-- Include module-specific JS if needed -->
        @vite('resources/js/modules/dtlr/logs.js')
    </div>
@endsection