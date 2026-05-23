import tw from 'twin.macro';
import { createGlobalStyle } from 'styled-components/macro';
// @ts-expect-error untyped font file
import font from '@fontsource-variable/ibm-plex-sans/files/ibm-plex-sans-latin-wght-normal.woff2';

export default createGlobalStyle`
    @font-face {
        font-family: 'IBM Plex Sans';
        font-style: normal;
        font-display: swap;
        font-weight: 100 700;
        src: url(${font}) format('woff2-variations');
        unicode-range: U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0304,U+0308,U+0329,U+2000-206F,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD;
    }

    /* Kroxy Premium B&W Theme */
    :root {
        --kroxy-bg: #0a0a0a;
        --kroxy-surface: #111111;
        --kroxy-surface2: #1a1a1a;
        --kroxy-border: rgba(255,255,255,0.08);
        --kroxy-border-bright: rgba(255,255,255,0.18);
        --kroxy-text: #f0f0f0;
        --kroxy-muted: #888888;
        --kroxy-accent: #ffffff;
        --kroxy-glow: rgba(255,255,255,0.06);
    }

    body {
        ${tw`font-sans text-neutral-200`};
        background-color: var(--kroxy-bg);
        letter-spacing: 0.015em;
    }

    h1, h2, h3, h4, h5, h6 {
        ${tw`font-medium tracking-normal font-header`};
    }

    p {
        ${tw`text-neutral-200 leading-snug font-sans`};
    }

    form {
        ${tw`m-0`};
    }

    textarea, select, input, button, button:focus, button:focus-visible {
        ${tw`outline-none`};
    }

    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none !important;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield !important;
    }

    /* Kroxy Scrollbar */
    ::-webkit-scrollbar {
        background: none;
        width: 14px;
        height: 14px;
    }

    ::-webkit-scrollbar-thumb {
        border: solid 0 rgb(0 0 0 / 0%);
        border-right-width: 4px;
        border-left-width: 4px;
        -webkit-border-radius: 9px 4px;
        -webkit-box-shadow: inset 0 0 0 1px rgba(255,255,255,0.15), inset 0 0 0 4px rgba(255,255,255,0.08);
    }

    ::-webkit-scrollbar-track-piece {
        margin: 4px 0;
    }

    ::-webkit-scrollbar-thumb:horizontal {
        border-right-width: 0;
        border-left-width: 0;
        border-top-width: 4px;
        border-bottom-width: 4px;
        -webkit-border-radius: 4px 9px;
    }

    ::-webkit-scrollbar-corner {
        background: transparent;
    }

    /* Page load animation */
    @keyframes kroxy-fadein {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    #app > * {
        animation: kroxy-fadein 0.35s ease-out forwards;
    }

    /* Card/box premium styling */
    .kroxy-card {
        background: var(--kroxy-surface);
        border: 1px solid var(--kroxy-border);
        border-radius: 6px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .kroxy-card:hover {
        border-color: var(--kroxy-border-bright);
        box-shadow: 0 0 0 1px rgba(255,255,255,0.04), 0 4px 24px rgba(0,0,0,0.5);
    }
`;
