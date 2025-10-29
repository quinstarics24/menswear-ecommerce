<?php
require_once __DIR__ . '/header.php';

// Fetch some statistics (you can add these functions to your includes/functions.php)
// $totalProducts = getTotalProducts();
// $totalOrders = getTotalOrders();
// $pendingOrders = getPendingOrders();
// $totalRevenue = getTotalRevenue();

// For now, using placeholder values - replace with actual database queries
$totalProducts = 45;
$totalOrders = 128;
$pendingOrders = 12;
$totalRevenue = 7850000; // In FCFA
?>

<style>
  :root {
    --admin-primary: #1a1a1a;
    --admin-accent: #d4af37;
    --admin-success: #28a745;
    --admin-info: #17a2b8;
    --admin-warning: #ffc107;
    --admin-danger: #dc3545;
  }

  .dashboard-header {
    background: linear-gradient(135deg, var(--admin-primary) 0%, #2d2d2d 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  }

  .dashboard-header h1 {
    margin: 0;
    font-weight: 700;
    letter-spacing: -0.5px;
  }

  .dashboard-header p {
    margin: 0.5rem 0 0 0;
    opacity: 0.9;
    font-weight: 300;
  }

  /* Stats Cards */
  .stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    border: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }

  .stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--card-accent, var(--admin-accent));
    transition: width 0.3s ease;
  }

  .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
  }

  .stat-card:hover::before {
    width: 8px;
  }

  .stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
  }

  .stat-card:hover .stat-icon {
    transform: scale(1.1) rotate(5deg);
  }

  .stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--admin-primary);
    margin-bottom: 0.25rem;
  }

  .stat-label {
    color: #6c757d;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
  }

  .stat-trend {
    font-size: 0.75rem;
    margin-top: 0.5rem;
    padding: 0.25rem 0.5rem;
    border-radius: 20px;
    display: inline-block;
  }

  .stat-trend.up {
    background: rgba(40, 167, 69, 0.1);
    color: var(--admin-success);
  }

  .stat-trend.down {
    background: rgba(220, 53, 69, 0.1);
    color: var(--admin-danger);
  }

  /* Action Cards */
  .action-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    border: none;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    height: 100%;
  }

  .action-card::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 120px;
    height: 120px;
    background: radial-gradient(circle, rgba(212, 175, 55, 0.08), transparent);
    border-radius: 50%;
    transform: translate(40%, -40%);
    transition: all 0.4s ease;
  }

  .action-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
  }

  .action-card:hover::after {
    transform: translate(30%, -30%) scale(1.5);
  }

  .action-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    margin-bottom: 1.25rem;
    transition: all 0.3s ease;
  }

  .action-card:hover .action-icon {
    transform: scale(1.15) rotate(-5deg);
  }

  .action-card h5 {
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: var(--admin-primary);
  }

  .action-card p {
    color: #6c757d;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
    line-height: 1.6;
  }

  .action-card .btn {
    width: 100%;
    padding: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
  }

  .action-card .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
  }

  /* Quick Actions Section */
  .quick-actions {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 12px;
    padding: 2rem;
    margin-top: 2rem;
  }

  .quick-actions h3 {
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: var(--admin-primary);
  }

  .quick-action-btn {
    background: white;
    border: 2px solid #e9ecef;
    padding: 1rem;
    border-radius: 10px;
    transition: all 0.3s ease;
    text-align: center;
    text-decoration: none;
    color: var(--admin-primary);
    display: block;
  }

  .quick-action-btn:hover {
    border-color: var(--admin-accent);
    background: var(--admin-accent);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
  }

  .quick-action-btn i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    display: block;
  }

  .quick-action-btn span {
    font-size: 0.875rem;
    font-weight: 500;
    display: block;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .stat-value {
      font-size: 1.5rem;
    }
    
    .dashboard-header {
      padding: 1.5rem;
    }
  }
</style>
<?php
// Correct paths from admin folder
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// --- Fetch Dashboard Stats ---

// Total Products
$stmt = $pdo->query("SELECT COUNT(*) FROM products");
$totalProducts = $stmt->fetchColumn();

// Total Orders
$stmt = $pdo->query("SELECT COUNT(*) FROM orders");
$totalOrders = $stmt->fetchColumn();

// Pending Orders
$stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'");
$pendingOrders = $stmt->fetchColumn();

// Total Revenue (sum of completed orders)
$stmt = $pdo->query("SELECT SUM(total) FROM orders WHERE status = 'completed'");
$totalRevenue = $stmt->fetchColumn() ?? 0;

// Optional: Trends (example)
$lastMonthOrdersStmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
$lastMonthOrders = $lastMonthOrdersStmt->fetchColumn();
$orderTrend = $lastMonthOrders > 0 ? round(($totalOrders - $lastMonthOrders) / $lastMonthOrders * 100) : 0;

