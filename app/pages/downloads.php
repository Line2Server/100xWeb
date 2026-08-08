<?php
require_once '../includes/config.php';

$downloads = [
    [
        'name' => 'Cliente Completo',
        'icon' => '🎮',
        'desc' => 'Cliente Lineage 2 Interlude completo com todas as atualizações. Pronto para jogar.',
        'size' => '3.2 GB',
        'version' => 'C6 Interlude',
        'url' => '#',
        'mirror' => '#',
        'type' => 'primary'
    ],
    [
        'name' => 'System Folder',
        'icon' => '⚙️',
        'desc' => 'Apenas a pasta System com as configurações do servidor. Para quem já tem o cliente.',
        'size' => '45 MB',
        'version' => 'v2.9.8',
        'url' => '#',
        'mirror' => '#',
        'type' => 'secondary'
    ],
    [
        'name' => 'Patch de Atualização',
        'icon' => '🔄',
        'desc' => 'Patch com as últimas correções e melhorias. Aplique sobre o cliente existente.',
        'size' => '120 MB',
        'version' => 'v2.9.8b',
        'url' => '#',
        'mirror' => '#',
        'type' => 'secondary'
    ],
    [
        'name' => 'DirectX 9.0c',
        'icon' => '🔧',
        'desc' => 'Runtime necessário para rodar o jogo. Instale se tiver problemas gráficos.',
        'size' => '95 MB',
        'version' => '9.0c',
        'url' => 'https://www.microsoft.com/download/details.aspx?id=35',
        'mirror' => 'https://www.microsoft.com/download/details.aspx?id=35',
        'type' => 'tool'
    ],
    [
        'name' => 'Java JRE 8',
        'icon' => '☕',
        'desc' => 'Java Runtime Environment necessário para o updater e alguns utilitários.',
        'size' => '65 MB',
        'version' => '8u381',
        'url' => 'https://www.java.com/download',
        'mirror' => 'https://www.java.com/download',
        'type' => 'tool'
    ],
    [
        'name' => 'Auto-Updater',
        'icon' => '🚀',
        'desc' => 'Atualizador automático do cliente. Mantém seu jogo sempre na versão mais recente.',
        'size' => '5 MB',
        'version' => 'v1.2',
        'url' => '#',
        'mirror' => '#',
        'type' => 'secondary'
    ]
];

require_once '../includes/header.php';
?>

<style>
.download-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
.download-card { background: var(--dark-card); border: 1px solid var(--dark-border); border-radius: 16px; padding: 32px; text-align: center; transition: var(--transition); position: relative; overflow: hidden; }
.download-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--gold-dark), var(--gold), var(--gold-dark)); opacity: 0.3; transition: var(--transition); }
.download-card:hover { border-color: rgba(201,168,76,0.2); transform: translateY(-5px); }
.download-card:hover::before { opacity: 1; }
.download-icon { width: 64px; height: 64px; margin: 0 auto 20px; border-radius: 16px; background: rgba(201,168,76,0.08); border: 1px solid rgba(201,168,76,0.15); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; }
.download-title { font-family: var(--font-display); font-size: 1.2rem; font-weight: 600; color: var(--text-bright); margin-bottom: 8px; }
.download-desc { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 16px; line-height: 1.6; min-height: 55px; }
.download-meta { display: flex; justify-content: center; gap: 16px; margin-bottom: 20px; font-size: 0.8rem; color: var(--text-muted); }
.download-meta span { background: rgba(201,168,76,0.05); padding: 4px 12px; border-radius: 20px; border: 1px solid rgba(201,168,76,0.1); }
.download-actions { display: flex; gap: 10px; }
.download-actions .btn { flex: 1; padding: 10px 16px; font-size: 0.8rem; }
.guide-section { background: var(--dark-card); border: 1px solid var(--dark-border); border-radius: 16px; padding: 32px; margin-top: 60px; }
.guide-section h3 { font-family: var(--font-display); color: var(--gold); margin-bottom: 20px; }
.guide-steps { counter-reset: step; }
.guide-step { display: flex; gap: 20px; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid var(--dark-border); }
.guide-step:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
.guide-step-num { counter-increment: step; min-width: 40px; height: 40px; border-radius: 50%; background: rgba(201,168,76,0.1); border: 1px solid rgba(201,168,76,0.2); display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-weight: 700; color: var(--gold); }
.guide-step-num::before { content: counter(step); }
.guide-step-content h4 { color: var(--text-bright); margin-bottom: 6px; font-size: 1rem; }
.guide-step-content p { color: var(--text-muted); font-size: 0.9rem; line-height: 1.6; }
.server-info-box { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-top: 40px; }
.info-box { background: var(--dark-card); border: 1px solid var(--dark-border); border-radius: 12px; padding: 24px; }
.info-box h4 { font-family: var(--font-display); color: var(--gold); margin-bottom: 12px; font-size: 0.95rem; }
.info-box p { color: var(--text-muted); font-size: 0.85rem; line-height: 1.7; }
.info-box code { background: rgba(201,168,76,0.1); padding: 2px 6px; border-radius: 4px; color: var(--gold); font-size: 0.8rem; }
@media (max-width: 1024px) { .download-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 768px) { .download-grid { grid-template-columns: 1fr; } .server-info-box { grid-template-columns: 1fr; } }
</style>

