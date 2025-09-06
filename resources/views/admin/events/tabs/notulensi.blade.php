<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-violet-500 to-violet-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Notulensi Rapat</h3>
                </div>
                
                @php
                    $notulensi = $event->documents->firstWhere('type', 'Notulensi');
                    $lastUpdated = $notulensi ? $notulensi->updated_at : null;
                @endphp
                
                @if($lastUpdated)
                    <div class="text-right">
                        <p class="text-violet-100 text-sm">Terakhir diperbarui</p>
                        <p class="text-white text-sm font-medium">{{ $lastUpdated->format('d M Y, H:i') }}</p>
                    </div>
                @endif
            </div>
            <p class="text-violet-100 text-sm mt-1">Dokumentasi hasil dan keputusan rapat</p>
        </div>

        {{-- Content --}}
        <form action="{{ route('admin.events.notulensi.store', $event) }}" method="POST" class="p-6">
            @csrf
            
            {{-- Quick Action Buttons --}}
            <div class="flex items-center justify-between mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="flex items-center space-x-4">
                    <button type="button" onclick="insertTemplate('agenda')" 
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Template Agenda
                    </button>
                    
                    <button type="button" onclick="insertTemplate('keputusan')" 
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Template Keputusan
                    </button>
                    
                    <button type="button" onclick="insertCurrentTime()" 
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Insert Waktu
                    </button>
                </div>

                <div class="text-sm text-gray-500">
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Gunakan template untuk mempercepat penulisan
                    </span>
                </div>
            </div>

            {{-- Rich Text Editor --}}
            <div class="space-y-4">
                <label for="notulensi_content" class="block text-sm font-medium text-gray-700">
                    Isi Notulensi
                </label>
                
                {{-- Custom Toolbar --}}
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-300 p-2 flex flex-wrap items-center gap-1">
                        {{-- Format Buttons --}}
                        <div class="flex items-center border-r border-gray-300 pr-2 mr-2">
                            <button type="button" onclick="formatText('bold')" title="Bold"
                                class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 4v3h5.5a2 2 0 110 4H5v3h5.5a3.5 3.5 0 100-7H5z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <button type="button" onclick="formatText('italic')" title="Italic"
                                class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 2a1 1 0 000 2h2.071l-4.286 12H4a1 1 0 100 2h8a1 1 0 100-2H9.929l4.286-12H16a1 1 0 100-2H8z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <button type="button" onclick="formatText('underline')" title="Underline"
                                class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 18a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM5 6a5 5 0 1110 0v4a1 1 0 11-2 0V6a3 3 0 10-6 0v4a1 1 0 11-2 0V6z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>

                        {{-- List Buttons --}}
                        <div class="flex items-center border-r border-gray-300 pr-2 mr-2">
                            <button type="button" onclick="formatText('insertOrderedList')" title="Numbered List"
                                class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4h14v2H7V4zm0 6h14v2H7v-2zm0 6h14v2H7v-2zM4 4h1v2H4V4zm0 6h1v2H4v-2zm0 6h1v2H4v-2z"/>
                                </svg>
                            </button>
                            <button type="button" onclick="formatText('insertUnorderedList')" title="Bullet List"
                                class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6h13M8 12h13m-13 6h13M3 6h.01M3 12h.01M3 18h.01"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Alignment Buttons --}}
                        <div class="flex items-center border-r border-gray-300 pr-2 mr-2">
                            <button type="button" onclick="formatText('justifyLeft')" title="Align Left"
                                class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h8a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h8a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <button type="button" onclick="formatText('justifyCenter')" title="Align Center"
                                class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm2 4a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm-2 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm2 4a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Heading Buttons --}}
                        <div class="flex items-center">
                            <select onchange="formatHeading(this.value)" class="text-sm border border-gray-300 rounded px-2 py-1 bg-white">
                                <option value="">Normal</option>
                                <option value="h1">Heading 1</option>
                                <option value="h2">Heading 2</option>
                                <option value="h3">Heading 3</option>
                            </select>
                        </div>
                    </div>

                    {{-- Editor Content Area --}}
                    <div id="editor" 
                         contenteditable="true" 
                         class="prose max-w-none p-4 min-h-[400px] focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2"
                         style="max-height: 600px; overflow-y: auto;">
                        @if($notulensi && $notulensi->content)
                            {!! $notulensi->content !!}
                        @else
                            <p class="text-gray-400 italic">Mulai menulis notulensi rapat di sini...</p>
                        @endif
                    </div>
                </div>

                {{-- Hidden textarea to store content --}}
                <textarea name="content" id="content" class="hidden"></textarea>

                @error('content')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Save Button --}}
            <div class="flex justify-between items-center pt-6">
                <div class="text-sm text-gray-500">
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                        Otomatis disimpan setiap 30 detik
                    </span>
                    <span id="save-status" class="ml-3 text-green-600 font-medium hidden">✓ Tersimpan</span>
                </div>
                
                <div class="flex items-center space-x-3">
                    <button type="button" onclick="previewNotulensi()" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Preview
                    </button>
                    
                    <x-bladewind::button can_submit="true" color="violet" icon="save">
                        Simpan Notulensi
                    </x-bladewind::button>
                </div>
            </div>
        </form>
    </div>

    {{-- Preview Modal --}}
    <div id="preview-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Preview Notulensi</h3>
                    <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div id="preview-content" class="p-6 overflow-y-auto max-h-[60vh] prose max-w-none">
                    <!-- Preview content will be inserted here -->
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let editor;
let autoSaveTimer;