$lastMonthRevenueStmt = $pdo->query("SELECT SUM(total) FROM orders WHERE status = 'completed' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
$lastMonthRevenue = $lastMonthRevenueStmt->fetchColumn() ?? 0;
$revenueTrend = $lastMonthRevenue > 0 ? round(($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue * 100) : 0;
?>

<div class="dashboard-header">
  <h1>Admin Dashboard</h1>
  <p>Welcome back! Here's what's happening with your store today.</p>
</div>

<!-- Statistics Overview -->
<div class="row g-4 mb-4">
  <div class="col-md-3 col-sm-6">
    <div class="stat-card" style="--card-accent: var(--admin-info);">
      <div class="stat-icon" style="background: rgba(23, 162, 184, 0.1); color: var(--admin-info);">
        <i class="fa-solid fa-box"></i>
      </div>
      <div class="stat-value"><?php echo $totalProducts; ?></div>
      <div class="stat-label">Total Products</div>
      <div class="stat-trend up">
        <i class="fa-solid fa-arrow-up"></i> Updated
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6">
    <div class="stat-card" style="--card-accent: var(--admin-success);">
      <div class="stat-icon" style="background: rgba(40, 167, 69, 0.1); color: var(--admin-success);">
        <i class="fa-solid fa-shopping-cart"></i>
      </div>
      <div class="stat-value"><?php echo $totalOrders; ?></div>
      <div class="stat-label">Total Orders</div>
      <div class="stat-trend up">
        <i class="fa-solid fa-arrow-up"></i> <?php echo $orderTrend; ?>% from last month
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6">
    <div class="stat-card" style="--card-accent: var(--admin-warning);">
      <div class="stat-icon" style="background: rgba(255, 193, 7, 0.1); color: var(--admin-warning);">
        <i class="fa-solid fa-clock"></i>
      </div>
      <div class="stat-value"><?php echo $pendingOrders; ?></div>
      <div class="stat-label">Pending Orders</div>
      <div class="stat-trend down">
        <i class="fa-solid fa-arrow-down"></i> <?php echo $pendingOrders; ?> pending
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6">
    <div class="stat-card" style="--card-accent: var(--admin-accent);">
      <div class="stat-icon" style="background: rgba(212, 175, 55, 0.1); color: var(--admin-accent);">
        <i class="fa-solid fa-dollar-sign"></i>
      </div>
      <div class="stat-value">XAF<?php echo number_format($totalRevenue, 2); ?></div>
      <div class="stat-label">Total Revenue</div>
      <div class="stat-trend up">
        <i class="fa-solid fa-arrow-up"></i> <?php echo $revenueTrend; ?>% from last month
      </div>
    </div>
  </div>
</div>

<!-- Main Action Cards -->
<div class="row g-4 mb-4">
  <div class="col-md-4">
    <div class="action-card">
      <div class="action-icon" style="background: rgba(23, 162, 184, 0.1); color: var(--admin-info);">
        <i class="fa-solid fa-box-open"></i>
      </div>
      <h5>Products</h5>
      <p>Manage products: add, edit or remove items from your inventory.</p>
      <a href="products.php" class="btn btn-dark">Manage Products</a>
    </div>
  </div>

  <div class="col-md-4">
    <div class="action-card">
      <div class="action-icon" style="background: rgba(40, 167, 69, 0.1); color: var(--admin-success);">
        <i class="fa-solid fa-list-check"></i>
      </div>
      <h5>Orders</h5>
      <p>View and update orders. Process pending orders and track shipments.</p>
      <a href="orders.php" class="btn btn-dark">View Orders</a>
    </div>
  </div>

  <div class="col-md-4">
    <div class="action-card">
      <div class="action-icon" style="background: rgba(212, 175, 55, 0.1); color: var(--admin-accent);">
        <i class="fa-solid fa-gear"></i>
      </div>
      <h5>Settings</h5>
      <p>Shop settings and preferences. Configure your store options.</p>
      <a href="settings.php" class="btn btn-dark">Settings</a>
    </div>
  </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
  <h3><i class="fa-solid fa-bolt me-2"></i>Quick Actions</h3>
  <div class="row g-3">
    <div class="col-6 col-md-3">
      <a href="products.php?action=add" class="quick-action-btn">
        <i class="fa-solid fa-plus-circle"></i>
        <span>Add Product</span>
      </a>
    </div>
    <div class="col-6 col-md-3">
      <a href="orders.php?status=pending" class="quick-action-btn">
        <i class="fa-solid fa-clock-rotate-left"></i>
        <span>Pending Orders</span>
      </a>
    </div>
    <div class="col-6 col-md-3">
      <a href="orders.php?action=export" class="quick-action-btn">
        <i class="fa-solid fa-file-export"></i>
        <span>Export Data</span>
      </a>
    </div>
    <div class="col-6 col-md-3">
      <a href="settings.php" class="quick-action-btn">
        <i class="fa-solid fa-chart-line"></i>
        <span>View Reports</span>
      </a>
    </div>
  </div>
</div>

<?php
require_once __DIR__ . '/footer.php';
?>