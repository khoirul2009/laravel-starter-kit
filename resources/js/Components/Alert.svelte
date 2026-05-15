<script>
    let { type = 'success', message = '', onclose = null } = $props();
    let visible = $state(true);

    function close() {
        visible = false;
        onclose?.();
    }
</script>

{#if visible && message}
    <div class="alert alert--{type}">
        <span>{message}</span>
        <button class="alert__close" type="button" onclick={close} aria-label="Dismiss">&times;</button>
    </div>
{/if}
