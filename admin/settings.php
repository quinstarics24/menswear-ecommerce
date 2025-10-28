<?php
require_once __DIR__ . '/header.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_general'])) {
        // Update general settings
        // Add your database update logic here
        $success_message = "General settings updated successfully!";
    } elseif (isset($_POST['update_payment'])) {
        // Update payment settings
        $success_message = "Payment settings updated successfully!";
    } elseif (isset($_POST['update_shipping'])) {
        // Update shipping settings
        $success_message = "Shipping settings updated successfully!";
    } elseif (isset($_POST['update_email'])) {
        // Update email settings
        $success_message = "Email settings updated successfully!";
    }
}

// Fetch current settings from database
// For now using placeholder values
$storeName = "Menswear E-commerce";
$storeEmail = "admin@menswear.com";
$storePhone = "+1 (555) 123-4567";
$currency = "USD";
$taxRate = "10";
?>

<style>
  :root {
    --settings-primary: #1a1a1a;
    --settings-accent: #d4af37;
  }

  .settings-header {
    background: linear-gradient(135deg, var(--settings-primary) 0%, #2d2d2d 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  }

  .settings-header h1 {
    margin: 0;
    font-weight: 700;
    letter-spacing: -0.5px;
  }

  .settings-header p {
    margin: 0.5rem 0 0 0;
    opacity: 0.9;
    font-weight: 300;
  }

  /* Settings Navigation Tabs */
  .settings-nav {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
    overflow-x: auto;
  }

  .settings-nav .nav-tabs {
    border: none;
    flex-wrap: nowrap;
  }

  .settings-nav .nav-link {
    border: none;
    color: #6c757d;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
    border-radius: 8px;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .settings-nav .nav-link:hover {
    color: var(--settings-primary);
    background: rgba(212, 175, 55, 0.1);
  }

  .settings-nav .nav-link.active {
    background: var(--settings-accent);
    color: white;
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
  }

  .settings-nav .nav-link i {
    font-size: 1.1rem;
  }

  /* Settings Cards */
  .settings-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
  }

  .settings-card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
  }

  .settings-card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f8f9fa;
  }

  .settings-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    background: rgba(212, 175, 55, 0.1);
    color: var(--settings-accent);
  }

  .settings-card-header h4 {
    margin: 0;
    font-weight: 600;
    color: var(--settings-primary);
  }

  .settings-card-header p {
    margin: 0.25rem 0 0 0;
    font-size: 0.875rem;
    color: #6c757d;
  }

  /* Form Styling */
  .form-label {
    font-weight: 500;
    color: var(--settings-primary);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
  }

  .form-control, .form-select {
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-size: 0.95rem;
  }

  .form-control:focus, .form-select:focus {
    border-color: var(--settings-accent);
    box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.15);
  }

  .form-text {
    color: #6c757d;
    font-size: 0.85rem;
    margin-top: 0.5rem;
  }

  /* Input Group */
  .input-group-text {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-right: none;
    color: #6c757d;
  }

  .input-group .form-control {
    border-left: none;
  }

  .input-group:focus-within .input-group-text {
    border-color: var(--settings-accent);
  }

  /* Switches */
  .form-check-input {
    width: 3rem;
    height: 1.5rem;
    cursor: pointer;
    border: 2px solid #e9ecef;
  }

  .form-check-input:checked {
    background-color: var(--settings-accent);
    border-color: var(--settings-accent);
  }

  .form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.15);
  }

  .form-check-label {
    font-weight: 500;
    color: var(--settings-primary);
    margin-left: 0.5rem;
  }

  /* Buttons */
  .btn-save {
    background: var(--settings-primary);
    color: white;
    border: 2px solid var(--settings-primary);
    padding: 0.75rem 2rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
  }

  .btn-save:hover {
    background: transparent;
    color: var(--settings-primary);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  }

  .btn-reset {
    background: transparent;
    color: #6c757d;
    border: 2px solid #e9ecef;
    padding: 0.75rem 2rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
  }

  .btn-reset:hover {
    background: #f8f9fa;
    border-color: #6c757d;
    color: var(--settings-primary);
  }

  /* Alert Styling */
  .alert-success {
    background: rgba(40, 167, 69, 0.1);
    border: 2px solid rgba(40, 167, 69, 0.3);
    color: #28a745;
    border-radius: 10px;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  .alert-success i {
    font-size: 1.5rem;
  }

  /* Info Box */
  .info-box {
    background: rgba(23, 162, 184, 0.1);
    border-left: 4px solid #17a2b8;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
  }

  .info-box i {
    color: #17a2b8;
    margin-right: 0.5rem;
  }

  .info-box p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .settings-card {
      padding: 1.5rem;
    }
    
    .settings-nav .nav-link {
      padding: 0.5rem 1rem;
      font-size: 0.875rem;
    }
  }
