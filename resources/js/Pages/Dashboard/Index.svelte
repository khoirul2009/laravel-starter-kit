<script>
    import DashboardLayout from '../../Layouts/DashboardLayout.svelte';
    import StatCard from '../../Components/StatCard.svelte';

    let { stats } = $props();

    function fmtDate(s) {
        if (!s) return '';
        return new Date(s).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
    }
</script>

<DashboardLayout title="Dashboard">
    <div class="page-header">
        <div>
            <h1 class="page-title">Overview</h1>
            <p class="page-subtitle">Welcome back — here's what's happening today.</p>
        </div>
    </div>

    <div class="stat-grid">
        <StatCard title="Total Users" value={stats.total} icon="👥" accent={1} />
        <StatCard title="Active Users" value={stats.active} icon="✅" accent={2} />
        <StatCard title="Inactive Users" value={stats.inactive} icon="🚫" accent={3} />
        <StatCard title="Latest Registered" value={stats.latest.length} icon="✨" accent={4} />
    </div>

    <div class="card">
        <div class="card__header">
            <div>
                <h2 class="card__title">Latest registered users</h2>
                <p class="card__subtitle">The five most recently created accounts.</p>
            </div>
        </div>

        {#if stats.latest.length === 0}
            <div class="empty-state">No users yet.</div>
        {:else}
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        {#each stats.latest as u}
                            <tr>
                                <td>{u.name}</td>
                                <td>{u.email}</td>
                                <td>
                                    <span class="badge {u.status === 1 ? 'badge--active' : 'badge--inactive'}">
                                        {u.status === 1 ? 'Active' : 'Inactive'}
                                    </span>
                                </td>
                                <td>{fmtDate(u.created_at)}</td>
                            </tr>
                        {/each}
                    </tbody>
                </table>
            </div>
        {/if}
    </div>
</DashboardLayout>
