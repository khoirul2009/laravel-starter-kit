<script>
    import DashboardLayout from '../../Layouts/DashboardLayout.svelte';
    import Button from '../../Components/Button.svelte';

    let { user } = $props();

    function fmtDate(s) {
        if (!s) return '—';
        return new Date(s).toLocaleString();
    }
</script>

<DashboardLayout title="User detail">
    <div class="page-header">
        <div>
            <h1 class="page-title">{user.name}</h1>
            <p class="page-subtitle">{user.email}</p>
        </div>
        <div style="display:flex;gap:8px;">
            <Button variant="secondary" href="/users">Back</Button>
            <Button href={`/users/${user.id}/edit`}>Edit</Button>
        </div>
    </div>

    <div class="card" style="max-width:720px;">
        <div class="dl-row">
            <span class="dl-row__label">ID</span>
            <span class="dl-row__value">#{user.id}</span>
        </div>
        <div class="dl-row">
            <span class="dl-row__label">Name</span>
            <span class="dl-row__value">{user.name}</span>
        </div>
        <div class="dl-row">
            <span class="dl-row__label">Email</span>
            <span class="dl-row__value">{user.email}</span>
        </div>
        <div class="dl-row">
            <span class="dl-row__label">Status</span>
            <span class="dl-row__value">
                <span class="badge {user.status === 1 ? 'badge--active' : 'badge--inactive'}">
                    {user.status === 1 ? 'Active' : 'Inactive'}
                </span>
            </span>
        </div>
        <div class="dl-row">
            <span class="dl-row__label">Created</span>
            <span class="dl-row__value">{fmtDate(user.created_at)}</span>
        </div>
        <div class="dl-row">
            <span class="dl-row__label">Updated</span>
            <span class="dl-row__value">{fmtDate(user.updated_at)}</span>
        </div>
    </div>
</DashboardLayout>
