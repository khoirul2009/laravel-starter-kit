<script>
    import { Link, router, page } from '@inertiajs/svelte';
    import DashboardLayout from '../../Layouts/DashboardLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import Pagination from '../../Components/Pagination.svelte';
    import ConfirmDialog from '../../Components/ConfirmDialog.svelte';

    let { users, filters } = $props();

    let q = $state(filters?.q ?? '');
    let searchTimer;
    let confirmOpen = $state(false);
    let deleting = $state(false);
    let target = $state(null);

    let me = $derived(page.props.auth?.user);

    function onSearchInput() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            router.get('/users', q ? { q } : {}, {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            });
        }, 300);
    }

    function askDelete(user) {
        target = user;
        confirmOpen = true;
    }

    function cancelDelete() {
        confirmOpen = false;
        target = null;
    }

    function confirmDelete() {
        if (!target) return;
        deleting = true;
        router.delete(`/users/${target.id}`, {
            onFinish: () => {
                deleting = false;
                confirmOpen = false;
                target = null;
            },
        });
    }

    function fmtDate(s) {
        if (!s) return '';
        return new Date(s).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
    }
</script>

<DashboardLayout title="Users">
    <div class="page-header">
        <div>
            <h1 class="page-title">Users</h1>
            <p class="page-subtitle">Manage the accounts that can sign in to the admin.</p>
        </div>
        <Button href="/users/create">+ New user</Button>
    </div>

    <div class="card">
        <div class="toolbar">
            <div class="toolbar__search">
                <input
                    class="field__input"
                    type="search"
                    placeholder="Search by name or email…"
                    bind:value={q}
                    oninput={onSearchInput}
                />
            </div>
        </div>

        {#if users.data.length === 0}
            <div class="empty-state">No users match your search.</div>
        {:else}
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {#each users.data as u (u.id)}
                            <tr>
                                <td><strong>{u.name}</strong></td>
                                <td>{u.email}</td>
                                <td>
                                    <span class="badge {u.status === 1 ? 'badge--active' : 'badge--inactive'}">
                                        {u.status === 1 ? 'Active' : 'Inactive'}
                                    </span>
                                </td>
                                <td>{fmtDate(u.created_at)}</td>
                                <td style="text-align:right;">
                                    <span class="row-actions">
                                        <Button size="sm" variant="secondary" href={`/users/${u.id}`}>View</Button>
                                        <Button size="sm" variant="secondary" href={`/users/${u.id}/edit`}>Edit</Button>
                                        {#if me?.id !== u.id}
                                            <Button size="sm" variant="danger" onclick={() => askDelete(u)}>Delete</Button>
                                        {/if}
                                    </span>
                                </td>
                            </tr>
                        {/each}
                    </tbody>
                </table>
            </div>
        {/if}

        <Pagination links={users.links} />
    </div>
</DashboardLayout>

<ConfirmDialog
    open={confirmOpen}
    title="Delete user?"
    message={target ? `This will permanently delete ${target.name}. This action cannot be undone.` : ''}
    confirmLabel="Delete"
    loading={deleting}
    onConfirm={confirmDelete}
    onCancel={cancelDelete}
/>
