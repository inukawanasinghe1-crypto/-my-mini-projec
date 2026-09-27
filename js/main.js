/**
 * EduHub — University Study Note & Resource Hub
 * Master Interactive JavaScript (main.js)
 * Rajarata University of Sri Lanka | ICT 2209 Web Technologies
 */

document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    // -------------------------------------------------------------------------
    // 1. Initialize Bootstrap Tooltips
    // -------------------------------------------------------------------------
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // -------------------------------------------------------------------------
    // 2. Auto-Dismiss Flash Alerts
    // -------------------------------------------------------------------------
    const flashAlerts = document.querySelectorAll('.alert-dismissible');
    flashAlerts.forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) {
                bsAlert.close();
            }
        }, 5000);
    });

    // -------------------------------------------------------------------------
    // 3. Smooth Scrolling for Anchor Links
    // -------------------------------------------------------------------------
    document.querySelectorAll('a[href^="#"]:not([href="#"]):not([data-bs-toggle])').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // -------------------------------------------------------------------------
    // 4. Dynamic Live Search & Category Filtering (Dashboard)
    // -------------------------------------------------------------------------
    const searchInput = document.getElementById('resourceSearchInput');
    const filterPills = document.querySelectorAll('.filter-pill-btn');
    const resourceCards = document.querySelectorAll('.resource-item-col');
    const noResultsBox = document.getElementById('noResultsMessage');
    const resourceCounter = document.getElementById('resourceDisplayCount');

    let activeFilter = 'all';
    let searchQuery = '';

    function applyResourceFilters() {
        if (!resourceCards.length) return;

        let visibleCount = 0;
        const query = searchQuery.trim().toLowerCase();

        resourceCards.forEach(col => {
            const subject = (col.getAttribute('data-subject') || '').toLowerCase();
            const category = (col.getAttribute('data-category') || '').toLowerCase();
            const title = (col.getAttribute('data-title') || '').toLowerCase();
            const desc = (col.getAttribute('data-content') || '').toLowerCase();

            // Match Category
            const matchesCategory = (activeFilter === 'all') || 
                                    (category === activeFilter.toLowerCase()) || 
                                    (subject.includes(activeFilter.toLowerCase()));

            // Match Search Query
            const matchesQuery = !query || 
                                 title.includes(query) || 
                                 subject.includes(query) || 
                                 desc.includes(query) || 
                                 category.includes(query);

            if (matchesCategory && matchesQuery) {
                col.style.display = '';
                col.classList.add('animate-fade-in');
                visibleCount++;
            } else {
                col.style.display = 'none';
                col.classList.remove('animate-fade-in');
            }
        });

        // Update empty state display
        if (noResultsBox) {
            noResultsBox.style.display = (visibleCount === 0) ? 'block' : 'none';
        }

        // Update counter badge if available
        if (resourceCounter) {
            resourceCounter.textContent = visibleCount;
        }
    }

    // Search bar input listener
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            searchQuery = this.value;
            applyResourceFilters();
        });

        // Clear search on escape
        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                this.value = '';
                searchQuery = '';
                applyResourceFilters();
            }
        });
    }

    // Category filter pills listener
    if (filterPills.length > 0) {
        filterPills.forEach(btn => {
            btn.addEventListener('click', function () {
                filterPills.forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                activeFilter = this.getAttribute('data-filter') || 'all';
                applyResourceFilters();
            });
        });
    }

    // -------------------------------------------------------------------------
    // 5. Dynamic Modal Note Viewer (Dashboard)
    // -------------------------------------------------------------------------
    const viewNoteModal = document.getElementById('viewNoteModal');
    if (viewNoteModal) {
        viewNoteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const title = button.getAttribute('data-title') || 'Study Note Details';
            const subject = button.getAttribute('data-subject') || '';
            const category = button.getAttribute('data-category') || '';
            const author = button.getAttribute('data-author') || 'Anonymous';
            const date = button.getAttribute('data-date') || '';
            const content = button.getAttribute('data-content') || 'No description provided.';

            document.getElementById('modalNoteTitle').textContent = title;
            document.getElementById('modalNoteSubject').textContent = subject;
            document.getElementById('modalNoteCategory').textContent = category;
            document.getElementById('modalNoteAuthor').textContent = author;
            document.getElementById('modalNoteDate').textContent = date;
            document.getElementById('modalNoteContent').textContent = content;
        });
    }

    // -------------------------------------------------------------------------
    // 6. Client-Side Form Validation: User Registration Form
    // -------------------------------------------------------------------------
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        const usernameInput = document.getElementById('reg_username');
        const emailInput = document.getElementById('reg_email');
        const passwordInput = document.getElementById('reg_password');
        const confirmPasswordInput = document.getElementById('reg_confirm_password');

        function validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function setFieldState(input, isValid, message) {
            const feedbackEl = input.nextElementSibling;
            if (isValid) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
            } else {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                if (feedbackEl && feedbackEl.classList.contains('invalid-feedback')) {
                    feedbackEl.textContent = message;
                }
            }
            return isValid;
        }

        // Live validation listeners
        if (usernameInput) {
            usernameInput.addEventListener('input', function () {
                const val = this.value.trim();
                const isValid = /^[a-zA-Z0-9_]{3,30}$/.test(val);
                setFieldState(this, isValid, 'Username must be 3-30 alphanumeric characters or underscores.');
            });
        }

        if (emailInput) {
            emailInput.addEventListener('input', function () {
                const isValid = validateEmail(this.value.trim());
                setFieldState(this, isValid, 'Please enter a valid university or personal email address.');
            });
        }

        if (passwordInput) {
            passwordInput.addEventListener('input', function () {
                const isValid = this.value.length >= 6;
                setFieldState(this, isValid, 'Password must be at least 6 characters.');
                if (confirmPasswordInput && confirmPasswordInput.value) {
                    const match = confirmPasswordInput.value === this.value;
                    setFieldState(confirmPasswordInput, match, 'Passwords do not match.');
                }
            });
        }

        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', function () {
                const match = this.value === passwordInput.value;
                setFieldState(this, match, 'Passwords do not match.');
            });
        }

        // Form submit listener
        registerForm.addEventListener('submit', function (e) {
            const isUserValid = /^[a-zA-Z0-9_]{3,30}$/.test(usernameInput.value.trim());
            const isEmailValid = validateEmail(emailInput.value.trim());
            const isPassValid = passwordInput.value.length >= 6;
            const isMatch = passwordInput.value === confirmPasswordInput.value;

            setFieldState(usernameInput, isUserValid, 'Username must be 3-30 alphanumeric characters or underscores.');
            setFieldState(emailInput, isEmailValid, 'Please enter a valid email address.');
            setFieldState(passwordInput, isPassValid, 'Password must be at least 6 characters.');
            setFieldState(confirmPasswordInput, isMatch, 'Passwords do not match.');

            if (!isUserValid || !isEmailValid || !isPassValid || !isMatch) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    }

    // -------------------------------------------------------------------------
    // 7. Client-Side Form Validation: Contact Query Form
    // -------------------------------------------------------------------------
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        const nameInput = document.getElementById('contact_name');
        const emailInput = document.getElementById('contact_email');
        const subjectInput = document.getElementById('contact_subject');
        const messageInput = document.getElementById('contact_message');
        const charCounter = document.getElementById('contactCharCount');

        if (messageInput && charCounter) {
            messageInput.addEventListener('input', function () {
                const len = this.value.length;
                charCounter.textContent = `${len} / 1000 characters`;
            });
        }

        contactForm.addEventListener('submit', function (e) {
            let isValid = true;

            // Name validation
            if (!nameInput.value.trim()) {
                nameInput.classList.add('is-invalid');
                isValid = false;
            } else {
                nameInput.classList.remove('is-invalid');
            }

            // Email validation
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailInput.value.trim())) {
                emailInput.classList.add('is-invalid');
                isValid = false;
            } else {
                emailInput.classList.remove('is-invalid');
            }

            // Subject validation
            if (!subjectInput.value.trim()) {
                subjectInput.classList.add('is-invalid');
                isValid = false;
            } else {
                subjectInput.classList.remove('is-invalid');
            }

            // Message validation (min 10 chars)
            if (messageInput.value.trim().length < 10) {
                messageInput.classList.add('is-invalid');
                isValid = false;
            } else {
                messageInput.classList.remove('is-invalid');
            }

            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    }

    // -------------------------------------------------------------------------
    // 8. Client-Side Validation: Add Resource Form (Dashboard)
    // -------------------------------------------------------------------------
    const addResourceForm = document.getElementById('addResourceForm');
    if (addResourceForm) {
        addResourceForm.addEventListener('submit', function (e) {
            const subject = document.getElementById('res_subject');
            const title = document.getElementById('res_title');
            const category = document.getElementById('res_category');
            const desc = document.getElementById('res_description');

            let isValid = true;

            [subject, title, category, desc].forEach(input => {
                if (!input || !input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    }

    // -------------------------------------------------------------------------
    // 9. Quick Copy Note Details to Clipboard
    // -------------------------------------------------------------------------
    const copyBtn = document.getElementById('modalCopyBtn');
    if (copyBtn) {
        copyBtn.addEventListener('click', function () {
            const title = document.getElementById('modalNoteTitle').textContent;
            const content = document.getElementById('modalNoteContent').textContent;
            const subject = document.getElementById('modalNoteSubject').textContent;
            const textToCopy = `[EduHub Note: ${subject} - ${title}]\n\n${content}`;

            navigator.clipboard.writeText(textToCopy).then(() => {
                const originalText = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="bi bi-check2 me-1"></i> Copied!';
                copyBtn.classList.remove('btn-outline-primary');
                copyBtn.classList.add('btn-success');

                setTimeout(() => {
                    copyBtn.innerHTML = originalText;
                    copyBtn.classList.remove('btn-success');
                    copyBtn.classList.add('btn-outline-primary');
                }, 2000);
            });
        });
    }

});
