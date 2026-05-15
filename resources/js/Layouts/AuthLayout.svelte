<script>
    let { children } = $props();
</script>

<div class="auth-shell">
    <div class="auth-card">
        <div class="auth-card__brand">
            <span class="sidebar__brand-mark">L</span>
            <span>Laravel Observe</span>
        </div>
        {@render children?.()}
    </div>
</div>
