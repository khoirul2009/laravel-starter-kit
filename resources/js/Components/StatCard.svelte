<script>
    let { title = '', value = 0, icon = '•', accent = 1 } = $props();
</script>

<div class="stat-card stat-card--accent-{accent}">
    <div class="stat-card__icon">{icon}</div>
    <div>
        <p class="stat-card__title">{title}</p>
        <p class="stat-card__value">{value}</p>
    </div>
</div>
