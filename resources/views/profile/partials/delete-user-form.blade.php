<section>
    <header>
        <h3>
            <i class="fas fa-trash"></i> Delete Account
        </h3>
        <p style="color: #6c757d; margin: 0.5rem 0 0 0;">Once your account is deleted, there is no going back. Please be certain.</p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" style="margin-top: 1.5rem;">
        @csrf
        @method('delete')

        <button type="button" class="btn btn-danger" data-confirm-btn data-confirm-title="Delete Account" data-confirm-message="Are you sure you want to delete your account? This action cannot be undone.">
            <i class="fas fa-exclamation-triangle"></i> Delete Account
        </button>
    </form>
</section>
