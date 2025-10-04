@extends('layouts.app')

@section('title', 'DTLR Upload')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <h2 class="text-2xl font-bold mb-4">Document Upload</h2>
        <p>This is the Document Upload submodule under DTLR.</p>
        <!-- Upload form -->
        <form class="form-control w-full max-w-md mt-4">
            @csrf
            <div class="flex flex-col">
                <label class="label">
                    <span class="label-text">Document Title</span>
                </label>
                <input type="text" name="title" placeholder="Enter title" class="input input-bordered w-full" required />
            </div>
            <div class="flex flex-col mt-4">
                <label class="label">
                    <span class="label-text">Select File</span>
                </label>
                <input type="file" name="document" class="file-input file-input-bordered w-full" required />
            </div>
            <button type="submit" class="btn btn-primary mt-4">Upload Document</button>
        </form>
        <!-- Dynamic data from gateway, e.g. recent uploads -->
        @if(isset($data) && !empty($data))
            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-2">Recent Uploads</h3>
                <ul class="menu bg-base-100 w-full rounded-box">
                    @foreach($data['recent_uploads'] ?? [] as $upload)
                        <li><a>{{ $upload['title'] ?? 'N/A' }} - {{ $upload['uploaded_at'] ?? 'N/A' }}</a></li>
                    @endforeach
                </ul>
            </div>
        @else
            <p>No upload data available. Check gateway connection.</p>
        @endif
        <!-- Include module-specific JS if needed -->
        @vite('resources/js/modules/dtlr/upload.js')
    </div>
@endsection