<?php
require_once '../includes/config.php';

require_once '../includes/header.php';
?>

<style>
.info-content p { color: var(--text-muted); line-height: 1.8; margin-bottom: 16px; }
.info-content ul { color: var(--text-muted); line-height: 2; padding-left: 20px; margin-bottom: 16px; }
.info-content li::marker { color: var(--gold); }
.info-content strong { color: var(--text-bright); }
.info-content code { background: rgba(201,168,76,0.1); padding: 2px 8px; border-radius: 4px; color: var(--gold); font-size: 0.9rem; }
.warning-box { background: rgba(192,57,43,0.08); border: 1px solid rgba(192,57,43,0.2); border-radius: 12px; padding: 20px; margin: 20px 0; }
.warning-box p { color: var(--red-glow) !important; margin: 0 !important; }
.tip-box { background: rgba(39,174,96,0.08); border: 1px solid rgba(39,174,96,0.2); border-radius: 12px; padding: 20px; margin: 20px 0; }
.tip-box p { color: var(--green-glow) !important; margin: 0 !important; }
.command-list { background: var(--dark-card); border: 1px solid var(--dark-border); border-radius: 12px; overflow: hidden; }
.command-row { display: grid; grid-template-columns: 200px 1fr; padding: 14px 20px; border-bottom: 1px solid var(--dark-border); }
.command-row:last-child { border-bottom: none; }
.command-row code { background: rgba(201,168,76,0.1); padding: 4px 10px; border-radius: 6px; color: var(--gold); font-size: 0.85rem; }
.command-row span { color: var(--text-muted); font-size: 0.9rem; }
@media (max-width: 768px) { .command-row { grid-template-columns: 1fr; gap: 6px; } }
</style>

