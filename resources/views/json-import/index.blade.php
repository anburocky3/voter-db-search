<x-layouts.auth.simple>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('JSON Import Tool') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form id="import-form" class="space-y-6">
                        @csrf
                        <div>
                            <label for="directory_path" class="block text-sm font-medium text-gray-700">
                                JSON Files Directory Path
                            </label>
                            <input type="text"
                                   id="directory_path"
                                   name="directory_path"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                   placeholder="/path/to/your/json/files"
                                   value="{{ old('directory_path') }}"
                                   required>
                        </div>

                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Start Import
                        </button>
                    </form>

                    <div id="import-status" class="mt-6 hidden">
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex">
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">
                                        Import in Progress...
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>Processing JSON files. Please wait...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="import-results" class="mt-6 hidden"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('import-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const statusDiv = document.getElementById('import-status');
            const resultsDiv = document.getElementById('import-results');

            statusDiv.classList.remove('hidden');
            resultsDiv.classList.add('hidden');

            try {
                const response = await fetch('{{ route("json-import.process") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
                    }
                });

                const result = await response.json();

                statusDiv.classList.add('hidden');
                resultsDiv.classList.remove('hidden');

                if (result.success) {
                    resultsDiv.innerHTML = `
                        <div class="bg-green-50 border border-green-200 rounded-md p-4">
                            <h3 class="text-sm font-medium text-green-800">Import Completed Successfully!</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>Total Files: ${result.data.total_files}</p>
                                <p>Imported Files: ${result.data.imported_files}</p>
                                <p>Total Records: ${result.data.total_records}</p>
                                <p>Errors: ${result.data.errors.length}</p>
                            </div>
                        </div>
                    `;
                } else {
                    resultsDiv.innerHTML = `
                        <div class="bg-red-50 border border-red-200 rounded-md p-4">
                            <h3 class="text-sm font-medium text-red-800">Import Failed</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>${result.message}</p>
                            </div>
                        </div>
                    `;
                }
            } catch (error) {
                statusDiv.classList.add('hidden');
                resultsDiv.classList.remove('hidden');
                resultsDiv.innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-md p-4">
                        <h3 class="text-sm font-medium text-red-800">Error</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>An unexpected error occurred: ${error.message}</p>
                        </div>
                    </div>
                `;
            }
        });
    </script>
</x-layouts.auth.simple>
