export default function filamentVoiceTranscribe({ state, language }) {
    return {
        state,
        language,
        isSupported: false,
        isSpeechSynthesisSupported: false,
        isRecording: false,
        isSpeaking: false,
        error: null,
        recognition: null,
        baseState: '',
        shouldStopRecording: false,
        voices: [],

        init() {
            this.validateSupport();
        },

        validateSupport() {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

            this.isSupported = Boolean(SpeechRecognition);
            this.isSpeechSynthesisSupported = 'speechSynthesis' in window && 'SpeechSynthesisUtterance' in window;

            if (this.isSpeechSynthesisSupported) {
                this.loadVoices();
                window.speechSynthesis.onvoiceschanged = () => this.loadVoices();
            }

            if (! this.isSupported) {
                return;
            }

            this.recognition = new SpeechRecognition();
            this.recognition.lang = this.language || 'en-US';
            this.recognition.interimResults = true;
            this.recognition.continuous = true;

            this.bindRecognitionEvents();
        },

        bindRecognitionEvents() {
            this.recognition.onresult = (event) => {
                const transcript = this.getTranscriptFromResults(event.results);

                if (transcript.length > 0) {
                    this.state = this.joinTranscripts(this.baseState, transcript);
                }
            };

            this.recognition.onend = () => {
                if (this.isRecording && ! this.shouldStopRecording) {
                    this.restartRecording();

                    return;
                }

                this.isRecording = false;
                this.shouldStopRecording = false;
            };

            this.recognition.onerror = () => {
                this.isRecording = false;
                this.shouldStopRecording = false;
            };
        },

        startRecording() {
            if (this.isRecording) {
                this.stopRecording();

                return;
            }

            if (! this.isSupported || ! this.recognition) {
                return;
            }

            this.error = null;
            this.baseState = this.normalizeTranscript(this.state);
            this.shouldStopRecording = false;

            try {
                this.isRecording = true;
                this.recognition.start();
            } catch (error) {
                this.error = error;
                this.isRecording = false;
                this.shouldStopRecording = false;
            }
        },

        stopRecording() {
            if (! this.recognition || ! this.isRecording) {
                return;
            }

            this.shouldStopRecording = true;
            this.recognition.stop();
        },

        restartRecording() {
            try {
                this.recognition.start();
            } catch (error) {
                this.error = error;
                this.isRecording = false;
                this.shouldStopRecording = false;
            }
        },

        getTranscriptFromResults(results) {
            return Array.from(results)
                .map((result) => result[0]?.transcript ?? '')
                .join('')
                .trim();
        },

        joinTranscripts(currentValue, transcript) {
            currentValue = this.normalizeTranscript(currentValue);
            transcript = this.normalizeTranscript(transcript);

            if (currentValue.length === 0) {
                return transcript;
            }

            if (transcript.length === 0) {
                return currentValue;
            }

            return `${currentValue} ${transcript}`;
        },

        normalizeTranscript(value) {
            return String(value ?? '').trim();
        },

        speakText(text = null) {
            if (! this.isSpeechSynthesisSupported) {
                return;
            }

            this.loadVoices();

            if (text instanceof Event) {
                text = null;
            }

            const content = this.normalizeTranscript(text ?? this.state);

            if (content.length === 0) {
                return;
            }

            if (this.isSpeaking) {
                window.speechSynthesis.cancel();
                this.isSpeaking = false;

                return;
            }

            const utterance = new SpeechSynthesisUtterance(content);
            const preferredVoice = this.getPreferredVoice();

            utterance.lang = this.language || 'en-US';

            if (preferredVoice) {
                utterance.voice = preferredVoice;
            }

            utterance.onend = () => {
                this.isSpeaking = false;
            };

            utterance.onerror = () => {
                this.isSpeaking = false;
            };

            this.isSpeaking = true;
            window.speechSynthesis.speak(utterance);
        },

        getPreferredVoice() {
            const voices = this.voices;

            if (voices.length === 0) {
                return null;
            }

            const exactLanguage = this.normalizeLocale(this.language);
            const baseLanguage = this.normalizeLanguage(this.language);

            if (exactLanguage.length === 0) {
                return null;
            }

            const exactLanguageVoices = voices.filter((voice) => this.normalizeLocale(voice.lang) === exactLanguage);
            const baseLanguageVoices = voices.filter((voice) => this.normalizeLanguage(voice.lang) === baseLanguage);

            return exactLanguageVoices[0] ?? baseLanguageVoices[0] ?? null;
        },

        normalizeLanguage(language) {
            return this.normalizeLocale(language).split('-')[0];
        },

        normalizeLocale(language) {
            return String(language || '').replaceAll('_', '-').toLowerCase();
        },

        loadVoices() {
            this.voices = window.speechSynthesis.getVoices();
        },
    }
}