</style>

<div class="settings-header">
  <h1><i class="fa-solid fa-gear me-2"></i>Settings</h1>
  <p>Manage your store configuration and preferences</p>
</div>

<?php if (isset($success_message)): ?>
<div class="alert alert-success">
  <i class="fa-solid fa-circle-check"></i>
  <div>
    <strong>Success!</strong> <?php echo $success_message; ?>
  </div>
</div>
<?php endif; ?>

<!-- Settings Navigation -->
<div class="settings-nav">
  <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
    <li class="nav-item">
      <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#general" type="button">
        <i class="fa-solid fa-store"></i> General
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link" data-bs-toggle="tab" data-bs-target="#payment" type="button">
        <i class="fa-solid fa-credit-card"></i> Payment
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link" data-bs-toggle="tab" data-bs-target="#shipping" type="button">
        <i class="fa-solid fa-truck"></i> Shipping
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link" data-bs-toggle="tab" data-bs-target="#email" type="button">
        <i class="fa-solid fa-envelope"></i> Email
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link" data-bs-toggle="tab" data-bs-target="#advanced" type="button">
        <i class="fa-solid fa-sliders"></i> Advanced
      </button>
    </li>
  </ul>
</div>

<!-- Tab Content -->
<div class="tab-content">
  <!-- General Settings -->
  <div class="tab-pane fade show active" id="general">
    <div class="settings-card">
      <div class="settings-card-header">
        <div class="settings-card-icon">
          <i class="fa-solid fa-store"></i>
        </div>
        <div>
          <h4>General Settings</h4>
          <p>Basic store information and configuration</p>
        </div>
      </div>

      <form method="post" action="">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Store Name</label>
            <input type="text" class="form-control" name="store_name" value="<?php echo htmlspecialchars($storeName); ?>" required>
            <div class="form-text">This will appear in your store header and emails</div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Store Email</label>
            <input type="email" class="form-control" name="store_email" value="<?php echo htmlspecialchars($storeEmail); ?>" required>
            <div class="form-text">Primary contact email for your store</div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Store Phone</label>
            <input type="tel" class="form-control" name="store_phone" value="<?php echo htmlspecialchars($storePhone); ?>">
            <div class="form-text">Customer support phone number</div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Currency</label>
            <select class="form-select" name="currency">
              <option value="USD" <?php echo $currency === 'USD' ? 'selected' : ''; ?>>USD - US Dollar</option>
              <option value="EUR">EUR - Euro</option>
              <option value="GBP">GBP - British Pound</option>
              <option value="CAD">CAD - Canadian Dollar</option>
              <option value="AUD">AUD - Australian Dollar</option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Tax Rate (%)</label>
            <div class="input-group">
              <input type="number" class="form-control" name="tax_rate" value="<?php echo $taxRate; ?>" step="0.01" min="0" max="100">
              <span class="input-group-text">%</span>
            </div>
            <div class="form-text">Default tax rate for products</div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Time Zone</label>
            <select class="form-select" name="timezone">
              <option value="America/New_York">Eastern Time (ET)</option>
              <option value="America/Chicago">Central Time (CT)</option>
              <option value="America/Denver">Mountain Time (MT)</option>
              <option value="America/Los_Angeles">Pacific Time (PT)</option>
            </select>
          </div>

          <div class="col-12">
            <label class="form-label">Store Description</label>
            <textarea class="form-control" name="store_description" rows="3">Quality menswear with timeless style and modern tailoring.</textarea>
            <div class="form-text">Brief description for SEO and social media</div>
          </div>

          <div class="col-12">
            <hr class="my-3">
            <div class="d-flex gap-2">
              <button type="submit" name="update_general" class="btn btn-save">
                <i class="fa-solid fa-check me-2"></i>Save Changes
              </button>
              <button type="reset" class="btn btn-reset">
                <i class="fa-solid fa-rotate-left me-2"></i>Reset
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Payment Settings -->
  <div class="tab-pane fade" id="payment">
    <div class="settings-card">
      <div class="settings-card-header">
        <div class="settings-card-icon">
          <i class="fa-solid fa-credit-card"></i>
        </div>
        <div>
          <h4>Payment Settings</h4>
          <p>Configure payment gateways and options</p>
        </div>
      </div>

      <div class="info-box">
        <i class="fa-solid fa-info-circle"></i>
        <p>Enable and configure your preferred payment methods. Make sure to test in sandbox mode before going live.</p>
      </div>

      <form method="post" action="">
        <div class="row g-3">
          <div class="col-12">
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="enableStripe" checked>
              <label class="form-check-label" for="enableStripe">Enable Stripe Payments</label>
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Stripe Publishable Key</label>
            <input type="text" class="form-control" name="stripe_public_key" placeholder="pk_test_...">
            <div class="form-text">Your Stripe publishable key</div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Stripe Secret Key</label>
            <input type="password" class="form-control" name="stripe_secret_key" placeholder="sk_test_...">
            <div class="form-text">Your Stripe secret key (kept secure)</div>
          </div>

          <div class="col-12"><hr class="my-3"></div>

          <div class="col-12">
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="enablePaypal">
              <label class="form-check-label" for="enablePaypal">Enable PayPal Payments</label>
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label">PayPal Client ID</label>
            <input type="text" class="form-control" name="paypal_client_id" placeholder="Client ID">
          </div>

          <div class="col-md-6">
            <label class="form-label">PayPal Secret</label>
            <input type="password" class="form-control" name="paypal_secret" placeholder="Secret Key">
          </div>

          <div class="col-12"><hr class="my-3"></div>

          <div class="col-12">
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="enableCOD" checked>
              <label class="form-check-label" for="enableCOD">Enable Cash on Delivery</label>
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label">COD Fee</label>
            <div class="input-group">
              <span class="input-group-text">XAF</span>
              <input type="number" class="form-control" name="cod_fee" value="5.00" step="0.01">
            </div>
            <div class="form-text">Additional fee for cash on delivery</div>
          </div>

          <div class="col-12">
            <hr class="my-3">
            <div class="d-flex gap-2">
              <button type="submit" name="update_payment" class="btn btn-save">
                <i class="fa-solid fa-check me-2"></i>Save Changes
              </button>
              <button type="reset" class="btn btn-reset">
                <i class="fa-solid fa-rotate-left me-2"></i>Reset
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Shipping Settings -->
  <div class="tab-pane fade" id="shipping">
    <div class="settings-card">
      <div class="settings-card-header">
        <div class="settings-card-icon">
          <i class="fa-solid fa-truck"></i>
        </div>
        <div>
          <h4>Shipping Settings</h4>
          <p>Configure shipping methods and rates</p>
        </div>
      </div>

      <form method="post" action="">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label">Free Shipping Threshold</label>
            <div class="input-group">
              <span class="input-group-text">XAF</span>
              <input type="number" class="form-control" name="free_shipping_threshold" value="100.00" step="0.01">
            </div>
            <div class="form-text">Minimum order value for free shipping</div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Standard Shipping Rate</label>
            <div class="input-group">
              <span class="input-group-text">XAF</span>
              <input type="number" class="form-control" name="standard_shipping" value="9.99" step="0.01">
            </div>
            <div class="form-text">Delivery in 5-7 business days</div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Express Shipping Rate</label>
            <div class="input-group">
              <span class="input-group-text">XAF</span>
              <input type="number" class="form-control" name="express_shipping" value="19.99" step="0.01">
            </div>
            <div class="form-text">Delivery in 2-3 business days</div>
          </div>

          <div class="col-12"><hr class="my-3"></div>

          <div class="col-12">
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="enableTracking" checked>
              <label class="form-check-label" for="enableTracking">Enable Order Tracking</label>
            </div>
          </div>

          <div class="col-12">
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="enableLocalPickup">
              <label class="form-check-label" for="enableLocalPickup">Enable Local Pickup</label>
            </div>
          </div>

          <div class="col-12">
            <label class="form-label">Shipping Zones</label>
            <textarea class="form-control" name="shipping_zones" rows="3">United States, Canada, United Kingdom, Australia</textarea>
            <div class="form-text">Countries/regions where you ship (comma-separated)</div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Processing Time</label>
            <select class="form-select" name="processing_time">
              <option value="1-2">1-2 business days</option>
              <option value="3-5" selected>3-5 business days</option>
              <option value="5-7">5-7 business days</option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Default Shipping Carrier</label>
            <select class="form-select" name="default_carrier">
              <option value="usps">USPS</option>
              <option value="ups" selected>UPS</option>
              <option value="fedex">FedEx</option>
              <option value="dhl">DHL</option>
            </select>
          </div>

          <div class="col-12">
            <hr class="my-3">
            <div class="d-flex gap-2">
              <button type="submit" name="update_shipping" class="btn btn-save">
                <i class="fa-solid fa-check me-2"></i>Save Changes
              </button>
              <button type="reset" class="btn btn-reset">
                <i class="fa-solid fa-rotate-left me-2"></i>Reset
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Email Settings -->
  <div class="tab-pane fade" id="email">
    <div class="settings-card">
      <div class="settings-card-header">
        <div class="settings-card-icon">
          <i class="fa-solid fa-envelope"></i>
        </div>
        <div>
          <h4>Email Settings</h4>
          <p>Configure email notifications and templates</p>
        </div>
      </div>

      <form method="post" action="">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label">From Email Address</label>
            <input type="email" class="form-control" name="from_email" value="noreply@menswear.com">
            <div class="form-text">Email address used for outgoing emails</div>
          </div>

          <div class="col-12">
            <label class="form-label">From Name</label>
            <input type="text" class="form-control" name="from_name" value="Menswear Store">
            <div class="form-text">Name displayed in outgoing emails</div>
          </div>

          <div class="col-12"><hr class="my-3"></div>

          <div class="col-12">
            <h5 class="mb-3">Email Notifications</h5>
          </div>

          <div class="col-md-6">
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="emailNewOrder" checked>
              <label class="form-check-label" for="emailNewOrder">New Order Confirmation</label>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="emailShipped" checked>
              <label class="form-check-label" for="emailShipped">Order Shipped</label>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="emailDelivered" checked>
              <label class="form-check-label" for="emailDelivered">Order Delivered</label>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="emailCancelled">
              <label class="form-check-label" for="emailCancelled">Order Cancelled</label>
            </div>
          </div>

          <div class="col-12"><hr class="my-3"></div>

          <div class="col-12">
            <h5 class="mb-3">SMTP Configuration</h5>
          </div>

          <div class="col-md-6">
            <label class="form-label">SMTP Host</label>
            <input type="text" class="form-control" name="smtp_host" placeholder="smtp.gmail.com">
          </div>

          <div class="col-md-6">
            <label class="form-label">SMTP Port</label>
            <input type="number" class="form-control" name="smtp_port" value="587">
          </div>

          <div class="col-md-6">
            <label class="form-label">SMTP Username</label>
            <input type="text" class="form-control" name="smtp_username">
          </div>

          <div class="col-md-6">
            <label class="form-label">SMTP Password</label>
            <input type="password" class="form-control" name="smtp_password">
          </div>

          <div class="col-12">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="smtpSSL" checked>
              <label class="form-check-label" for="smtpSSL">Use SSL/TLS</label>
            </div>
          </div>

          <div class="col-12">
            <hr class="my-3">
            <div class="d-flex gap-2">
              <button type="submit" name="update_email" class="btn btn-save">
                <i class="fa-solid fa-check me-2"></i>Save Changes
              </button>
              <button type="button" class="btn btn-reset">
                <i class="fa-solid fa-paper-plane me-2"></i>Send Test Email
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Advanced Settings -->
  <div class="tab-pane fade" id="advanced">
    <div class="settings-card">
      <div class="settings-card-header">
        <div class="settings-card-icon">
          <i class="fa-solid fa-sliders"></i>
        </div>
        <div>
          <h4>Advanced Settings</h4>
          <p>Advanced configuration options</p>
        </div>
      </div>

      <div class="info-box">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <p><strong>Caution:</strong> These settings can affect your store's functionality. Only modify if you understand the implications.</p>
      </div>

      <form method="post" action="">
        <div class="row g-3">
          <div class="col-12">
            <h5 class="mb-3">Performance</h5>
          </div>

          <div class="col-md-6">
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="enableCache" checked>
              <label class="form-check-label" for="enableCache">Enable Page Caching</label>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="minifyCSS" checked>
              <label class="form-check-label" for="minifyCSS">Minify CSS & JavaScript</label>
            </div>
          </div>

          <div class="col-12"><hr class="my-3"></div>

          <div class="col-12">
            <h5 class="mb-3">Security</h5>
          </div>

          <div class="col-md-6">
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="enable2FA">
              <label class="form-check-label" for="enable2FA">Enable Two-Factor Authentication</label>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="forceSSL" checked>
              <label class="form-check-label" for="forceSSL">Force SSL (HTTPS)</label>
            </div>
          </div>

          <div class="col-12">
            <label class="form-label">Session Timeout (minutes)</label>
            <input type="number" class="form-control" name="session_timeout" value="30" min="5" max="120">
            <div class="form-text">User session will expire after this many minutes of inactivity</div>
          </div>

          <div class="col-12"><hr class="my-3"></div>

          <div class="col-12">
            <h5 class="mb-3">Maintenance</h5>
          </div>

          <div class="col-12">
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="maintenanceMode">
              <label class="form-check-label" for="maintenanceMode">Enable Maintenance Mode</label>
            </div>
            <div class="form-text mb-3">Display maintenance page to visitors (admins can still access)</div>
          </div>

          <div class="col-12">
            <label class="form-label">Maintenance Message</label>
            <textarea class="form-control" name="maintenance_message" rows="2">We're currently updating our store. Please check back soon!</textarea>
          </div>

          <div class="col-12"><hr class="my-3"></div>

          <div class="col-12">
            <h5 class="mb-3">Backup & Export</h5>
          </div>

          <div class="col-md-6">
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="autoBackup" checked>
              <label class="form-check-label" for="autoBackup">Automatic Daily Backups</label>
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Backup Retention (days)</label>
            <input type="number" class="form-control" name="backup_retention" value="30" min="7" max="90">
          </div>

          <div class="col-12 mt-3">
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-outline-dark">
                <i class="fa-solid fa-download me-2"></i>Download Backup
              </button>
              <button type="button" class="btn btn-outline-dark">
                <i class="fa-solid fa-file-export me-2"></i>Export Data
              </button>
            </div>
          </div>

          <div class="col-12">
            <hr class="my-3">
            <div class="d-flex gap-2">
              <button type="submit" name="update_advanced" class="btn btn-save">
                <i class="fa-solid fa-check me-2"></i>Save Changes
              </button>
              <button type="reset" class="btn btn-reset">
                <i class="fa-solid fa-rotate-left me-2"></i>Reset
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
require_once __DIR__ . '/footer.php';
?>