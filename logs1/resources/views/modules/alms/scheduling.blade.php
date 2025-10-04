@extends('layouts.app')

@section('title', 'ALMS Scheduling')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <h2 class="text-2xl font-bold mb-4">Maintenance Scheduling</h2>
        <p>This is the Maintenance Scheduling submodule under ALMS.</p>
        <!-- Dynamic data from gateway, e.g. -->
        @if(isset($data) && !empty($data))
            <table class="table w-full mt-4">
                <thead>
                    <tr>
                        <th>Asset</th>
                        <th>Scheduled Date</th>
                        <th>Technician</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['schedules'] ?? [] as $schedule)
                        <tr>
                            <td>{{ $schedule['asset'] ?? 'N/A' }}</td>
                            <td>{{ $schedule['date'] ?? 'N/A' }}</td>
                            <td>{{ $schedule['technician'] ?? 'N/A' }}</td>
                            <td><span class="badge {{ $schedule['status'] === 'scheduled' ? 'badge-info' : 'badge-success' }}">{{ $schedule['status'] ?? 'N/A' }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No schedule data available. Check gateway connection.</p>
        @endif
        <!-- Include module-specific JS if needed (e.g., for calendar) -->
        @vite('resources/js/modules/alms/scheduling.js')
    </div>
@endsection