<!-- resources/views/components/module-under-construction.blade.php -->
<div class="flex flex-col items-center justify-center h-96">
    <i class='bx bx-hard-hat text-6xl text-yellow-500 mb-4'></i>
    <h2 class="text-2xl font-bold text-gray-700 mb-2">Module Under Construction</h2>
    <p class="text-gray-600 mb-4">The {{ $module ?? 'selected' }} module is currently being developed.</p>
    <button onclick="history.back()" class="btn btn-primary">
        <i class='bx bx-arrow-back mr-2'></i>Go Back
    </button>
</div>