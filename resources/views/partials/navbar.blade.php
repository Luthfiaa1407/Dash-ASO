<style>
.navbar {
    background: #0F2A44;
    color: white;
    padding: 16px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.navbar-title {
    font-size: 18px;
    font-weight: 600;
}

.navbar-right {
    display: flex;
    align-items: center;
    gap: 24px; /* Jarak antara tabs dan logout */
}

.navbar-tabs {
    display: flex;
    gap: 16px;
}

.navbar-tabs a {
    color: white;
    text-decoration: none;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.navbar-tabs a.active {
    font-weight: 700; /* Teks bold jika aktif */
}

.logout {
    background: #E31E24;
    color: white;
    padding: 8px 18px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 13px;
}

.logout:hover {
    opacity: 0.9;
}
</style>

<div class="navbar">
    <span class="navbar-title">Monitoring Kinerja Teknisi</span>

    <div class="navbar-right">
        <div class="navbar-tabs">
            <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="/detail" class="{{ request()->is('detail') ? 'active' : '' }}">Detail</a>
        </div>
        <a href="/login" class="logout" onclick="return confirmLogout(event)">Logout</a>
    </div>
</div>

<script>
function confirmLogout(event) {
    event.preventDefault(); // Mencegah link langsung diarahkan
    if (confirm('Yakin mau logout?')) {
        window.location.href = event.target.href; // Jika OK, arahkan ke /login
    }
}
</script>