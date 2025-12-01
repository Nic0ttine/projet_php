<?php
// On vérifie si la session est démarrée, sinon on le fait (sécurité)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="main-header">
    <div class="header-content">
        <a href="index.html" class="logo">Youtube</a>

        <div class="burger-menu" id="burgerToggle">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <nav class="nav-links" id="navLinks">
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1): ?>
                    <a href="admin.php" class="nav-item admin-link">Admin</a>
                <?php endif; ?>

                <a href="profil.php" class="nav-item">Mon Profil</a>
                <a href="logout.php" class="nav-item btn-logout">Se déconnecter</a>

            <?php else: ?>
                <a href="login.php" class="nav-item">Connexion</a>
                <a href="register.php" class="nav-item btn-register">Inscription</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<script>
    // Petit script JS pour gérer le clic sur le burger
    const burger = document.getElementById('burgerToggle');
    const nav = document.getElementById('navLinks');

    burger.addEventListener('click', () => {
        nav.classList.toggle('active'); // On ajoute/enlève la classe "active"
        burger.classList.toggle('toggle'); // Pour l'animation du burger (optionnel)
    });
</script>