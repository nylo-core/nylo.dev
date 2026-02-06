import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

// Search modal store
Alpine.store('search', {
    open: false,
    query: '',
    selectedIndex: 0,

    toggle() {
        this.open = !this.open;
        this.query = '';
        this.selectedIndex = 0;
    },

    close() {
        this.open = false;
        this.query = '';
        this.selectedIndex = 0;
    }
});

// Dark mode store with localStorage persistence
Alpine.store('darkMode', {
    on: localStorage.getItem('darkMode') === 'true' ||
        (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches),

    init() {
        // Apply dark mode class on page load
        document.documentElement.classList.toggle('dark', this.on);
    },

    toggle() {
        this.on = !this.on;
        localStorage.setItem('darkMode', this.on);
        document.documentElement.classList.toggle('dark', this.on);
    }
});

// Typewriter animation component
Alpine.data('typewriter', (phrases = [], speed = 100) => ({
    text: '',
    phrases: phrases,
    currentPhrase: 0,
    currentChar: 0,
    isDeleting: false,
    typeSpeed: speed,
    deleteSpeed: 50,
    pauseTime: 2000,
    cursorVisible: true,

    init() {
        this.type();
        // Blink cursor
        setInterval(() => {
            this.cursorVisible = !this.cursorVisible;
        }, 530);
    },

    type() {
        if (this.phrases.length === 0) return;

        const phrase = this.phrases[this.currentPhrase];

        if (this.isDeleting) {
            this.text = phrase.substring(0, this.currentChar - 1);
            this.currentChar--;
        } else {
            this.text = phrase.substring(0, this.currentChar + 1);
            this.currentChar++;
        }

        if (!this.isDeleting && this.currentChar === phrase.length) {
            setTimeout(() => {
                this.isDeleting = true;
                this.type();
            }, this.pauseTime);
            return;
        }

        if (this.isDeleting && this.currentChar === 0) {
            this.isDeleting = false;
            this.currentPhrase = (this.currentPhrase + 1) % this.phrases.length;
        }

        setTimeout(() => this.type(), this.isDeleting ? this.deleteSpeed : this.typeSpeed);
    }
}));

// Counter animation component for stats
Alpine.data('counter', (target = 0, duration = 2000) => ({
    count: 0,
    target: target,
    duration: duration,
    started: false,

    init() {
        // Use Intersection Observer to trigger animation when visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !this.started) {
                    this.started = true;
                    this.animate();
                }
            });
        }, { threshold: 0.5 });

        observer.observe(this.$el);
    },

    animate() {
        const start = 0;
        const end = this.target;
        const startTime = performance.now();

        const step = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / this.duration, 1);
            // Ease out cubic
            const easeOut = 1 - Math.pow(1 - progress, 3);
            this.count = Math.floor(easeOut * (end - start) + start);

            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                this.count = end;
            }
        };

        requestAnimationFrame(step);
    }
}));

Alpine.plugin(collapse);

// Global theme setter function
window.setTheme = function(theme) {
    if (theme === 'system') {
        localStorage.removeItem('darkMode');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        Alpine.store('darkMode').on = prefersDark;
        document.documentElement.classList.toggle('dark', prefersDark);
    } else if (theme === 'dark') {
        localStorage.setItem('darkMode', 'true');
        Alpine.store('darkMode').on = true;
        document.documentElement.classList.add('dark');
    } else {
        localStorage.setItem('darkMode', 'false');
        Alpine.store('darkMode').on = false;
        document.documentElement.classList.remove('dark');
    }
};

Alpine.start();
