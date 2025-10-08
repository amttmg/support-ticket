<div x-data="{ showViewModal: false, selectedFile: null }" class="space-y-2">
    @if ($getState() && $getState()->count())
        <ul class="space-y-1">
            @foreach ($getState() as $file)
                <li class="flex items-center justify-between p-2 border rounded-md">
                    <span class="truncate max-w-[200px] cursor-pointer text-blue-600 hover:underline"
                        @click="selectedFile = '{{ Storage::url($file->file_path) }}'; showViewModal = true">
                        <x-heroicon-o-paper-clip class="inline w-4 h-4 mr-1 text-gray-500" />
                        {{ \Illuminate\Support\Str::afterLast($file->original_name, '/') }}
                    </span>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-sm text-gray-500">No files attached</p>
    @endif

    <div x-data x-show="showViewModal" x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60"
        @click.self="showViewModal = false">
        <div x-show="showViewModal" x-transition.scale
            class="relative bg-white rounded-xl shadow-2xl w-11/12 md:w-3/4 lg:w-1/2 max-h-[90vh] overflow-auto p-4">

            <!-- Close Button -->
            <button @click="showViewModal = false"
                class="absolute text-3xl font-bold text-gray-500 top-3 right-3 hover:text-red-500">&times;</button>

            <!-- File Name -->
            <h4 class="mb-4 text-lg font-semibold text-gray-800 truncate">
                {{ \Illuminate\Support\Str::afterLast($file->file_path, '/') }}
            </h4>

            <!-- File Preview -->
            <div class="flex items-center justify-center">
                <template x-if="selectedFile">
                    <img :src="selectedFile" alt="Preview" style="height: 200px"
                        class="object-contain rounded shadow " />
                </template>
            </div>
        </div>
    </div>
</div>
