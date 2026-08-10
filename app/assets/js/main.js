/**
 * Aden Eternal L2 - Main JavaScript
 * Otimizado para performance em máquinas básicas
 */

(function() {
    'use strict';

    // === PARTICLES SYSTEM (Canvas) ===
    const particlesContainer = document.getElementById('particles');
    if (particlesContainer) {
        const canvas = document.createElement('canvas');
        canvas.style.cssText = 'position:absolute;top:0;left:0;width:100%;height:100%;';
        particlesContainer.appendChild(canvas);

        const ctx = canvas.getContext('2d');
        let particles = [];
        const maxParticles = window.innerWidth < 768 ? 25 : 50;

        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        resize();
        window.addEventListener('resize', resize, { passive: true });

        class Particle {
            constructor() {
                this.reset();
            }
            reset() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 0.5;
                this.speedX = (Math.random() - 0.5) * 0.3;
                this.speedY = (Math.random() - 0.5) * 0.3;
                this.opacity = Math.random() * 0.5 + 0.1;
                this.color = Math.random() > 0.7 ? '201, 168, 76' : '192, 57, 43';
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                if (this.x < 0 || this.x > canvas.width || this.y < 0 || this.y > canvas.height) {
                    this.reset();
                }
            }
            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(${this.color}, ${this.opacity})`;
                ctx.fill();
            }
        }

        for (let i = 0; i < maxParticles; i++) {
            particles.push(new Particle());
        }

        let frameCount = 0;
        function animate() {
            frameCount++;
            // Render a cada 2 frames para economizar CPU
            if (frameCount % 2 === 0) {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                particles.forEach(p => {
                    p.update();
                    p.draw();
                });
            }
            requestAnimationFrame(animate);
        }
        animate();
    }

    // === MOBILE MENU ===
    const mobileToggle = document.querySelector('.mobile-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            mobileToggle.classList.toggle('active');
        });

        // Fechar menu ao clicar em link
        navMenu.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
                mobileToggle.classList.remove('active');
            });
        });
    }

    // === SMOOTH SCROLL ===
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // === INTERSECTION OBSERVER (Scroll Animations) ===
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.feature-card, .stat-card, .donate-card, .download-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
        observer.observe(el);
    });

    // === LIVE PLAYER COUNT UPDATE ===
    function updatePlayerCount() {
        fetch('api/status.php')
            .then(r => r.json())
            .then(data => {
                const countEl = document.querySelector('.players-count');
                if (countEl && data.online !== undefined) {
                    const numEl = countEl.querySelector('span:last-child') || countEl.childNodes[countEl.childNodes.length - 1];
                    if (numEl) numEl.textContent = ` ${data.online} Online`;
                }

                const statusEl = document.querySelector('.status-indicator');
                if (statusEl) {
                    statusEl.className = `status-indicator ${data.status === 'online' ? 'online' : 'offline'}`;
                    const textEl = statusEl.querySelector('span:last-child') || statusEl.childNodes[statusEl.childNodes.length - 1];
                    if (textEl) textEl.textContent = data.status === 'online' ? 'ONLINE' : 'OFFLINE';
                }
            })
            .catch(() => {});
    }

    // Atualizar a cada 30 segundos
    if (document.querySelector('.players-count')) {
        setInterval(updatePlayerCount, 30000);
    }

    // === RANKINGS TABS ===
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.dataset.tab;

            tabBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            tabContents.forEach(c => {
                c.style.display = c.id === target ? 'block' : 'none';
            });
        });
    });

    // === COPY TO CLIPBOARD ===
    document.querySelectorAll('[data-copy]').forEach(el => {
        el.addEventListener('click', () => {
            const text = el.dataset.copy;
            navigator.clipboard.writeText(text).then(() => {
                const original = el.textContent;
                el.textContent = 'Copiado!';
                setTimeout(() => el.textContent = original, 2000);
            });
        });
    });

    // === NAVBAR SCROLL EFFECT ===
    let lastScroll = 0;
    const nav = document.querySelector('.main-nav');

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;

        if (currentScroll > 100) {
            nav.style.background = 'rgba(10, 10, 15, 0.98)';
        } else {
            nav.style.background = 'transparent';
        }

        lastScroll = currentScroll;
    }, { passive: true });

    // === LAZY LOAD IMAGES ===
    if ('IntersectionObserver' in window) {
        const imgObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imgObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => imgObserver.observe(img));
    }

})();
