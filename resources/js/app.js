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

// Split a millisecond gap into whole days, hours, minutes and seconds.
function countdownParts(ms) {
    const total = Math.max(0, Math.floor(ms / 1000));

    return {
        days: Math.floor(total / 86400),
        hours: Math.floor((total % 86400) / 3600),
        minutes: Math.floor((total % 3600) / 60),
        seconds: total % 60,
    };
}

// Site-wide event bar: ticks down to the next online event, flips to a live
// state while it runs, and closes itself once it is over. The dismissal is
// keyed by slug so next quarter's event shows again to everyone.
Alpine.data('eventBanner', (slug, startsAt, endsAt) => ({
    open: false,
    live: false,
    countdown: '',
    key: `ny-event-dismissed:${slug}`,
    timer: null,

    init() {
        this.start = new Date(startsAt).getTime();
        this.end = new Date(endsAt).getTime();

        try {
            this.open = localStorage.getItem(this.key) !== '1';
        } catch (e) {
            this.open = true;
        }

        this.tick();

        if (this.open) {
            this.timer = setInterval(() => this.tick(), 1000);
        }
    },

    destroy() {
        clearInterval(this.timer);
    },

    tick() {
        const now = Date.now();

        // A tab left open past the end of the event shouldn't keep promoting it.
        if (now > this.end) {
            this.open = false;
            clearInterval(this.timer);
            return;
        }

        this.live = now >= this.start;

        const { days, hours, minutes, seconds } = countdownParts(this.start - now);

        // Drop the leading units as they empty, so the pill stays short.
        if (days > 0) {
            this.countdown = `${days}d ${hours}h ${minutes}m ${seconds}s`;
        } else if (hours > 0) {
            this.countdown = `${hours}h ${minutes}m ${seconds}s`;
        } else if (minutes > 0) {
            this.countdown = `${minutes}m ${seconds}s`;
        } else {
            this.countdown = `${seconds}s`;
        }
    },

    dismiss() {
        this.open = false;
        clearInterval(this.timer);

        try {
            localStorage.setItem(this.key, '1');
        } catch (e) {
            // Private browsing: the bar stays gone for this page view only.
        }
    },
}));

// The four-box countdown on an event page.
Alpine.data('eventCountdown', (startsAt, endsAt) => ({
    days: '0',
    hours: '00',
    minutes: '00',
    seconds: '00',
    live: false,
    ended: false,
    timer: null,

    init() {
        this.start = new Date(startsAt).getTime();
        this.end = new Date(endsAt).getTime();

        this.tick();
        this.timer = setInterval(() => this.tick(), 1000);
    },

    destroy() {
        clearInterval(this.timer);
    },

    pad(value) {
        return String(value).padStart(2, '0');
    },

    tick() {
        const now = Date.now();

        this.live = now >= this.start && now <= this.end;
        this.ended = now > this.end;

        if (this.live || this.ended) {
            clearInterval(this.timer);
            return;
        }

        const { days, hours, minutes, seconds } = countdownParts(this.start - now);

        this.days = String(days);
        this.hours = this.pad(hours);
        this.minutes = this.pad(minutes);
        this.seconds = this.pad(seconds);
    },
}));

// Restates an event's start time in whatever timezone the visitor is in —
// the audience is spread across 16 locales, so "16:30 BST" isn't much help.
Alpine.data('eventLocalTime', (startsAt, endsAt, timezone) => ({
    label: '',

    init() {
        try {
            const start = new Date(startsAt);
            const end = new Date(endsAt);

            // Nothing to add for a visitor whose clock already reads the same.
            if (start.toLocaleString('en-GB', { timeZone: timezone }) === start.toLocaleString('en-GB')) {
                return;
            }

            const zone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            const time = (date) => date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const day = start.toLocaleDateString([], { weekday: 'long', day: 'numeric', month: 'long' });

            this.label = `${day}, ${time(start)} – ${time(end)} (${zone})`;
        } catch (e) {
            this.label = '';
        }
    },
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