<section class="section" style="padding-top: 40px;">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Downloads</span>
            <h2 class="section-title">Baixe e Jogue</h2>
            <p class="section-desc">Tudo que você precisa para começar sua jornada em Aden.</p>
        </div>

        <div class="download-grid">
            <?php foreach ($downloads as $dl): ?>
            <div class="download-card">
                <div class="download-icon"><?php echo $dl['icon']; ?></div>
                <h3 class="download-title"><?php echo $dl['name']; ?></h3>
                <p class="download-desc"><?php echo $dl['desc']; ?></p>
                <div class="download-meta">
                    <span>📦 <?php echo $dl['size']; ?></span>
                    <span>🏷️ <?php echo $dl['version']; ?></span>
                </div>
                <div class="download-actions">
                    <a href="<?php echo $dl['url']; ?>" class="btn btn-primary" style="justify-content:center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        Download
                    </a>
                    <?php if ($dl['mirror'] != $dl['url']): ?>
                    <a href="<?php echo $dl['mirror']; ?>" class="btn btn-secondary" style="justify-content:center;">Mirror</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Guia de Instalação -->
        <div class="guide-section">
            <h3>📖 Guia de Instalação</h3>
            <div class="guide-steps">
                <div class="guide-step">
                    <div class="guide-step-num"></div>
                    <div class="guide-step-content">
                        <h4>Baixe o Cliente Completo</h4>
                        <p>Clique em "Download" no card acima. O arquivo é um .zip ou .exe de aproximadamente 3.2GB. Recomendamos usar um gerenciador de downloads para evitar corrupção.</p>
                    </div>
                </div>
                <div class="guide-step">
                    <div class="guide-step-num"></div>
                    <div class="guide-step-content">
                        <h4>Extraia os Arquivos</h4>
                        <p>Extraia o conteúdo para uma pasta de sua preferência (ex: C:\Games\Lineage2). Certifique-se de ter pelo menos 5GB de espaço livre.</p>
                    </div>
                </div>
                <div class="guide-step">
                    <div class="guide-step-num"></div>
                    <div class="guide-step-content">
                        <h4>Instale os Pré-requisitos</h4>
                        <p>Baixe e instale o DirectX 9.0c e Java JRE 8 (links acima). Reinicie o computador após a instalação.</p>
                    </div>
                </div>
                <div class="guide-step">
                    <div class="guide-step-num"></div>
                    <div class="guide-step-content">
                        <h4>Configure o Servidor</h4>
                        <p>Abra a pasta <code>system</code> e edite o arquivo <code>l2.ini</code> (ou use o L2Editor). Altere o IP para: <code><?php echo SERVER_IP; ?></code></p>
                    </div>
                </div>
                <div class="guide-step">
                    <div class="guide-step-num"></div>
                    <div class="guide-step-content">
                        <h4>Crie sua Conta</h4>
                        <p>Acesse o jogo e crie sua conta diretamente no login screen. Use um e-mail válido para recuperação de senha.</p>
                    </div>
                </div>
                <div class="guide-step">
                    <div class="guide-step-num"></div>
                    <div class="guide-step-content">
                        <h4>Entre no Jogo!</h4>
                        <p>Selecione seu servidor, crie seu personagem e comece sua aventura em Aden. Boa sorte, guerreiro!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info do Servidor -->
        <div class="server-info-box">
            <div class="info-box">
                <h4>🌐 Informações do Servidor</h4>
                <p><strong>IP:</strong> <code><?php echo SERVER_IP; ?></code> <button data-copy="<?php echo SERVER_IP; ?>" style="background:none;border:none;color:var(--gold);cursor:pointer;font-size:0.8rem;">📋 Copiar</button></p>
                <p><strong>Porta Game:</strong> <code><?php echo SERVER_PORT; ?></code></p>
                <p><strong>Porta Login:</strong> <code><?php echo LOGIN_PORT; ?></code></p>
                <p><strong>Chronicle:</strong> Interlude (C6)</p>
            </div>
            <div class="info-box">
                <h4>⚙️ Requisitos Mínimos</h4>
                <p><strong>OS:</strong> Windows 7/8/10/11 (64-bit)</p>
                <p><strong>CPU:</strong> Intel Core 2 Duo 2.0GHz</p>
                <p><strong>RAM:</strong> 2GB</p>
                <p><strong>GPU:</strong> GeForce 6600 / Radeon X1300</p>
                <p><strong>HD:</strong> 5GB livres</p>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
