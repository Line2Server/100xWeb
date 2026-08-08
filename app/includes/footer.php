<?php
// Footer
?>
    <footer class="main-footer">
        <div class="footer-top">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-brand">
                        <div class="footer-logo">
                            <svg viewBox="0 0 100 100" width="50" height="50">
                                <polygon points="50,5 95,27.5 95,72.5 50,95 5,72.5 5,27.5" fill="none" stroke="currentColor" stroke-width="2"/>
                                <text x="50" y="58" text-anchor="middle" font-size="28" font-weight="bold" fill="currentColor" font-family="Cinzel">E</text>
                            </svg>
                        </div>
                        <h3><?php echo SERVER_NAME; ?></h3>
                        <p>O melhor servidor Lineage 2 Interlude PvP Mid-Rate do Brasil. Venha fazer parte da história.</p>
                        <div class="footer-socials">
                            <a href="<?php echo DISCORD_INVITE; ?>" target="_blank" aria-label="Discord">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/></svg>
                            </a>
                            <a href="<?php echo FACEBOOK_URL; ?>" target="_blank" aria-label="Facebook">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="<?php echo YOUTUBE_URL; ?>" target="_blank" aria-label="YouTube">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </a>
                        </div>
                    </div>

                    <div class="footer-links">
                        <h4>Navegação</h4>
                        <ul>
                            <li><a href="/">Início</a></li>
                            <li><a href="/pages/rankings.php">Rankings</a></li>
                            <li><a href="/pages/donate.php">Donate</a></li>
                            <li><a href="/pages/downloads.php">Downloads</a></li>
                            <li><a href="/pages/roadmap.php">Roadmap</a></li>
                        </ul>
                    </div>

                    <div class="footer-links">
                        <h4>Suporte</h4>
                        <ul>
                            <li><a href="<?php echo DISCORD_INVITE; ?>" target="_blank">Discord Support</a></li>
                            <li><a href="<?php echo WHATSAPP_GROUP; ?>" target="_blank">WhatsApp Grupo</a></li>
                            <li><a href="/pages/info.php#rules">Regras do Servidor</a></li>
                            <li><a href="/pages/info.php#faq">FAQ</a></li>
                        </ul>
                    </div>

                    <div class="footer-links">
                        <h4>Status</h4>
                        <ul>
                            <li><span class="footer-status <?php echo $serverStatus['online'] ? 'online' : 'offline'; ?>">
                                <?php echo $serverStatus['online'] ? '🟢 Online' : '🔴 Offline'; ?>
                            </span></li>
                            <li><?php echo formatNumber($serverStatus['players']); ?> jogadores online</li>
                            <li>Chronicle: <?php echo SERVER_CHRONICLE; ?></li>
                            <li>IP: <?php echo SERVER_IP; ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SERVER_NAME; ?>. Todos os direitos reservados.</p>
                <p class="footer-disclaimer">Lineage II é marca registrada da NCSOFT Corporation. Servidor não-oficial.</p>
            </div>
        </div>
    </footer>

    <script src="/assets/js/main.js"></script>
</body>
</html>
