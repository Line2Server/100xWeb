<section class="l2-hero" id="l2Hero">
    <div class="bg-slides" aria-hidden="true">
        <div class="bg-slide active" style="background-image: url('/assets/images/hero-aden.jpg')" data-location="Aden — A Capital do Reino"></div>
        <div class="bg-slide" style="background-image: url('/assets/images/hero-giran.jpg')" data-location="Giran — O Porto Mercante"></div>
    </div>
    <div class="hero-overlay"></div><div class="hero-vignette"></div>
    <div class="hero-content">
        <div class="hero-badge">Servidor Online</div>
        <h1 class="hero-title">ADEN <span>ETERNAL</span></h1>
        <div class="ornament"></div>
        <p class="hero-subtitle">Lineage II Interlude — Servidor PvP</p>
        <div class="server-pills"><div class="pill gold">x100 Rates</div><div class="pill">Interlude</div><div class="pill">PvP</div><div class="pill">Custom Itens</div></div>
        <div class="hero-actions"><a href="/register.php" class="btn btn-primary">Criar Conta</a><a href="/pages/downloads.php" class="btn btn-secondary">Download</a></div>
    </div>
    <div class="location-label" id="locationLabel">Aden — A Capital do Reino</div>
    <div class="slide-indicators" aria-label="Cenários do servidor"><button class="indicator active" data-index="0" aria-label="Aden"></button><button class="indicator" data-index="1" aria-label="Giran"></button></div>
</section>
<script>
(() => {
    const hero = document.getElementById('l2Hero'); if (!hero) return;
    const slides = hero.querySelectorAll('.bg-slide'); const indicators = hero.querySelectorAll('.indicator'); const location = document.getElementById('locationLabel'); let current = 0; let timer;
    const selectSlide = (index) => { slides[current].classList.remove('active'); indicators[current].classList.remove('active'); current = index; slides[current].classList.add('active'); indicators[current].classList.add('active'); location.textContent = slides[current].dataset.location; };
    const start = () => { clearInterval(timer); timer = setInterval(() => selectSlide((current + 1) % slides.length), 7000); };
    indicators.forEach((indicator, index) => indicator.addEventListener('click', () => { selectSlide(index); start(); }));
    hero.addEventListener('mouseenter', () => clearInterval(timer)); hero.addEventListener('mouseleave', start); start();
})();
</script>
