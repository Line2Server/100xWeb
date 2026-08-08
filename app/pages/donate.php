<?php
require_once '../includes/config.php';

// Buscar configurações de donate do banco (se houver)
$donatePackages = [
    [
        'name' => 'Bronze',
        'price' => 10.00,
        'coins' => 10,
        'bonus' => 0,
        'features' => [
            '10 Coins Donate',
            'Buff Scrolls x20',
            'Teleport Scrolls x10',
            'Soulshots x5000',
            'Spiritshots x5000'
        ],
        'disabled' => ['VIP Status', 'Título Custom', 'Item S-Grade'],
        'featured' => false
    ],
    [
        'name' => 'Prata',
        'price' => 30.00,
        'coins' => 35,
        'bonus' => 5,
        'features' => [
            '35 Coins Donate',
            'Armor Set B-Grade',
            'Weapon B-Grade + SA',
            'Buff Scrolls x50',
            'VIP Status 3 dias',
            'Teleport Scrolls x30'
        ],
        'disabled' => ['Título Custom', 'Item S-Grade'],
        'featured' => false
    ],
    [
        'name' => 'Ouro',
        'price' => 50.00,
        'coins' => 65,
        'bonus' => 15,
        'features' => [
            '65 Coins Donate',
            'Full Armor A-Grade',
            'Weapon A-Grade + SA',
            'Buff Scrolls x100',
            'VIP Status 7 dias',
            'Título Colorido',
            'Teleport Scrolls x50'
        ],
        'disabled' => ['Item S-Grade'],
        'featured' => true
    ],
    [
        'name' => 'Diamante',
        'price' => 100.00,
        'coins' => 150,
        'bonus' => 50,
        'features' => [
            '150 Coins Donate',
            'Full Set S-Grade',
            'Weapon S-Grade + SA',
            'Buff Scrolls x200',
            'VIP Status 30 dias',
            'Título Custom + Cor',
            'Teleport Scrolls x100',
            'Enchant Scrolls Blessed x20'
        ],
        'disabled' => [],
        'featured' => false
    ],
    [
        'name' => 'Lendário',
        'price' => 200.00,
        'coins' => 350,
        'bonus' => 150,
        'features' => [
            '350 Coins Donate',
            'Full Set S-Grade +6',
            'Weapon S-Grade +10 + SA',
            'Buff Scrolls x500',
            'VIP Status 60 dias',
            'Título Custom + Cor + Efeito',
            'Teleport Scrolls x200',
            'Enchant Scrolls Crystal x50',
            'Noblesse Instantâneo',
            'Hero Weapon (7 dias)'
        ],
        'disabled' => [],
        'featured' => false
    ]
];

// Itens individuais da loja
$shopItems = [
    ['name' => 'Coin Donate', 'price' => 1.00, 'desc' => '1 Coin para gastar na loja'],
    ['name' => 'Enchant Scroll Blessed', 'price' => 5.00, 'desc' => '+1 sem quebrar o item'],
    ['name' => 'Enchant Scroll Crystal', 'price' => 10.00, 'desc' => 'Chance aumentada de +1'],
    ['name' => 'Clan Reputation +1000', 'price' => 15.00, 'desc' => 'Aumenta reputação do clã'],
    ['name' => 'Name Change Scroll', 'price' => 20.00, 'desc' => 'Troca o nome do personagem'],
    ['name' => 'Gender Change', 'price' => 25.00, 'desc' => 'Troca o sexo do personagem'],
    ['name' => 'Subclass Certificate', 'price' => 50.00, 'desc' => 'Adiciona uma subclass extra'],
    ['name' => 'Hero Weapon (7d)', 'price' => 100.00, 'desc' => 'Arma Hero temporária'],
    ['name' => 'Noblesse Tiara', 'price' => 80.00, 'desc' => 'Torna Noblesse instantâneo'],
    ['name' => 'Custom Title Color', 'price' => 30.00, 'desc' => 'Título com cor customizada'],
];

require_once '../includes/header.php';
?>

<style>
.donate-methods { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 60px; }
.donate-method { background: var(--dark-card); border: 1px solid var(--dark-border); border-radius: 16px; padding: 28px; text-align: center; transition: var(--transition); }
.donate-method:hover { border-color: rgba(201,168,76,0.2); transform: translateY(-3px); }
.donate-method-icon { width: 56px; height: 56px; margin: 0 auto 16px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
.donate-method-title { font-family: var(--font-display); font-size: 1.1rem; color: var(--text-bright); margin-bottom: 8px; }
.donate-method-desc { font-size: 0.85rem; color: var(--text-muted); }
.shop-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; }
.shop-item { background: var(--dark-card); border: 1px solid var(--dark-border); border-radius: 12px; padding: 20px; text-align: center; transition: var(--transition); }
.shop-item:hover { border-color: rgba(201,168,76,0.2); transform: translateY(-3px); }
.shop-item-name { font-weight: 600; color: var(--text-bright); font-size: 0.9rem; margin-bottom: 4px; }
.shop-item-desc { font-size: 0.75rem; color: var(--text-muted); margin-bottom: 12px; }
.shop-item-price { font-family: var(--font-display); font-size: 1.2rem; color: var(--gold); font-weight: 700; }
@media (max-width: 1024px) { .donate-methods { grid-template-columns: 1fr; } .shop-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 768px) { .shop-grid { grid-template-columns: repeat(2, 1fr); } }
</style>

