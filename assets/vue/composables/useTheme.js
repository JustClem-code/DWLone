// useTheme.js
import { ref, onMounted } from 'vue';

const THEME_KEY = 'theme';

export function useTheme() {
  const choice = ref('system'); // 'system' = pas de choix explicite
  const isDark = ref(false);

  const apply = (next) => {
    choice.value = next;
    if (next === 'light') {
      localStorage.setItem(THEME_KEY, 'light');
      document.documentElement.classList.remove('dark');
      // data-attr: document.documentElement.setAttribute('data-theme', 'light');
      isDark.value = false;
    } else if (next === 'dark') {
      localStorage.setItem(THEME_KEY, 'dark');
      document.documentElement.classList.add('dark');
      // data-attr: document.documentElement.setAttribute('data-theme', 'dark');
      isDark.value = true;
    } else {
      localStorage.removeItem(THEME_KEY);
      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      document.documentElement.classList.toggle('dark', prefersDark);
      // data-attr: document.documentElement.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
      isDark.value = prefersDark;
    }
  };

  const toggleTheme = () => apply(isDark.value ? 'light' : 'dark');

  onMounted(() => {
    const saved = localStorage.getItem(THEME_KEY);
    if (saved === 'light' || saved === 'dark') {
      apply(saved);
    } else {
      apply('system');
    }

    // Optionnel: écouter les changements système si 'system'
    const mq = window.matchMedia('(prefers-color-scheme: dark)');
    const onChange = () => {
      if (choice.value === 'system') apply('system');
    };
    mq.addEventListener?.('change', onChange);
  });

  return { choice, isDark, apply, toggleTheme };
}
