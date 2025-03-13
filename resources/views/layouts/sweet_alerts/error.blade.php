<div class="row">
    @if (session('error'))
        <script>
            // Trigger SweetAlert success message with session success
            Swal.fire({
                title: "{{ session('success') }}",  // Use the session success message
                icon: "error",                    // Set icon to 'success'
                draggable: true,                    // Enable dragging
            });
        </script>
    @endif
  </div>