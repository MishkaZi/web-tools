class ModalManager {
    constructor() {
        this.container = document.getElementById('modalContainer');
        this.backdrop = document.getElementById('modalBackdrop');
        this.content = document.getElementById('modalContent');
        
        if (!this.container || !this.backdrop || !this.content) {
            console.error('Modal elements not found!');
            return;
        }
        
        this.handleBackdropClick = this.handleBackdropClick.bind(this);
        this.close = this.close.bind(this);
        this.init();
    }

    init() {
        // Close on backdrop click
        this.backdrop.addEventListener('click', this.handleBackdropClick);
        
        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') this.close();
        });
    }

    show(content) {
        this.content.innerHTML = content;
        this.container.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Add event listener to close button after content is added
        const closeButton = this.content.querySelector('.modal-close');
        if (closeButton) {
            closeButton.addEventListener('click', this.close);
        }
    }

    close() {
        this.container.classList.add('hidden');
        this.content.innerHTML = '';
        document.body.style.overflow = '';
    }

    handleBackdropClick(e) {
        if (e.target === this.backdrop) {
            this.close();
        }
    }

    static createContent({ title, content, showClose = true }) {
        return `
            <div class='p-6'>
                <div class='flex justify-between items-center mb-4'>
                    <h2 class='text-2xl font-bold text-gray-800'>${title}</h2>
                    ${showClose ? `
                        <button type='button' class='text-gray-500 hover:text-gray-700 modal-close'>
                            <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'></path>
                            </svg>
                        </button>
                    ` : ''}
                </div>
                <div class='modal-body'>${content}</div>
            </div>
        `;
    }
}

window.ModalManager = ModalManager;