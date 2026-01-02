import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

interface BeforeInstallPromptEvent extends Event {
  readonly platforms: string[];
  readonly userChoice: Promise<{ outcome: 'accepted' | 'dismissed'; platform: string }>;
  prompt: () => Promise<void>;
}

const DISMISS_KEY = 'pwa-install-dismissed-at';
const DISMISS_TTL_DAYS = 14;

const isIosUserAgent = () => {
  if (typeof window === 'undefined') return false;
  return /iphone|ipad|ipod/i.test(window.navigator.userAgent);
};

const isStandaloneMode = () => {
  if (typeof window === 'undefined') return false;
  const nav = window.navigator as Navigator & { standalone?: boolean };
  return window.matchMedia('(display-mode: standalone)').matches || nav.standalone === true;
};

const isDismissedRecently = () => {
  if (typeof window === 'undefined') return false;
  const value = window.localStorage.getItem(DISMISS_KEY);
  if (!value) return false;
  const timestamp = Number(value);
  if (Number.isNaN(timestamp)) return false;
  const ttlMs = DISMISS_TTL_DAYS * 24 * 60 * 60 * 1000;
  return Date.now() - timestamp < ttlMs;
};

export const usePwaInstall = () => {
  const deferredPrompt = ref<BeforeInstallPromptEvent | null>(null);
  const canPrompt = ref(false);
  const installed = ref(false);
  const dismissed = ref(false);
  const iosDevice = ref(false);
  const handleBeforeInstallPrompt = (event: Event) => {
    event.preventDefault();
    deferredPrompt.value = event as BeforeInstallPromptEvent;
    canPrompt.value = true;
  };
  const handleAppInstalled = () => {
    installed.value = true;
    deferredPrompt.value = null;
    canPrompt.value = false;
  };

  const hidePrompt = () => {
    dismissed.value = true;
    if (typeof window !== 'undefined') {
      window.localStorage.setItem(DISMISS_KEY, String(Date.now()));
    }
  };

  const requestInstall = async () => {
    if (!deferredPrompt.value) return { outcome: 'dismissed' as const };
    await deferredPrompt.value.prompt();
    const choice = await deferredPrompt.value.userChoice;
    if (choice.outcome === 'accepted') {
      installed.value = true;
      deferredPrompt.value = null;
      canPrompt.value = false;
    }
    return choice;
  };

  onMounted(() => {
    iosDevice.value = isIosUserAgent();
    installed.value = isStandaloneMode();
    dismissed.value = isDismissedRecently();

    window.addEventListener('beforeinstallprompt', handleBeforeInstallPrompt);
    window.addEventListener('appinstalled', handleAppInstalled);
  });

  onBeforeUnmount(() => {
    if (typeof window === 'undefined') return;
    window.removeEventListener('beforeinstallprompt', handleBeforeInstallPrompt);
    window.removeEventListener('appinstalled', handleAppInstalled);
  });

  const showPrompt = computed(() => !installed.value && !dismissed.value && canPrompt.value);
  const showIosPrompt = computed(() => !installed.value && !dismissed.value && iosDevice.value && !canPrompt.value);

  return {
    showPrompt,
    showIosPrompt,
    requestInstall,
    hidePrompt,
  };
};
