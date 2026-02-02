<x-typing-app :role="'student'" :title="'Online Document Editor - ' . $assignment->title">

    <div class="h-[calc(100vh-100px)] flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <a href="{{ route('typing.student.upload', $assignment->id) }}" class="btn-ghost">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-file-word text-blue-600"></i>
                        {{ $assignment->title }}
                    </h1>
                    <p class="text-xs text-gray-500">Document Editor Mode</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="saveDocument()" class="btn-primary" id="save-btn">
                    <i class="fas fa-save mr-2"></i> บันทึกและส่งงาน
                </button>
            </div>
        </div>

        <div class="flex flex-1 gap-6 overflow-hidden">
            <!-- Left: Instruction/Preview (Collapsible) -->
            @if($assignment->master_file_path)
                <div class="w-1/3 flex flex-col bg-gray-100 rounded-xl border border-gray-200 overflow-hidden"
                    x-data="{ expanded: true }" :class="text-xs">
                    <div class="p-3 bg-gray-200 border-b border-gray-300 flex justify-between items-center">
                        <span class="font-semibold text-gray-700"><i class="fas fa-file-alt mr-2"></i>โจทย์ต้นฉบับ</span>
                        <a href="{{ asset($assignment->master_file_path) }}" download
                            class="text-blue-600 hover:text-blue-800 text-xs">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                    <!-- DOCX Preview -->
                    <div id="docx-container" class="flex-1 overflow-auto bg-gray-50 p-2 relative">
                        <div id="loading-preview" class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-spinner fa-spin text-gray-400"></i>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Right: Editor -->
            <div
                class="flex-1 bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col overflow-hidden relative">
                <div id="editor-container" class="flex-1 overflow-auto bg-gray-100 p-8 flex justify-center">
                    <!-- Paper -->
                    <div class="bg-white shadow-lg w-[21cm] min-h-[29.7cm] p-[2.5cm] text-black">
                        <div id="editor">
                            {!! $existingSubmission && Str::endsWith($existingSubmission->file_name, '.html') && file_exists(public_path($existingSubmission->file_path)) ? file_get_contents(public_path($existingSubmission->file_path)) : '<p>เริ่มพิมพ์เอกสารที่นี่...</p>' !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/jszip/dist/jszip.min.js"></script>
    <script src="https://unpkg.com/docx-preview/dist/docx-preview.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/decoupled-document/ckeditor.js"></script>

    <script>
        let editorInstance;

        // Initialize Editor
        DecoupledEditor
            .create(document.querySelector('#editor'), {
                toolbar: {
                    items: [
                        'heading', '|',
                        'fontFamily', 'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'alignment', '|',
                        'numberedList', 'bulletedList', '|',
                        'outdent', 'indent', '|',
                        'link', 'blockQuote', 'insertTable', '|',
                        'undo', 'redo'
                    ]
                },
                fontFamily: {
                    options: [
                        'default',
                        'Sarabun, sans-serif',
                        'Courier New, Courier, monospace',
                        'Arial, Helvetica, sans-serif',
                        'Times New Roman, Times, serif'
                    ],
                    supportAllValues: true
                },
                fontSize: {
                    options: [10, 12, 14, 16, 18, 20, 22, 24, 26, 28, 30, 36]
                }
            })
            .then(editor => {
                const toolbarContainer = document.querySelector('#editor-container');
                // Add Toolbar to top of container
                const toolbar = editor.ui.view.toolbar.element;
                toolbar.classList.add('sticky', 'top-0', 'z-10', 'bg-white', 'border-b', 'border-gray-200', 'p-2', 'flex', 'flex-wrap', 'justify-center', 'shadow-sm', 'w-full', 'mb-4', 'rounded-t-xl');

                // We need to insert the toolbar before the paper container
                document.querySelector('#editor-container').parentElement.insertBefore(toolbar, document.querySelector('#editor-container'));

                editorInstance = editor;
            })
            .catch(error => {
                console.error(error);
            });

        // Render DOCX if exists
        @if($assignment->master_file_path)
            document.addEventListener('DOMContentLoaded', function () {
                const docData = "{{ asset($assignment->master_file_path) }}";
                const container = document.getElementById('docx-container');
                const loading = document.getElementById('loading-preview');

                fetch(docData)
                    .then(response => response.blob())
                    .then(blob => {
                        loading.style.display = 'none';
                        docx.renderAsync(blob, container, container, {
                            className: "docx",
                            inWrapper: true,
                            ignoreWidth: false,
                            ignoreHeight: false,
                            breakPages: false
                        });
                    })
                    .catch(err => {
                        loading.style.display = 'none';
                        container.innerHTML = '<div class="text-center p-4">Failed to load preview</div>';
                    });
            });
        @endif

        // Save Function
        function saveDocument() {
            const content = editorInstance.getData();
            const btn = document.getElementById('save-btn');

            // Disable button
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> กำลังบันทึก...';

            fetch("{{ route('typing.student.editor.submit', $assignment->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({ content: content })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ',
                            text: 'งานของคุณถูกส่งเรียบร้อยแล้ว',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = data.redirect;
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ผิดพลาด',
                            text: data.message || 'ไม่สามารถบันทึกได้'
                        });
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-save mr-2"></i> บันทึกและส่งงาน';
                    }
                })
                .catch(err => {
                    console.error(err);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-save mr-2"></i> บันทึกและส่งงาน';
                    Swal.fire({
                        icon: 'error',
                        title: 'System Error',
                        text: 'Connection failed'
                    });
                });
        }
    </script>

    <style>
        /* Editor Paper Styles */
        .ck.ck-editor__editable_inline {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            outline: none !important;
        }

        /* Make toolbar sticky properly */
        .ck-toolbar {
            width: 100%;
        }
    </style>
</x-typing-app>