<?php
require_once '../includes/config.php';

$roadmap = [
    ['phase'=>'Fase 1','title'=>'Beta Fechada','date'=>'Concluído','desc'=>'Testes internos com 50 jogadores. Balanceamento inicial, correção de bugs críticos e ajuste de rates.','status'=>'completed'],
    ['phase'=>'Fase 2','title'=>'Soft Launch','date'=>'Concluído','desc'=>'Abertura para 200 jogadores. Monitoramento de performance, ajustes de economia e primeiro evento.','status'=>'completed'],
    ['phase'=>'Fase 3','title'=>'Lançamento Oficial','date'=>'Agosto 2025','desc'=>'Servidor aberto para todos. Evento de lançamento com 2x XP, torneio inaugural e giveaways.','status'=>'active'],
    ['phase'=>'Fase 4','title'=>'Primeira Grande Atualização','date'=>'Setembro 2025','desc'=>'Sistema de arenas PvP com matchmaking ELO, season rankings e recompensas exclusivas.','status'=>'pending'],
    ['phase'=>'Fase 5','title'=>'Expansão de Conteúdo','date'=>'Outubro 2025','desc'=>'Novas dungeons customizadas, sistema de achievements e eventos sazonais (Halloween).','status'=>'pending'],
    ['phase'=>'Fase 6','title'=>'Competitivo e Torneios','date'=>'Novembro 2025','desc'=>'Campeonato oficial de clãs com premiação. Sistema de transmissão ao vivo integrado.','status'=>'pending'],
    ['phase'=>'Fase 7','title'=>'Natal e Ano Novo','date'=>'Dezembro 2025','desc'=>'Evento de Natal com mapa exclusivo, boss especial e celebração de 6 meses do servidor.','status'=>'pending'],
    ['phase'=>'Fase 8','title'=>'Renovação e Futuro','date'=>'2026','desc'=>'Análise de migração para próxima chronicle. Votação da comunidade para próximos passos.','status'=>'pending']
];

require_once '../includes/header.php';
?>

<section class="section" style="padding-top: 40px;">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Nosso Caminho</span>
            <h2 class="section-title">Roadmap do Servidor</h2>
            <p class="section-desc">A jornada de Eternal War está apenas começando. Veja o que planejamos.</p>
        </div>
        <div class="roadmap-timeline">
            <?php foreach ($roadmap as $item): ?>
            <div class="roadmap-item">
                <div class="roadmap-dot <?php echo $item['status']; ?>"></div>
                <div class="roadmap-content">
                    <div class="roadmap-date"><?php echo $item['date']; ?></div>
                    <h3 class="roadmap-title"><?php echo $item['title']; ?></h3>
                    <p class="roadmap-desc"><?php echo $item['desc']; ?></p>
                    <span class="roadmap-status status-<?php echo $item['status']; ?>">
                        <?php echo $item['status']=='completed'?'✅ Concluído':($item['status']=='active'?'🔄 Em Andamento':'⏳ Planejado'); ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center;margin-top:60px;padding:40px;background:var(--dark-card);border:1px solid var(--dark-border);border-radius:16px;">
            <h3 style="font-family:var(--font-display);color:var(--gold);margin-bottom:12px;">💡 Sua Opinião Importa</h3>
            <p style="color:var(--text-muted);margin-bottom:20px;">Tem uma ideia para o servidor? Quer ver algum recurso implementado?</p>
            <a href="<?php echo DISCORD_INVITE; ?>" target="_blank" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/></svg>
                Sugerir no Discord
            </a>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
