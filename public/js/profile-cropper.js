/**
 * Profile Cropper JS Helper for Grow a Garden
 * Automatically uploads and applies cropped avatar instantly upon cropping (1:1 Ratio)
 */

window.ProfileCropper = (function () {
    let cropperInstance = null;
    let currentInput = null;
    let currentPreviewImg = null;
    let currentIconEl = null;
    let scaleX = 1;

    const modal = document.getElementById('profile-cropper-modal');
    const imageEl = document.getElementById('cropper-image');
    const btnClose = document.getElementById('cropper-btn-close');
    const btnCancel = document.getElementById('cropper-btn-cancel');
    const btnApply = document.getElementById('cropper-btn-apply');
    const applyIcon = document.getElementById('cropper-apply-icon');
    const applyText = document.getElementById('cropper-apply-text');
    const backdrop = document.getElementById('cropper-modal-backdrop');

    // Controls
    const btnZoomIn = document.getElementById('crop-zoom-in');
    const btnZoomOut = document.getElementById('crop-zoom-out');
    const btnRotateLeft = document.getElementById('crop-rotate-left');
    const btnRotateRight = document.getElementById('crop-rotate-right');
    const btnFlipX = document.getElementById('crop-flip-x');
    const btnReset = document.getElementById('crop-reset');

    function openModal(imageSrc) {
        if (!modal || !imageEl) return;

        // Reset flip state
        scaleX = 1;

        imageEl.src = imageSrc;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        // Reset apply button state
        if (btnApply) {
            btnApply.disabled = false;
            if (applyIcon) applyIcon.textContent = 'check';
            if (applyText) applyText.textContent = 'Terapkan Foto';
        }

        // Destroy previous instance if any
        if (cropperInstance) {
            cropperInstance.destroy();
            cropperInstance = null;
        }

        // Initialize Cropper.js with 1:1 ratio
        cropperInstance = new Cropper(imageEl, {
            aspectRatio: 1, // 1:1 RATIO
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 0.9,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            preview: '.cropper-preview',
            responsive: true,
            background: true,
        });
    }

    function closeModal() {
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';

        if (cropperInstance) {
            cropperInstance.destroy();
            cropperInstance = null;
        }
        if (imageEl) {
            imageEl.src = '';
        }
        if (currentInput) {
            currentInput.value = '';
        }
    }

    function initEvents() {
        if (btnClose) btnClose.addEventListener('click', closeModal);
        if (btnCancel) btnCancel.addEventListener('click', closeModal);
        if (backdrop) backdrop.addEventListener('click', closeModal);

        // Control Buttons
        if (btnZoomIn) {
            btnZoomIn.addEventListener('click', () => {
                if (cropperInstance) cropperInstance.zoom(0.1);
            });
        }

        if (btnZoomOut) {
            btnZoomOut.addEventListener('click', () => {
                if (cropperInstance) cropperInstance.zoom(-0.1);
            });
        }

        if (btnRotateLeft) {
            btnRotateLeft.addEventListener('click', () => {
                if (cropperInstance) cropperInstance.rotate(-90);
            });
        }

        if (btnRotateRight) {
            btnRotateRight.addEventListener('click', () => {
                if (cropperInstance) cropperInstance.rotate(90);
            });
        }

        if (btnFlipX) {
            btnFlipX.addEventListener('click', () => {
                if (cropperInstance) {
                    scaleX = scaleX === 1 ? -1 : 1;
                    cropperInstance.scaleX(scaleX);
                }
            });
        }

        if (btnReset) {
            btnReset.addEventListener('click', () => {
                if (cropperInstance) {
                    scaleX = 1;
                    cropperInstance.reset();
                }
            });
        }

        // Apply Cropped Image & Auto-Upload Instantly
        if (btnApply) {
            btnApply.addEventListener('click', async () => {
                if (!cropperInstance) return;

                // High quality 512x512 square canvas
                const canvas = cropperInstance.getCroppedCanvas({
                    width: 512,
                    height: 512,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });

                if (!canvas) {
                    if (window.Alert && window.Alert.toast) {
                        window.Alert.toast.error('Gagal memproses potongan gambar.');
                    }
                    return;
                }

                // Show loading state
                btnApply.disabled = true;
                if (applyIcon) applyIcon.textContent = 'sync';
                if (applyIcon) applyIcon.classList.add('animate-spin');
                if (applyText) applyText.textContent = 'Menyimpan...';

                // Convert to Blob and upload immediately
                canvas.toBlob(async (blob) => {
                    if (!blob) {
                        btnApply.disabled = false;
                        if (applyIcon) applyIcon.classList.remove('animate-spin');
                        if (applyIcon) applyIcon.textContent = 'check';
                        if (applyText) applyText.textContent = 'Terapkan Foto';
                        return;
                    }

                    const fileType = blob.type || 'image/webp';
                    const ext = fileType === 'image/webp' ? 'webp' : (fileType === 'image/png' ? 'png' : 'jpg');
                    const fileName = `avatar_${Date.now()}.${ext}`;
                    const croppedFile = new File([blob], fileName, { type: fileType });

                    // Prepare FormData
                    const formData = new FormData();
                    formData.append('avatar', croppedFile);

                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

                    try {
                        const response = await fetch('/settings/avatar', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            const newAvatarUrl = data.avatar_url || canvas.toDataURL(fileType, 0.92);

                            // Update live preview in settings form
                            if (currentPreviewImg) {
                                currentPreviewImg.src = newAvatarUrl;
                                currentPreviewImg.classList.remove('hidden');
                            }
                            if (currentIconEl) {
                                currentIconEl.classList.add('hidden');
                            }

                            // Update header / sidebar profile pictures if available
                            const headerAvatars = document.querySelectorAll('header img[alt="Profile"], header img[alt="Avatar"], nav img[alt="Profile"]');
                            headerAvatars.forEach((img) => {
                                img.src = newAvatarUrl;
                            });

                            closeModal();

                            if (window.Alert && window.Alert.toast) {
                                window.Alert.toast.success(data.message || 'Foto profil berhasil diperbarui!');
                            }
                        } else {
                            throw new Error(data.message || 'Gagal menyimpan foto profil.');
                        }
                    } catch (error) {
                        console.error('Avatar upload error:', error);
                        Alert.toast.error(error.message || 'Terjadi kesalahan saat mengunggah foto profil.');
                    } finally {
                        btnApply.disabled = false;
                        if (applyIcon) applyIcon.classList.remove('animate-spin');
                        if (applyIcon) applyIcon.textContent = 'check';
                        if (applyText) applyText.textContent = 'Terapkan Foto';
                    }
                }, 'image/webp', 0.92);
            });
        }

        // Close on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    }

    /**
     * Attach Cropper handler to a file input element
     * @param {string|HTMLInputElement} inputEl
     * @param {string|HTMLImageElement} previewEl
     * @param {string|HTMLElement} iconEl
     */
    function attach(inputEl, previewEl, iconEl) {
        const input = typeof inputEl === 'string' ? document.getElementById(inputEl) : inputEl;
        const preview = typeof previewEl === 'string' ? document.getElementById(previewEl) : previewEl;
        const icon = typeof iconEl === 'string' ? document.getElementById(iconEl) : iconEl;

        if (!input) return;

        input.addEventListener('change', function (e) {
            const files = e.target.files;
            if (!files || files.length === 0) return;

            const file = files[0];

            // Validate file type
            if (!file.type.match(/^image\/(jpeg|png|jpg|webp)$/i)) {
                Alert.toast.error('Format gambar harus JPG, PNG, atau WEBP.');
                input.value = '';
                return;
            }

            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                Alert.toast.warning('Ukuran file maksimal 2MB.');
                input.value = '';
                return;
            }

            currentInput = input;
            currentPreviewImg = preview;
            currentIconEl = icon;

            const reader = new FileReader();
            reader.onload = function (event) {
                openModal(event.target.result);
            };
            reader.readAsDataURL(file);
        });
    }

    // Auto initialize events when DOM is loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initEvents);
    } else {
        initEvents();
    }

    return {
        attach: attach,
        open: openModal,
        close: closeModal,
    };
})();
