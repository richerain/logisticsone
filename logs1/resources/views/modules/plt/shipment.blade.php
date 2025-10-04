@extends('layouts.app')

@section('title', 'PLT Shipment')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <h2 class="text-2xl font-bold mb-4">Shipment Tracking</h2>
        <p>This is the Shipment Tracking submodule under PLT.</p>
        <!-- Dynamic data from gateway, e.g. -->
        @if(isset($data) && !empty($data))
            <table class="table w-full mt-4">
                <thead>
                    <tr>
                        <th>Shipment ID</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['shipments'] ?? [] as $shipment)
                        <tr>
                            <td>{{ $shipment['id'] ?? 'N/A' }}</td>
                            <td>{{ $shipment['from'] ?? 'N/A' }}</td>
                            <td>{{ $shipment['to'] ?? 'N/A' }}</td>
                            <td><span class="badge {{ $shipment['status'] === 'in-transit' ? 'badge-warning' : 'badge-success' }}">{{ $shipment['status'] ?? 'N/A' }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No shipment data available. Check gateway connection.</p>
        @endif
        <!-- Include module-specific JS if needed -->
        @vite('resources/js/modules/plt/shipment.js')
    </div>
@endsection