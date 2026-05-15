<script>
    import Button from './Button.svelte';

    let {
        open = false,
        title = 'Confirm',
        message = 'Are you sure?',
        confirmLabel = 'Confirm',
        cancelLabel = 'Cancel',
        variant = 'danger',
        loading = false,
        onConfirm = null,
        onCancel = null,
    } = $props();
</script>

{#if open}
    <div class="dialog-backdrop" role="dialog" aria-modal="true">
        <div class="dialog">
            <h3 class="dialog__title">{title}</h3>
            <p class="dialog__message">{message}</p>
            <div class="dialog__actions">
                <Button variant="secondary" onclick={onCancel} disabled={loading}>{cancelLabel}</Button>
                <Button {variant} onclick={onConfirm} {loading}>{confirmLabel}</Button>
            </div>
        </div>
    </div>
{/if}