document.addEventListener('DOMContentLoaded', function() {
    editor = document.getElementById('editor');
    
    // Initialize auto-save
    startAutoSave();
    
    // Update hidden textarea when editor content changes
    editor.addEventListener('input', function() {
        document.getElementById('content').value = editor.innerHTML;
        showUnsavedStatus();
    });
    
    // Handle form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        document.getElementById('content').value = editor.innerHTML;
    });
});

function formatText(command, value = null) {
    document.execCommand(command, false, value);
    editor.focus();
}

function formatHeading(tag) {
    if (tag) {
        document.execCommand('formatBlock', false, tag);
    } else {
        document.execCommand('formatBlock', false, 'p');
    }
    editor.focus();
}

function insertTemplate(type) {
    let template = '';
    const currentDate = new Date().toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    if (type === 'agenda') {
        template = `
            <h3>Agenda Rapat</h3>
            <ol>
                <li>Pembukaan</li>
                <li>Laporan Kegiatan</li>
                <li>Pembahasan</li>
                <li>Keputusan</li>
                <li>Penutup</li>
            </ol>
        `;
    } else if (type === 'keputusan') {
        template = `
            <h3>Keputusan Rapat</h3>
            <ol>
                <li><strong>Keputusan 1:</strong> [Isi keputusan]</li>
                <li><strong>Keputusan 2:</strong> [Isi keputusan]</li>
                <li><strong>Tindak Lanjut:</strong> [Isi tindak lanjut]</li>
            </ol>
        `;
    }
    
    insertAtCursor(template);
}

function insertCurrentTime() {
    const now = new Date();
    const timeString = now.toLocaleString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    insertAtCursor(`<p><em>Dicatat pada: ${timeString}</em></p>`);
}

function insertAtCursor(html) {
    const selection = window.getSelection();
    if (selection.rangeCount > 0) {
        const range = selection.getRangeAt(0);
        range.deleteContents();
        const div = document.createElement('div');
        div.innerHTML = html;
        while (div.firstChild) {
            range.insertNode(div.firstChild);
        }
    } else {
        editor.innerHTML += html;
    }
    editor.focus();
    document.getElementById('content').value = editor.innerHTML;
}

function startAutoSave() {
    autoSaveTimer = setInterval(() => {
        autoSave();
    }, 30000); // Auto-save every 30 seconds
}

function autoSave() {
    const content = editor.innerHTML;
    if (content.trim() && content !== '<p class="text-gray-400 italic">Mulai menulis notulensi rapat di sini...</p>') {
        // Here you would typically send an AJAX request to save
        // For now, just show the saved status
        showSavedStatus();
    }
}

function showSavedStatus() {
    const status = document.getElementById('save-status');
    status.textContent = '✓ Tersimpan';
    status.classList.remove('hidden', 'text-orange-600');
    status.classList.add('text-green-600');
}

function showUnsavedStatus() {
    const status = document.getElementById('save-status');
    status.textContent = '● Belum tersimpan';
    status.classList.remove('hidden', 'text-green-600');
    status.classList.add('text-orange-600');
}

function previewNotulensi() {
    const content = editor.innerHTML;
    document.getElementById('preview-content').innerHTML = content;
    document.getElementById('preview-modal').classList.remove('hidden');
}

function closePreview() {
    document.getElementById('preview-modal').classList.add('hidden');
}

// Clean up on page unload
window.addEventListener('beforeunload', function() {
    if (autoSaveTimer) {
        clearInterval(autoSaveTimer);
    }
});
</script>
@endpush