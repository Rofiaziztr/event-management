<div class="bg-gradient-to-br from-yellow-50 via-white to-yellow-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">
        @if (session('success'))
            <div class="content-transition">
                <div class="bg-green-50 border border-green-200 rounded-2xl p-4 shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 content-transition">
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
                    <div>
                        <h3 class="text-lg md:text-xl font-bold text-gray-900">Notulensi</h3>
                        <p class="text-xs md:text-sm text-gray-600 mt-1">Dokumentasi hasil dan keputusan acara</p>
                    </div>
                    @php
                        $notulensi = $event->documents->whereNull('file_path')->first();
                        $lastUpdated = $notulensi ? $notulensi->updated_at : null;
                    @endphp
                    @if($lastUpdated)
                        <div class="text-right bg-yellow-50 px-3 py-2 rounded-lg border border-yellow-100">
                            <p class="text-yellow-700 text-xs">Terakhir diperbarui</p>
                            <p class="text-gray-900 text-sm font-medium">{{ $lastUpdated->format('d M Y, H:i') }} WIB</p>
                        </div>
                    @endif
                </div>
            </div>
            <form action="{{ route('admin.events.notulensi.store', $event) }}" method="POST" class="p-6">
                @csrf
                
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex flex-wrap gap-2 mb-4 md:mb-0">
                        <button type="button" onclick="insertTemplate('agenda')" 
                            class="inline-flex items-center px-3 py-2.5 sm:py-2 border border-gray-200 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-gray-50 hover:bg-gray-100 transition-colors duration-200 w-full sm:w-auto justify-center sm:justify-start">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Template Agenda
                        </button>
                        
                        <button type="button" onclick="insertTemplate('keputusan')" 
                            class="inline-flex items-center px-3 py-2.5 sm:py-2 border border-gray-200 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-gray-50 hover:bg-gray-100 transition-colors duration-200 w-full sm:w-auto justify-center sm:justify-start">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Template Keputusan
                        </button>
                        
                        <button type="button" onclick="insertCurrentTime()" 
                            class="inline-flex items-center px-3 py-2.5 sm:py-2 border border-gray-200 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-gray-50 hover:bg-gray-100 transition-colors duration-200 w-full sm:w-auto justify-center sm:justify-start">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Insert Waktu
                        </button>
                    </div>
                    <div class="hidden md:block text-xs text-gray-500 bg-gray-50 p-2 rounded-lg border border-gray-200">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-gray-700">Gunakan template untuk mempercepat penulisan notulensi</span>
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
                    <label for="notulensi_content" class="block text-sm font-medium text-gray-700">
                        Isi Notulensi
                    </label>
                    
                    <div class="border border-gray-300 rounded-lg overflow-hidden">
                        <div class="bg-gray-100 border-b border-gray-300 p-2 flex items-center gap-1 rounded-t-lg overflow-x-auto whitespace-nowrap" style="-webkit-overflow-scrolling: touch;">
                            <div class="flex items-center border-r border-gray-300 pr-2 mr-2">
                                <button type="button" onclick="formatText('bold')" title="Bold"
                                    class="p-2 sm:p-2.5 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded transition-colors duration-150">
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
                                <button type="button" onclick="formatText('justifyRight')" title="Align Right"
                                    class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm4 4a1 1 0 011-1h8a1 1 0 110 2H8a1 1 0 01-1-1zm-4 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm4 4a1 1 0 011-1h8a1 1 0 110 2H8a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <button type="button" onclick="formatText('justifyFull')" title="Justify Full"
                                    class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="flex items-center">
                                <select onchange="formatHeading(this.value)" class="text-sm min-w-[100px] border border-gray-300 rounded-md px-3 py-1.5 sm:py-1.5 bg-white font-medium focus:outline-none focus:ring-1 focus:ring-yellow-400 focus:border-yellow-400">
                                    <option value="">Normal</option>
                                    <option value="h1">Heading 1</option>
                                    <option value="h2">Heading 2</option>
                                    <option value="h3">Heading 3</option>
                                </select>
                            </div>
                        </div>

                        <div id="editor" 
                             contenteditable="true" 
                             class="prose max-w-none p-4 sm:p-5 min-h-[250px] sm:min-h-[400px] focus:outline-none focus:ring-1 focus:ring-yellow-400 focus:border-yellow-400 bg-white rounded-b-lg"
                             style="max-height: 400px; overflow-y: auto; font-size: 0.95rem; line-height: 1.5;">
                            @if($notulensi && $notulensi->content)
                                {!! $notulensi->content !!}
                            @else
                                <p class="text-gray-400 italic">Mulai menulis notulensi di sini...</p>
                            @endif
                        </div>
                    </div>

                    <textarea name="content" id="content" class="hidden">{!! $notulensi && $notulensi->content ? $notulensi->content : '' !!}</textarea>

                    @error('content')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end pt-6">
                    <div class="flex items-center space-x-3 w-full sm:w-auto">
                        <x-bladewind::button can_submit="true" color="yellow" icon="save" class="shadow-md hover:shadow-lg transition-shadow duration-200 w-full sm:w-auto justify-center">
                            Simpan Notulensi
                        </x-bladewind::button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    let editor;

    document.addEventListener('DOMContentLoaded', function() {
        editor = document.getElementById('editor');
        
        if (!editor) {
            console.error('Editor element not found');
            return;
        }
        
        editor.addEventListener('input', function() {
            const content = editor.innerHTML;
            document.getElementById('content').value = content;
        });
        
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const content = editor.innerHTML;
                const placeholderText = '<p class="text-gray-400 italic">Mulai menulis notulensi di sini...</p>';
                
                if (content === placeholderText) {
                    document.getElementById('content').value = '';
                } else {
                    document.getElementById('content').value = content;
                }
            });
        }
    });

    function formatText(command, value = null) {
        document.execCommand(command, false, value);
        editor.focus();
        updateHiddenTextarea();
    }

    function formatHeading(tag) {
        if (tag) {
            document.execCommand('formatBlock', false, tag);
        } else {
            document.execCommand('formatBlock', false, 'p');
        }
        editor.focus();
        updateHiddenTextarea();
    }

    function insertTemplate(type) {
        editor.focus();
        let template = '';
        const currentDate = new Date().toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        if (type === 'agenda') {
            template = `
                <h3>Agenda Acara</h3>
                <ol>
                    <li><strong>Pembukaan</strong> - <em>Waktu: ...</em></li>
                    <li><strong>Laporan Kegiatan</strong> - <em>Oleh: ...</em></li>
                    <li><strong>Pembahasan</strong></li>
                    <li><strong>Keputusan</strong></li>
                    <li><strong>Penutup</strong> - <em>Waktu: ...</em></li>
                </ol>
            `;
        } else if (type === 'keputusan') {
            template = `
                <h3>Keputusan</h3>
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
        editor.focus();
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
        updateHiddenTextarea();
    }

    function updateHiddenTextarea() {
        if (!editor) return;
        
        try {
            const content = editor.innerHTML;
            const placeholderText = '<p class="text-gray-400 italic">Mulai menulis notulensi di sini...</p>';
            document.getElementById('content').value = (content === placeholderText) ? '' : content;
        } catch (error) {
            console.error('Error updating textarea:', error);
        }
    }
    </script>
    @endpush