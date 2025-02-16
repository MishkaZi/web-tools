class ToolManager {
    constructor() {
        this.modal = new ModalManager();
        this.dropzone = null;
        this.baseUrl = document.querySelector('meta[name="base-url"]')?.content || '/web-tools';
        this.init();
    }

    init() {
        document.getElementById('addToolButton')?.addEventListener('click', () => this.showUploadModal());

        // Delete Tool button handlers
        document.querySelectorAll('.delete-tool').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.handleDeleteTool(e.target.closest('.delete-tool'));
            });
        });
    }

    showUploadModal() {
        const content = `
            <div class='mb-4'>
                <label class='block text-gray-700 text-sm font-bold mb-2' for='toolName'>
                    Tool Name
                </label>
                <input type='text' id='toolName' 
                    class='shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'
                    placeholder='Enter tool name'>
            </div>
            
            <div class='dropzone mb-4' id='toolFilesUpload'>
                <div class='dz-message' data-dz-message>
                    <span>Drop files here or click to upload</span>
                    <p class='text-sm text-gray-500 mt-1'>Maximum file size: 5MB</p>
                </div>
            </div>
        `;

        this.modal.show(ModalManager.createContent({
            title: 'Add New Tool',
            content: content
        }));

        this.initializeDropzone();
    }

    initializeDropzone() {
        if (this.dropzone) {
            this.dropzone.destroy();
        }

        const dropzoneOptions = {
            url: `${this.baseUrl}/api/tool-upload`,  // Updated URL
            autoProcessQueue: false,
            maxFilesize: 5,
            parallelUploads: 10,  // Allow multiple files to be uploaded simultaneously
            uploadMultiple: true,  // Enable uploading multiple files in one request
            addRemoveLinks: false,  // Remove the default "Remove file" link

            previewTemplate: `
                <div class="p-2">
                    <div class="flex items-center gap-2">
                        <span class="text-sm" data-dz-name></span>
                        <span class="text-xs text-gray-500" data-dz-size></span>
                        <button class="text-red-500 hover:text-red-700 ml-auto text-sm" data-dz-remove>Remove</button>
                    </div>
                    <div class="text-red-500 text-sm mt-1" data-dz-errormessage></div>
                </div>
            `,
            init: function () {
                // Add permanent upload button after Dropzone is initialized
                const uploadButton = document.createElement('button');
                uploadButton.className = 'mt-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded w-full';
                uploadButton.textContent = 'Upload Tool';
                this.element.appendChild(uploadButton);

                uploadButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    const toolName = document.getElementById('toolName').value.trim();

                    if (!toolName) {
                        alert('Please enter a tool name');
                        return;
                    }

                    this.processQueue();
                });

                this.on('sending', (file, xhr, formData) => {
                    const toolName = document.getElementById('toolName').value;
                    formData.append('toolName', toolName);
                });

                this.on('success', (file, response) => {
                    if (response.files && response.files.length > 0) {
                        console.log('Uploaded files:', response.files);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        console.error('Unexpected server response:', response);
                    }
                });

                this.on('error', (file, message) => {
                    if (message.includes('404 Not Found')) {
                        const errorMessage = 'The upload functionality is not available at the moment. Please try again later.';
                        const errorElement = file.previewElement.querySelector('[data-dz-errormessage]');
                        errorElement.textContent = errorMessage;
                    } else {
                        console.error('Upload error:', message);
                    }
                });
            }
        };

        this.dropzone = new Dropzone('#toolFilesUpload', dropzoneOptions);
    }


    async handleDeleteTool(button) {
        const toolName = button.dataset.toolName;
        const toolDisplayName = toolName.split('-').map(word =>
            word.charAt(0).toUpperCase() + word.slice(1)
        ).join(' ');

        // Show confirmation modal
        this.modal.show(ModalManager.createContent({
            title: 'Delete Tool',
            content: `
                <div class='mb-6'>
                    <p class='text-gray-700 mb-4'>Are you sure you want to delete "${toolDisplayName}"?</p>
                    <p class='text-red-600 text-sm'>This action cannot be undone.</p>
                </div>
                <div class='flex justify-end gap-4'>
                    <button class='px-4 py-2 text-gray-600 hover:text-gray-800 modal-close'>
                        Cancel
                    </button>
                    <button class='px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 confirm-delete'>
                        Delete Tool
                    </button>
                </div>
            `
        }));

        // Add event listener for delete confirmation
        document.querySelector('.confirm-delete').addEventListener('click', async () => {
            try {
                const formData = new FormData();
                formData.append('toolName', toolName);

                const response = await fetch(`${this.baseUrl}/api/tool-delete`, {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();

                if (result.success) {
                    // Close modal and reload page
                    this.modal.close();
                    location.reload();
                } else {
                    throw new Error(result.error || 'Failed to delete tool');
                }
            } catch (error) {
                console.error('Delete error:', error);
                alert('Failed to delete tool: ' + error.message);
            }
        });
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    new ToolManager();
});