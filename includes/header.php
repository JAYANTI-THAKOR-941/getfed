<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<header class="header">
    <div class="container">
        <div class="logo">
            <a href="/getfed/index.php">GETFED HEALTHY FOOD</a>
        </div>
        <nav id="nav-menu" class="nav-menu">
            <ul>
                <li><a href="/getfed/index.php">Home</a></li>
                <li><a href="/getfed/pages/menu.php">Menu</a></li>
                <li><a href="/getfed/pages/about.php">About Us</a></li>
                <li><a href="/getfed/pages/contact.php">Contact</a></li>
            </ul>
        </nav>
        <div class="header-icons">
            <div class="cart-icon">
                <a href="/getfed/pages/cart.php">
                    <img src="https://cdn-icons-png.freepik.com/512/6713/6713667.png" alt="Cart" />
                    <span class="cart-count"><?php echo $cartCount; ?></span>
                </a>
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="profile-menu">
                    <div class="profile-icon" onclick="toggleProfileMenu()">
                        <?php if (!empty($_SESSION['profile_image'])): ?>
                            <img src="/getfed/uploads/<?php echo basename($_SESSION['profile_image']); ?>" alt="Profile Image">
                        <?php else: ?>
                            <span class="user-initial"><?php echo strtoupper($_SESSION['username'][0]); ?></span>
                        <?php endif; ?>
                    </div>
                    <div id="profile-menu-dropdown" class="profile-menu-dropdown">
                        <?php if (!empty($_SESSION['profile_image'])): ?>
                            <img src="/getfed/uploads/<?php echo basename($_SESSION['profile_image']); ?>" class="profile-icon"; alt="Profile Image">
                        <?php else: ?>
                            <span class="user-initial user-icon"><?php echo strtoupper($_SESSION['username'][0]); ?></span>
                        <?php endif; ?>
                        <h2><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></h2>
                        <p><?php echo htmlspecialchars($_SESSION['email'] ?? 'N/A'); ?></p>
                        <a href="/getfed/pages/account.php" class="btn-account">View Profile</a>
                        <a href="/getfed/pages/logout.php" class="btn-logout">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="login-button">
                    <a href="/getfed/pages/login.php" class="btn-login">Login</a>
                </div>
            <?php endif; ?>

            <button class="menu-toggle" onclick="toggleMenu()">☰</button>
        </div>
    </div>
</header>

<script>
function toggleMenu() {
    const menu = document.getElementById('nav-menu');
    menu.classList.toggle('active');
}

function toggleProfileMenu() {
    const profileMenu = document.getElementById('profile-menu-dropdown');
    profileMenu.classList.toggle('active');
}
</script>

<style>

/* Header Styles */
.header {
    background-color:#2c3e50;
    color: #fff;
    padding: 20px 0;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.header .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header .logo a {
    color: #fff;
    text-decoration: none;
    font-size: 26px;
    font-weight: bold;
    letter-spacing: 1px;
    transition: color 0.3s ease;
}

.header .logo a:hover {
    color: #2ecc71;
}

.header .nav-menu {
    display: flex;
    gap: 20px;
}

.header .nav-menu ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    gap: 20px;
}

.header .nav-menu ul li a {
    color: #fff;
    text-decoration: none;
    font-size: 16px;
    padding: 8px 12px;
    border-radius: 4px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.header .nav-menu ul li a:hover {
    background-color: #2ecc71;
    color: #fff;
}

.header .header-icons {
    display: flex;
    align-items: center;
    gap: 20px;
}


.cart-icon {
    position: relative;
    display: inline-block;
    margin: 0 50%;
}

.cart-icon img {
    width: 24px;
    height: 24px;
}

.cart-icon span {
    background-color: #2ecc71;
    color: #fff;
    font-size: 10px;
    padding: 2px 7px;
    border-radius: 50%;
    position: absolute;
    top: -5px;
    right: -8px;
    font-weight: bold; 
}


.btn-login {
    text-decoration: none;
    padding: 8px 20px;
    background-color: #2ecc71;
    color: #fff;
    border-radius: 20px;
    font-weight: bold;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.btn-login:hover {
    background-color: #1b8d4a;
}

.menu-toggle {
    display: none;
    background: none;
    color: #fff;
    border: none;
    font-size: 24px;
    cursor: pointer;
}
.user-initial {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 40px;
    background-color: #28a745; 
    color: #fff;
    font-weight: bold;
    font-size: 18px;
    border-radius: 50%;
    text-transform: uppercase;
    cursor: pointer;
}

.profile-menu-dropdown {
    display: none;
    position: absolute;
    right: 10px;
    top: 55px;
    background-color: #fff;
    color:#000;
    border: 1px solid #ddd;
    border-radius:5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 40px;
    z-index: 1000;
    text-align: center;
}
.profile-menu-dropdown h2,.profile-menu-dropdown p{
    margin: 0;
    padding: 0;
}
.profile-menu-dropdown p{
    margin-bottom:20px;
}
.profile-menu-dropdown.active {
    display: block;
}

.profile-icon img,.profile-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    cursor: pointer;
}
.user-icon{
    margin: auto;
}
.btn-account,
.btn-logout {
    background-color: #007bff;
    color: #fff;
    padding: 8px 12px;
    text-decoration: none;
    border-radius: 5px;
    text-align: center;
}

.btn-logout {
    background-color: #2ecc71;
}
</style>