<section class="section" style="padding-top: 40px;">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Apoie o Servidor</span>
            <h2 class="section-title">Sistema de Doações</h2>
            <p class="section-desc">100% do valor é reinvestido no servidor. Sem pay-to-win, apenas conveniência e cosméticos.</p>
        </div>

        <!-- Métodos de Pagamento -->
        <div class="donate-methods">
            <div class="donate-method">
                <div class="donate-method-icon" style="background: rgba(0,150,80,0.1); color: #00c853;">💠</div>
                <div class="donate-method-title">PIX</div>
                <div class="donate-method-desc">Pagamento instantâneo via QR Code. Crédito imediato na conta.</div>
            </div>
            <div class="donate-method">
                <div class="donate-method-icon" style="background: rgba(0,100,200,0.1); color: #2196f3;">💳</div>
                <div class="donate-method-title">Mercado Pago</div>
                <div class="donate-method-desc">Cartão de crédito, boleto e saldo MP. Link de checkout seguro.</div>
            </div>
            <div class="donate-method">
                <div class="donate-method-icon" style:="background: rgba(201,168,76,0.1); color: var(--gold);">₿</div>
                <div class="donate-method-title">Cripto</div>
                <div class="donate-method-desc">BTC, ETH, USDT via Binance Pay. Cotação em tempo real.</div>
            </div>
        </div>

        <!-- Pacotes -->
        <div style="text-align: center; margin-bottom: 40px;">
            <h3 style="font-family: var(--font-display); font-size: 1.8rem; color: var(--text-bright); margin-bottom: 8px;">Pacotes de Doação</h3>
            <p style="color: var(--text-muted);">Escolha o pacote ideal para você</p>
        </div>

        <div class="donate-grid">
            <?php foreach ($donatePackages as $pkg): ?>
            <div class="donate-card <?php echo $pkg['featured'] ? 'featured' : ''; ?>">
                <?php if ($pkg['featured']): ?><div style="height: 14px;"></div><?php endif; ?>
                <div class="donate-tier"><?php echo $pkg['name']; ?></div>
                <div class="donate-price">R$ <?php echo number_format($pkg['price'], 2, ',', '.'); ?></div>
                <div class="donate-coins">
                    <?php echo $pkg['coins']; ?> Coins 
                    <?php if ($pkg['bonus'] > 0): ?>
                    <span style="color: var(--green-glow);">+<?php echo $pkg['bonus']; ?> Bônus</span>
                    <?php endif; ?>
                </div>
                <ul class="donate-features">
                    <?php foreach ($pkg['features'] as $feat): ?>
                    <li><?php echo $feat; ?></li>
                    <?php endforeach; ?>
                    <?php foreach ($pkg['disabled'] as $dis): ?>
                    <li class="disabled"><?php echo $dis; ?></li>
                    <?php endforeach; ?>
                </ul>
                <button class="btn <?php echo $pkg['featured'] ? 'btn-primary' : 'btn-secondary'; ?>" style="width:100%;justify-content:center;" onclick="alert('Digite .donation no jogo para acessar o sistema de doações!')">
                    Comprar Agora
                </button>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Loja de Itens -->
        <div style="text-align: center; margin: 80px 0 40px;">
            <h3 style="font-family: var(--font-display); font-size: 1.8rem; color: var(--text-bright); margin-bottom: 8px;">Loja de Itens</h3>
            <p style="color: var(--text-muted);">Compre itens individuais com seus Coins</p>
        </div>

        <div class="shop-grid">
            <?php foreach ($shopItems as $item): ?>
            <div class="shop-item">
                <div class="shop-item-name"><?php echo $item['name']; ?></div>
                <div class="shop-item-desc"><?php echo $item['desc']; ?></div>
                <div class="shop-item-price">R$ <?php echo number_format($item['price'], 2, ',', '.'); ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Instruções -->
        <div style="background: var(--dark-card); border: 1px solid var(--dark-border); border-radius: 16px; padding: 32px; margin-top: 60px;">
            <h4 style="font-family: var(--font-display); color: var(--gold); margin-bottom: 16px; font-size: 1.2rem;">📋 Como Doar</h4>
            <ol style="color: var(--text); line-height: 2; padding-left: 20px;">
                <li>Entre no jogo com seu personagem</li>
                <li>Digite o comando <code style="background: rgba(201,168,76,0.1); padding: 2px 8px; border-radius: 4px; color: var(--gold);">.donation</code> ou <code style="background: rgba(201,168,76,0.1); padding: 2px 8px; border-radius: 4px; color: var(--gold);">.pix</code></li>
                <li>Escolha o pacote ou quantidade de Coins desejada</li>
                <li>Selecione o método de pagamento (PIX, MP ou Crypto)</li>
                <li>Complete o pagamento e os Coins serão entregues automaticamente</li>
                <li>Use <code style="background: rgba(201,168,76,0.1); padding: 2px 8px; border-radius: 4px; color: var(--gold);">.donation</code> novamente para gastar seus Coins na loja</li>
            </ol>
            <p style="color: var(--text-muted); margin-top: 16px; font-size: 0.85rem;">
                ⚠️ Em caso de problemas, entre em contato via Discord ou WhatsApp. Suporte 24/7.
            </p>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
