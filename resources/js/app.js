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
        document.documentElement.classList.toggle('dark', this.on);

        // Follow system appearance changes when user hasn't explicitly chosen
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (localStorage.getItem('darkMode') === null) {
                this.on = e.matches;
                document.documentElement.classList.toggle('dark', e.matches);
            }
        });
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

// Install bar: after the first Copy, the command morphs into `nylo new <app>`
// and cycles example app names so devs learn the next command to run.
Alpine.data('installDemo', (command, appNames = []) => ({
    copied: false,
    base: command,
    appName: '',
    started: false,

    copy() {
        window.nyCopyText(command);
        this.copied = true;
        setTimeout(() => this.copied = false, 1800);

        if (this.started || appNames.length === 0) return;
        this.started = true;
        this.run();
    },

    wait(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    },

    async run() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            // No typewriter: swap the command and rotate names in place.
            await this.wait(1400);
            this.base = 'nylo new ';
            for (let i = 0; ; i = (i + 1) % appNames.length) {
                this.appName = appNames[i];
                await this.wait(3200);
            }
        }

        // Let the "Copied" state land, then backspace the install command.
        await this.wait(900);
        while (this.base.length > 0) {
            this.base = this.base.slice(0, -1);
            await this.wait(14);
        }
        await this.wait(260);

        for (const char of 'nylo new ') {
            this.base += char;
            await this.wait(55);
        }

        for (let i = 0; ; i = (i + 1) % appNames.length) {
            for (const char of appNames[i]) {
                this.appName += char;
                await this.wait(70);
            }
            await this.wait(2100);
            while (this.appName.length > 0) {
                this.appName = this.appName.slice(0, -1);
                await this.wait(38);
            }
            await this.wait(340);
        }
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

// Inline scripts (e.g. the ⌘K shortcut) reach the stores through this global.
window.Alpine = Alpine;

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
