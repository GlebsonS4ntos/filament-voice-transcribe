export default function filamentVoiceTranscribe({ state, language }) {
    return {
        state,
        language,
        isSupported: false,
        isRecording: false,
        error: null,
        recognition: null,
        baseState: '',
        shouldStopRecording: false,

        init() {
            this.validateSupport();
        },

        validateSupport() {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

            this.isSupported = Boolean(SpeechRecognition);

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
    }
}
