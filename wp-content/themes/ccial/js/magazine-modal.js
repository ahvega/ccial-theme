/**
 * Magazine Modal Frontend JavaScript
 * 
 * Handles click interception, attachment ID resolution, AJAX calls,
 * and modal integration for magazine archive functionality.
 * 
 * @package CCI AL
 * @version 1.0.0
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Magazine Modal Handler
     */
    var MagazineModal = {

        /**
         * Initialize the magazine modal functionality
         */
        init: function() {
            this.bindEvents();
            this.ensureModalExists();
        },

        /**
         * Bind event handlers
         */
        bindEvents: function() {
            var self = this;

            // Use event delegation to catch clicks on magazine-modal:// links
            $(document).on('click', 'a[href^="magazine-modal://"]', function(e) {
                e.preventDefault();
                self.handleModalClick($(this));
            });

            // Listen for Avada modal show events
            $(document).on('show.bs.modal', '.fusion-modal.magazine', function(e) {
                self.onModalShow($(this));
            });

            // Listen for Avada modal hide events
            $(document).on('hide.bs.modal', '.fusion-modal.magazine', function(e) {
                self.onModalHide($(this));
            });

            // Handle modal close events
            $(document).on('click', '.fusion-modal.magazine .close, .fusion-modal.magazine .btn-close', function(e) {
                self.closeModal();
            });

            // Handle ESC key
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27 && self.isModalOpen()) {
                    self.closeModal();
                }
            });
        },

        /**
         * Handle click on modalview link
         * 
         * @param {jQuery} $link The clicked link element
         */
        handleModalClick: function($link) {
            var href = $link.attr('href');
            var attachmentId = this.resolveAttachmentId(href, $link);

            // Debug: console.log('Modal click - href:', href);
            // Debug: console.log('Modal click - resolved attachment ID:', attachmentId);

            if (!attachmentId) {
                this.showError('Could not determine magazine issue.');
                return;
            }

            // Check if modal exists before proceeding
            var $modal = $('.fusion-modal.magazine');
            if ($modal.length === 0) {
                console.error('Magazine modal not found. Please create a modal element named "magazine" in Avada.');
                return;
            }

            // Store the attachment ID for the modal show event
            this.currentAttachmentId = attachmentId;

            // Convert the link to proper Avada modal format and trigger click
            this.convertToModalLink($link);
        },

        /**
         * Resolve attachment ID from href or image element
         * 
         * @param {string} href The href value
         * @param {jQuery} $link The link element
         * @return {number|null} Attachment ID or null
         */
        resolveAttachmentId: function(href, $link) {
            // Extract ID from href if present
            var match = href.match(/magazine-modal:\/\/(\d+)/);
            if (match && match[1]) {
                return parseInt(match[1], 10);
            }

            // If auto, try to get from clicked image element
            var $img = $link.find('img').first();
            if ($img.length === 0) {
                // If no img in link, try to find the associated image
                $img = $link.siblings('img').first();
            }

            if ($img.length > 0) {
                // Try data-attachment-id attribute on image
                var imgDataId = $img.attr('data-attachment-id');
                if (imgDataId) {
                    return parseInt(imgDataId, 10);
                }

                // Try to extract from image src
                var src = $img.attr('src');
                if (src) {
                    return this.getAttachmentIdFromUrl(src);
                }
            }

            return null;
        },

        /**
         * Convert link to proper Avada modal format and trigger modal
         * 
         * @param {jQuery} $link The link element
         */
        convertToModalLink: function($link) {
            // Convert the link to proper Avada modal format
            $link.removeClass().addClass('fusion-modal-text-link');
            $link.attr('data-toggle', 'modal');
            $link.attr('data-target', '.fusion-modal.magazine');
            $link.attr('href', '#');

            // Directly trigger the Avada modal
            var $modal = $('.fusion-modal.magazine');
            if ($modal.length > 0) {
                $modal.modal('show');
            }
        },

        /**
         * Get attachment ID from image URL
         * 
         * @param {string} url Image URL
         * @return {number|null} Attachment ID or null
         */
        getAttachmentIdFromUrl: function(url) {
            // Try to extract ID from URL patterns
            var patterns = [
                /\/wp-content\/uploads\/\d{4}\/\d{2}\/([^\/]+)-(\d+)x\d+\./,
                /\/wp-content\/uploads\/\d{4}\/\d{2}\/([^\/]+)-(\d+)\./,
                /attachment_id=(\d+)/,
                /\/attachment\/(\d+)\//
            ];

            for (var i = 0; i < patterns.length; i++) {
                var match = url.match(patterns[i]);
                if (match && match[1]) {
                    return parseInt(match[1], 10);
                }
            }

            return null;
        },

        /**
         * Handle modal show event
         * 
         * @param {jQuery} $modal The modal element
         */
        onModalShow: function($modal) {
            var self = this;

            // Debug: console.log('Modal show event - currentAttachmentId:', this.currentAttachmentId);

            if (!this.currentAttachmentId) {
                this.showError($modal, 'No magazine issue selected.');
                return;
            }

            // Show loading state
            this.showLoading($modal);

            // Make AJAX request
            $.ajax({
                url: ccialMagazineModal.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'get_magazine_embed',
                    attachment_id: this.currentAttachmentId,
                    nonce: ccialMagazineModal.nonce
                },
                success: function(response) {
                    // Debug: console.log('AJAX Response:', response);
                    if (response.success) {
                        self.showMagazineEmbed($modal, response.data.embed_html, response.data.formatted_title);
                    } else {
                        self.showError($modal, response.data || 'Failed to load magazine embed.');
                    }
                },
                error: function(xhr, status, error) {
                    self.showError($modal, 'Network error: ' + error);
                }
            });
        },

        /**
         * Handle modal hide event
         * 
         * @param {jQuery} $modal The modal element
         */
        onModalHide: function($modal) {
            // Clear content to stop any playing media
            this.clearModalContent($modal);

            // Reset current attachment ID
            this.currentAttachmentId = null;
        },

        /**
         * Load magazine embed via AJAX
         * 
         * @param {number} attachmentId Attachment ID
         */
        loadMagazineEmbed: function(attachmentId) {
            var self = this;

            // Show loading state
            this.showLoading();

            // Make AJAX request
            $.ajax({
                url: ccialMagazineModal.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'get_magazine_embed',
                    attachment_id: attachmentId,
                    nonce: ccialMagazineModal.nonce
                },
                success: function(response) {
                    if (response.success) {
                        self.showMagazineEmbed(response.data.embed_html, response.data.formatted_title);
                    } else {
                        self.showError(response.data || 'Failed to load magazine embed.');
                    }
                },
                error: function(xhr, status, error) {
                    self.showError('Network error: ' + error);
                }
            });
        },

        /**
         * Show magazine embed in modal
         * 
         * @param {jQuery} $modal The modal element
         * @param {string} embedHtml The embed HTML
         * @param {string} title The attachment title
         */
        showMagazineEmbed: function($modal, embedHtml, title) {
            var $modalBody = $modal.find('.modal-body');
            var $target = $modal.find('#magazine-modal-target');

            // Clear previous content
            this.clearModalContent($modal);

            // Set title if modal has title element
            var $title = $modal.find('.modal-title, .modal-header h3, .modal-header h4');
            if ($title.length > 0) {
                $title.text(title || 'Magazine Viewer');
            }

            // Process the embed HTML to make it responsive
            var processedHtml = this.makeEmbedResponsive(embedHtml);

            // Debug: Log the processed HTML
            // console.log('Original embed HTML:', embedHtml);
            // console.log('Processed embed HTML:', processedHtml);

            // Insert embed HTML
            if ($target.length > 0) {
                $target.html(processedHtml);
            } else {
                $modalBody.html('<div id="magazine-modal-target">' + processedHtml + '</div>');
            }
        },

        /**
         * Show loading state
         * 
         * @param {jQuery} $modal The modal element
         */
        showLoading: function($modal) {
            var $modalBody = $modal.find('.modal-body');

            this.clearModalContent($modal);

            var loadingHtml = '<div class="magazine-loading text-center">' +
                '<div class="spinner-border" role="status">' +
                '<span class="sr-only">Loading...</span>' +
                '</div>' +
                '<p class="mt-3">Loading magazine...</p>' +
                '</div>';

            if ($modalBody.length > 0) {
                $modalBody.html(loadingHtml);
            }
        },

        /**
         * Show error message
         * 
         * @param {jQuery} $modal The modal element
         * @param {string} message Error message
         */
        showError: function($modal, message) {
            var $modalBody = $modal.find('.modal-body');

            this.clearModalContent($modal);

            var errorHtml = '<div class="magazine-error text-center">' +
                '<div class="alert alert-danger">' +
                '<h4>Error</h4>' +
                '<p>' + this.escapeHtml(message) + '</p>' +
                '</div>' +
                '</div>';

            if ($modalBody.length > 0) {
                $modalBody.html(errorHtml);
            }
        },

        /**
         * Clear modal content
         * 
         * @param {jQuery} $modal The modal element
         */
        clearModalContent: function($modal) {
            var $target = $modal.find('#magazine-modal-target');
            var $modalBody = $modal.find('.modal-body');

            if ($target.length > 0) {
                $target.empty();
            } else if ($modalBody.length > 0) {
                $modalBody.empty();
            }
        },

        /**
         * Close the modal
         */
        closeModal: function() {
            var $modal = $('.fusion-modal.magazine');

            if ($modal.length > 0) {
                // Use Avada's modal closing method
                $modal.modal('hide');
            }
        },

        /**
         * Check if modal is open
         * 
         * @return {boolean} True if modal is open
         */
        isModalOpen: function() {
            var $modal = $('.fusion-modal.magazine');
            return $modal.length > 0 && $modal.hasClass('in');
        },

        /**
         * Ensure modal exists in DOM
         */
        ensureModalExists: function() {
            var $modal = $('.fusion-modal.magazine');

            if ($modal.length === 0) {
                // Only show warning if we're on a page that should have the modal
                // Check if there are any magazine-modal:// links on the page
                var $modalLinks = $('a[href^="magazine-modal://"]');
                if ($modalLinks.length > 0) {
                    console.warn('Magazine modal not found. Please create a modal element named "magazine" in Avada.');
                }
            }
        },

        /**
         * Make Calameo embed responsive
         * 
         * @param {string} embedHtml The original embed HTML
         * @return {string} Processed HTML
         */
        makeEmbedResponsive: function(embedHtml) {
            // Since Calameo now generates width="100%" height="100%", 
            // we don't need to modify the iframe attributes
            // Just return the HTML as-is and let CSS handle the styling
            return embedHtml;
        },

        /**
         * Escape HTML to prevent XSS
         * 
         * @param {string} text Text to escape
         * @return {string} Escaped text
         */
        escapeHtml: function(text) {
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };

            return text.replace(/[&<>"']/g, function(m) {
                return map[m];
            });
        }
    };

    /**
     * Initialize when document is ready
     */
    $(document).ready(function() {
        MagazineModal.init();
    });

})(jQuery);