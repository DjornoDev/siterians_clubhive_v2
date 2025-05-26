<script>
    // Current selected club ID
    let currentClubId = null;

    // Toggle functions for each modal
    function toggleViewClubModal() {
        document.getElementById('viewClubModal').classList.toggle('hidden');
    }

    function toggleEditClubModal() {
        const modal = document.getElementById('editClubModal');

        // Check if the modal is currently visible (about to be hidden)
        if (!modal.classList.contains('hidden')) {
            // Reset file inputs when closing the modal
            document.getElementById('editClubLogo').value = '';
            document.getElementById('editClubBanner').value = '';

            // Also hide the preview containers
            document.getElementById('newLogoPreviewContainer').classList.add('hidden');
            document.getElementById('newBannerPreviewContainer').classList.add('hidden');
        }

        // Toggle the modal visibility
        modal.classList.toggle('hidden');
    }    function toggleDeleteClubModal() {
        document.getElementById('deleteClubModal').classList.toggle('hidden');
    }
    
    function togglePasswordVerificationModal() {
        document.getElementById('passwordVerificationModal').classList.toggle('hidden');
        // Clear password field and error when toggling
        document.getElementById('security-password').value = '';
        document.getElementById('password-error').classList.add('hidden');
    }

    // Open edit modal from view modal
    function openEditClubModal() {
        // Hide view modal
        toggleViewClubModal();

        // Get club data from the view modal
        const clubName = document.getElementById('clubNameDisplay').textContent;
        const clubDescription = document.getElementById('clubDescriptionDisplay').textContent;
        const clubAdviser = document.getElementById('clubAdviserDisplay').textContent;
        const clubLogo = document.getElementById('clubLogoImage').src;
        const clubBanner = document.getElementById('clubBannerImage').src;

        // Set data in edit form
        document.getElementById('editClubId').value = currentClubId;
        document.getElementById('editClubName').value = clubName;
        document.getElementById('editClubDescription').value = clubDescription;

        // Set current images
        if (clubLogo && clubLogo !== 'http://' && clubLogo !== 'https://' && clubLogo !== window.location.href) {
            document.getElementById('currentLogoDisplay').src = clubLogo;
            document.getElementById('currentLogoContainer').classList.remove('hidden');
        } else {
            document.getElementById('currentLogoContainer').classList.add('hidden');
        }

        if (clubBanner && clubBanner !== 'http://' && clubBanner !== 'https://' && clubBanner !== window.location
            .href) {
            document.getElementById('currentBannerDisplay').src = clubBanner;
            document.getElementById('currentBannerContainer').classList.remove('hidden');
        } else {
            document.getElementById('currentBannerContainer').classList.add('hidden');
        }

        // Try to select the correct adviser in the dropdown
        const adviserSelect = document.getElementById('editClubAdviser');
        if (adviserSelect) {
            for (let i = 0; i < adviserSelect.options.length; i++) {
                if (adviserSelect.options[i].text === clubAdviser) {
                    adviserSelect.selectedIndex = i;
                    break;
                }
            }
        }

        // Show edit modal
        toggleEditClubModal();
    }

    // Open delete modal from view modal
    function openDeleteClubModal() {
        // Hide view modal
        toggleViewClubModal();

        // Get club name
        const clubName = document.getElementById('clubNameDisplay').textContent;

        // Set club name and ID in delete confirmation
        document.getElementById('deleteClubName').textContent = clubName;
        document.getElementById('deleteClubId').value = currentClubId;

        // Show delete modal
        toggleDeleteClubModal();
    }

    // View club details
    function viewClubDetails(id, name, description, adviser, logo, banner) {
        // Store the current club ID
        currentClubId = id;

        // Set text content
        document.getElementById('clubNameDisplay').textContent = name;
        document.getElementById('clubDescriptionDisplay').textContent = description || 'No description available';
        document.getElementById('clubAdviserDisplay').textContent = adviser;

        // Set images
        const logoImage = document.getElementById('clubLogoImage');
        const logoContainer = document.getElementById('clubLogoContainer');

        if (logo) {
            logoImage.src = logo;
            logoImage.alt = name + ' Logo';
            logoContainer.classList.remove('hidden');
        } else {
            logoContainer.classList.add('hidden');
        }

        const bannerImage = document.getElementById('clubBannerImage');
        const bannerContainer = document.getElementById('clubBannerContainer');

        if (banner) {
            bannerImage.src = banner;
            bannerImage.alt = name + ' Banner';
            bannerContainer.classList.remove('hidden');
        } else {
            bannerContainer.classList.add('hidden');
        }

        // Show the modal
        toggleViewClubModal();
    }

    // Form submission handlers
    document.addEventListener('DOMContentLoaded', function() {
        // Edit club form submission
        const editForm = document.getElementById('editClubForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const clubId = document.getElementById('editClubId').value;
                formData.append('_method', 'PUT'); // Ensure method is correctly set

                // For now, just show an alert
                alert('Update club functionality will be implemented. Club ID: ' + clubId);

                // Comment out until backend is ready

                fetch(`/admin/clubs/${clubId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                        },
                        body: formData
                    })
                    .then(response => {
                        if (response.ok) {
                            window.location.reload();
                        } else {
                            return response.json().then(err => {
                                throw new Error(err.message || 'Failed to update club');
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert(error.message || 'An error occurred while updating the club');
                    });


                toggleEditClubModal();
            });
        }        // Delete club form submission
        const deleteForm = document.getElementById('deleteClubForm');
        if (deleteForm) {
            deleteForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Get club name and ID
                const clubId = document.getElementById('deleteClubId').value;
                const clubName = document.getElementById('deleteClubName').textContent;
                
                // Set the club name in the verification modal
                document.getElementById('verifyDeleteClubName').textContent = clubName;
                
                // Hide delete confirmation modal and show password verification modal
                toggleDeleteClubModal();
                togglePasswordVerificationModal();
            });
        }

        // Close modals when clicking outside
        document.getElementById('viewClubModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });

        document.getElementById('editClubModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });        document.getElementById('deleteClubModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
        
        document.getElementById('passwordVerificationModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
        
        // Add Enter key support for password field
        document.getElementById('security-password')?.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                verifyPasswordAndDeleteClub();
            }
        });
    });
    
    // Function to verify password and proceed with deletion
    function verifyPasswordAndDeleteClub() {
        const password = document.getElementById('security-password').value;
        const clubId = document.getElementById('deleteClubId').value;
        const errorElement = document.getElementById('password-error');
        
        if (!password) {
            errorElement.textContent = 'Password is required';
            errorElement.classList.remove('hidden');
            return;
        }
          // Create form data for verification
        const formData = new FormData();
        formData.append('password', password);
        formData.append('club_id', clubId);
        // Don't need _method: 'DELETE' for a regular POST request
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));        // Make API call to verify password and delete club
        fetch(`/admin/clubs/${clubId}/verify-and-delete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            
            if (response.ok) {
                // Success - reload page
                window.location.reload();
            } else if (response.status === 401) {
                // Password verification failed
                errorElement.textContent = 'Incorrect password. Please try again.';
                errorElement.classList.remove('hidden');
                return Promise.reject('Incorrect password');
            } else {
                return response.json().then(err => {
                    console.error('Error response:', err);
                    throw new Error(err.message || 'Failed to delete club');
                }).catch(err => {
                    // Handle case where response isn't JSON
                    console.error('Response parsing error:', err);
                    throw new Error('Failed to delete club: ' + response.status);
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'An error occurred while deleting the club');
            togglePasswordVerificationModal();
        });
    }
</script>