<section class="section" style="padding-top: 40px;">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Informações</span>
            <h2 class="section-title">Tudo Sobre o Servidor</h2>
            <p class="section-desc">Rates, comandos, regras e tudo que você precisa saber.</p>
        </div>

        <div class="info-grid">
            <aside class="info-sidebar">
                <ul class="info-nav">
                    <li><a href="#rates" class="active">📊 Rates</a></li>
                    <li><a href="#commands">⌨️ Comandos</a></li>
                    <li><a href="#events">🎉 Eventos</a></li>
                    <li><a href="#rules">📜 Regras</a></li>
                    <li><a href="#classes">⚔️ Classes</a></li>
                    <li><a href="#enchant">✨ Enchant</a></li>
                    <li><a href="#bosses">👹 Bosses</a></li>
                    <li><a href="#siege">🏰 Siege</a></li>
                    <li><a href="#vip">👑 VIP</a></li>
                    <li><a href="#faq">❓ FAQ</a></li>
                </ul>
            </aside>

            <div class="info-content">
                <!-- RATES -->
                <h3 id="rates">📊 Rates do Servidor</h3>
                <table class="info-table">
                    <tr><th>Configuração</th><th>Valor</th></tr>
                    <tr><td>XP Rate</td><td><strong>x50</strong></td></tr>
                    <tr><td>SP Rate</td><td><strong>x50</strong></td></tr>
                    <tr><td>Adena Rate</td><td><strong>x30</strong></td></tr>
                    <tr><td>Drop Rate</td><td><strong>x15</strong></td></tr>
                    <tr><td>Spoil Rate</td><td><strong>x20</strong></td></tr>
                    <tr><td>Quest XP/SP</td><td><strong>x2</strong></td></tr>
                    <tr><td>Quest Adena</td><td><strong>x15</strong></td></tr>
                    <tr><td>Party XP Bonus</td><td><strong>x1.5</strong></td></tr>
                    <tr><td>Buff Slots</td><td><strong>24 + 12 (Dances)</strong></td></tr>
                    <tr><td>Buff Duration</td><td><strong>2 horas</strong></td></tr>
                    <tr><td>Max Level</td><td><strong>80 (com Subclass)</strong></td></tr>
                    <tr><td>Subclass</td><td><strong>Level 75</strong></td></tr>
                    <tr><td>Noblesse</td><td><strong>Quest ou Donate</strong></td></tr>
                </table>

                <!-- COMMANDS -->
                <h3 id="commands">⌨️ Comandos do Jogador</h3>
                <div class="command-list">
                    <div class="command-row"><code>.menu</code><span>Abre o menu principal do servidor</span></div>
                    <div class="command-row"><code>.donation</code><span>Sistema de doações e loja VIP</span></div>
                    <div class="command-row"><code>.pix</code><span>Gera pagamento PIX (alternativa)</span></div>
                    <div class="command-row"><code>.autofarm</code><span>Ativa/desativa o AutoFarm</span></div>
                    <div class="command-row"><code>.offline</code><span>Ativa loja offline</span></div>
                    <div class="command-row"><code>.repair</code><span>Repara itens equipados</span></div>
                    <div class="command-row"><code>.buffstore</code><span>Abre loja de buffs</span></div>
                    <div class="command-row"><code>.dressme</code><span>Altera aparência do equipamento</span></div>
                    <div class="command-row"><code>.tournament</code><span>Informações sobre torneios</span></div>
                    <div class="command-row"><code>.rank</code><span>Mostra seu ranking PvP</span></div>
                    <div class="command-row"><code>.clan</code><span>Informações do clã</span></div>
                    <div class="command-row"><code>.info</code><span>Informações do servidor</span></div>
                    <div class="command-row"><code>.boss</code><span>Status dos bosses</span></div>
                    <div class="command-row"><code>.siege</code><span>Informações de siege</span></div>
                    <div class="command-row"><code>.help</code><span>Lista todos os comandos</span></div>
                </div>

                <!-- EVENTS -->
                <h3 id="events">🎉 Eventos Automáticos</h3>
                <table class="info-table">
                    <tr><th>Evento</th><th>Intervalo</th><th>Recompensa</th></tr>
                    <tr><td>Team vs Team (TvT)</td><td>A cada 1 hora</td><td>100k Adena + Coins</td></tr>
                    <tr><td>Death Match (DM)</td><td>A cada 2 horas</td><td>150k Adena + Coins</td></tr>
                    <tr><td>Capture The Flag</td><td>A cada 3 horas</td><td>120k Adena + Coins</td></tr>
                    <tr><td>Last Man Standing</td><td>A cada 4 horas</td><td>200k Adena + Coins</td></tr>
                    <tr><td>Random Farm Event</td><td>Aleatório</td><td>XP/SP Boost + Itens</td></tr>
                    <tr><td>Battle Boss</td><td>Countdown diário</td><td>Loot exclusivo do Boss</td></tr>
                </table>

                <!-- RULES -->
                <h3 id="rules">📜 Regras do Servidor</h3>
                <div class="warning-box">
                    <p>⚠️ Quebra das regras pode resultar em banimento permanente sem aviso prévio.</p>
                </div>
                <ul>
                    <li><strong>Uso de cheats/hacks:</strong> Banimento permanente de HWID + conta</li>
                    <li><strong>Multi-client (mais de 2 boxes):</strong> Banimento de HWID</li>
                    <li><strong>Venda de itens/adena por dinheiro real:</strong> Banimento permanente</li>
                    <li><strong>Abuso de bugs:</strong> Banimento + rollback de personagem</li>
                    <li><strong>Comportamento tóxico:</strong> Mute temporário ou permanente</li>
                    <li><strong>Scam entre jogadores:</strong> Banimento de 7 a 30 dias</li>
                    <li><strong>Nicknames ofensivos:</strong> Renomeação forçada + mute</li>
                    <li><strong>Spam no chat global:</strong> Mute progressivo (1h → 24h → 7d → permanente)</li>
                </ul>

                <!-- CLASSES -->
                <h3 id="classes">⚔️ Balanceamento de Classes</h3>
                <p>Todas as classes foram revisadas para garantir competitividade no PvP. O balanceamento é feito através de ajustes nos multiplicadores de dano/defesa por <code>ClassId</code>.</p>
                <table class="info-table">
                    <tr><th>Tier</th><th>Classes</th><th>Descrição</th></tr>
                    <tr><td>S</td><td>Duelist, Grand Khavatari, Ghost Hunter</td><td>Alto dano melee, requer skill</td></tr>
                    <tr><td>A</td><td>Archmage, Soultaker, Storm Screamer</td><td>Dano mágico devastador</td></tr>
                    <tr><td>A</td><td>Sagittarius, Ghost Sentinel</td><td>Dano à distância, posicionamento</td></tr>
                    <tr><td>B</td><td>Phoenix Knight, Shillien Templar, Eva's Templar</td><td>Tanques com suporte</td></tr>
                    <tr><td>B</td><td>Hierophant, Eva's Saint, Shillien Saint</td><td>Suporte essencial</td></tr>
                    <tr><td>C</td><td>Fortune Seeker, Maestro</td><td>Especialistas em crafting/economia</td></tr>
                </table>

                <!-- ENCHANT -->
                <h3 id="enchant">✨ Sistema de Enchant</h3>
                <table class="info-table">
                    <tr><th>Configuração</th><th>Valor</th></tr>
                    <tr><td>Enchant Safe (Armor/Weapon)</td><td><strong>+4</strong></td></tr>
                    <tr><td>Enchant Máximo</td><td><strong>+16</strong></td></tr>
                    <tr><td>Chance Normal Weapon</td><td><strong>65%</strong></td></tr>
                    <tr><td>Chance Normal Armor</td><td><strong>65%</strong></td></tr>
                    <tr><td>Chance Normal Jewelry</td><td><strong>55%</strong></td></tr>
                    <tr><td>Chance Blessed Weapon</td><td><strong>70%</strong></td></tr>
                    <tr><td>Chance Blessed Armor</td><td><strong>70%</strong></td></tr>
                    <tr><td>Chance Crystal Weapon</td><td><strong>75%</strong></td></tr>
                    <tr><td>Chance Crystal Armor</td><td><strong>75%</strong></td></tr>
                    <tr><td>Comportamento Blessed</td><td><strong>Não quebra, volta +0</strong></td></tr>
                </table>

                <!-- BOSSES -->
                <h3 id="bosses">👹 Bosses e Raids</h3>
                <table class="info-table">
                    <tr><th>Boss</th><th>Level</th><th>Respawn</th><th>Local</th></tr>
                    <tr><td>Queen Ant</td><td>40</td><td>24h</td><td>Ant Nest</td></tr>
                    <tr><td>Core</td><td>50</td><td>48h</td><td>Core Room</td></tr>
                    <tr><td>Orfen</td><td>50</td><td>48h</td><td>Sea of Spores</td></tr>
                    <tr><td>Zaken</td><td>60</td><td>48h</td><td>Devil's Isle</td></tr>
                    <tr><td>Baium</td><td>75</td><td>120h</td><td>Tower of Insolence</td></tr>
                    <tr><td>Antharas</td><td>79</td><td>264h</td><td>Dragon Valley</td></tr>
                    <tr><td>Valakas</td><td>79</td><td>264h</td><td>Forge of the Gods</td></tr>
                    <tr><td>Frintezza</td><td>80</td><td>48h</td><td>Imperial Tomb</td></tr>
                </table>
                <div class="tip-box">
                    <p>💡 Anti-Zerg ativo: mais de 15 jogadores no mesmo boss ativa flag PvP automático para todos.</p>
                </div>

                <!-- SIEGE -->
                <h3 id="siege">🏰 Castle Siege</h3>
                <p>Sieges acontecem todo <strong>sábado às 20:00 (BRT)</strong>. Castelos disputados:</p>
                <ul>
                    <li><strong>Aden Castle</strong> - O coração do reino. Maior renda de impostos.</li>
                    <li><strong>Giran Castle</strong> - Centro comercial. Bônus de taxa de mercado.</li>
                    <li><strong>Dion Castle</strong> - Fortaleza defensiva. Bônus de defesa para defensores.</li>
                    <li><strong>Gludio Castle</strong> - Castelo inicial. Bônus de XP para membros do clã.</li>
                    <li><strong>Innadril Castle</strong> - Porto estratégico. Bônus de pesca e crafting.</li>
                    <li><strong>Oren Castle</strong> - Fronteira norte. Bônus de drop em zonas PvP.</li>
                    <li><strong>Schuttgart Castle</strong> - Fortaleza anã. Bônus de mineração.</li>
                    <li><strong>Godard Castle</strong> - Castelo do norte. Bônus de resistência ao frio.</li>
                    <li><strong>Rune Castle</strong> - Capital dos orcs. Bônus de força física.</li>
                </ul>

                <!-- VIP -->
                <h3 id="vip">👑 Sistema VIP</h3>
                <table class="info-table">
                    <tr><th>Benefício</th><th>VIP</th><th>Normal</th></tr>
                    <tr><td>Bônus XP</td><td><strong>x1.5</strong></td><td>x1.0</td></tr>
                    <tr><td>Bônus SP</td><td><strong>x1.5</strong></td><td>x1.0</td></tr>
                    <tr><td>Bônus Drop</td><td><strong>x1.3</strong></td><td>x1.0</td></tr>
                    <tr><td>Bônus Adena</td><td><strong>x1.2</strong></td><td>x1.0</td></tr>
                    <tr><td>Buff Slots</td><td><strong>28 + 12</strong></td><td>24 + 12</td></tr>
                    <tr><td>Teleport Cost</td><td><strong>-50%</strong></td><td>Normal</td></tr>
                    <tr><td>Zona VIP</td><td><strong>✅ Acesso</strong></td><td>❌</td></tr>
                    <tr><td>Offline Shop</td><td><strong>Ilimitado</strong></td><td>24h</td></tr>
                    <tr><td>AutoFarm</td><td><strong>Rotas ilimitadas</strong></td><td>3 rotas</td></tr>
                </table>

                <!-- FAQ -->
                <h3 id="faq">❓ Perguntas Frequentes</h3>
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="background: var(--dark-card); border: 1px solid var(--dark-border); border-radius: 12px; padding: 20px;">
                        <h4 style="color: var(--gold); margin-bottom: 8px; font-size: 1rem;">Como crio uma conta?</h4>
                        <p style="margin: 0; color: var(--text-muted); font-size: 0.9rem;">Basta abrir o jogo e criar sua conta diretamente na tela de login. Use um e-mail válido para recuperação de senha.</p>
                    </div>
                    <div style="background: var(--dark-card); border: 1px solid var(--dark-border); border-radius: 12px; padding: 20px;">
                        <h4 style="color: var(--gold); margin-bottom: 8px; font-size: 1rem;">Posso jogar de mais de um PC?</h4>
                        <p style="margin: 0; color: var(--text-muted); font-size: 0.9rem;">Sim, cada jogador pode ter até <strong>2 boxes</strong> (personagens simultâneos). Mais que isso resulta em banimento de HWID.</p>
                    </div>
                    <div style="background: var(--dark-card); border: 1px solid var(--dark-border); border-radius: 12px; padding: 20px;">
                        <h4 style="color: var(--gold); margin-bottom: 8px; font-size: 1rem;">Como funciona o donate?</h4>
                        <p style="margin: 0; color: var(--text-muted); font-size: 0.9rem;">Dentro do jogo, digite <code>.donation</code> e escolha seu método de pagamento. Os Coins são entregues automaticamente após confirmação.</p>
                    </div>
                    <div style="background: var(--dark-card); border: 1px solid var(--dark-border); border-radius: 12px; padding: 20px;">
                        <h4 style="color: var(--gold); margin-bottom: 8px; font-size: 1rem;">O servidor é pay-to-win?</h4>
                        <p style="margin: 0; color: var(--text-muted); font-size: 0.9rem;"><strong>Não.</strong> O donate oferece conveniência e cosméticos. Todo equipamento S-Grade pode ser obtido in-game através de farm, bosses e eventos.</p>
                    </div>
                    <div style="background: var(--dark-card); border: 1px solid var(--dark-border); border-radius: 12px; padding: 20px;">
                        <h4 style="color: var(--gold); margin-bottom: 8px; font-size: 1rem;">Perdi minha senha, o que fazer?</h4>
                        <p style="margin: 0; color: var(--text-muted); font-size: 0.9rem;">Entre em contato via Discord ou WhatsApp com seu login e e-mail cadastrado. A equipe de suporte irá auxiliar na recuperação.</p>
                    </div>
                    <div style="background: var(--dark-card); border: 1px solid var(--dark-border); border-radius: 12px; padding: 20px;">
                        <h4 style="color: var(--gold); margin-bottom: 8px; font-size: 1rem;">Qual o horário dos eventos?</h4>
                        <p style="margin: 0; color: var(--text-muted); font-size: 0.9rem;">Todos os horários são em <strong>BRT (UTC-3)</strong>. Eventos automáticos rodam conforme a tabela acima. Sieges aos sábados às 20:00.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Smooth scroll para links do sidebar
document.querySelectorAll('.info-nav a').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            document.querySelectorAll('.info-nav a').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        }
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
